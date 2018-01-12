<?php
/**
 * Created by PhpStorm.
 * User: winton
 * Date: 2017/11/4
 * Time: 10:37
 */

namespace app\index\controller;

use Throwable;
use think\Db;

class Shop extends Base
{
    public function _initialize()
    {
        parent::_initialize();
        if (empty(session('user'))) {
            return $this->success('请先登录', url('index/login/index'));
        }
    }

    private function getCartData()
    {
        $user = session('user');
        return Db::name('cart')
            ->alias([
                'cart' => 'c',
                'publication' => 'p',
            ])
            ->join('publication', 'c.p_id = p.id')
            ->field("c.id as cart_id, c.u_id, c.p_id, c.quantity, p.id, p.cover, p.img, p.ISBN, p.`name`, p.price, p.author, p.chinaClassification, p.classificationName, p.date, p.number, p.c_id, p.pages, p.size")
            ->where([
                'c.u_id' => $user['id']
            ])
            ->order('c.id DESC')
            ->select();
    }

    //显示购物车页面
    public function cart()
    {
        $data = $this->getCartData();

        $this->assign('cid', '');
        $this->assign('cart', $data);

        $user = session('user');
        switch ($user['type']) {
            case 0:
                return $this->fetch();
            case 1:
                return $this->fetch('big_cart');
        }
    }

    //用于购物车页面js统计
    public function getCart()
    {
        $data = $this->getCartData();

        return json_result(true, 'success', $data);
    }

    //用于head统计购物车物品数量
    public function getShopCartList()
    {
        $data = $this->getCartData();

        return json_result(true, 'success', $data);
    }

    //更新购物车商品数量
    public function saveCart()
    {
        $bookId = input('bookId', 0);
        $quantity = input('quantity', 0);
        if (!$bookId || !$quantity) {
            return json_result(false, '参数错误');
        }

        $user = session('user');
        if ($user['type'] == 1 && $quantity < 100) {
            $quantity = 100;
        }

        $data = Db::name('cart')->where([
            'u_id' => $user['id'],
            'p_id' => $bookId,
        ])->find();

        if ($data) {
            $data['quantity'] = $quantity;
            $result = Db::name('cart')->update($data);

        } else {
            $data = [
                'u_id' => $user['id'],
                'p_id' => $bookId,
                'quantity' => $quantity,
            ];
            $result = Db::name('cart')->insert($data);

        }

        return json_result($result);
    }

    //删除购物车里的商品
    public function delCart()
    {
        $bookId = input('bookId', 0);
        if (!$bookId) {
            return json_result(false, '参数错误');
        }

        $user = session('user');
        $result = Db::name('cart')->where([
            'p_id' => $bookId,
            'u_id' => $user['id'],
        ])
            ->delete();

        return json_result($result);
    }

    public function addCart()
    {
        $bookId = input('bookId', 0);
        $quantity = input('quantity', 0);
        if (!$bookId || !$quantity) {
            return json_result(false, '参数错误');
        }

        $user = session('user');
        if ($user['type'] == 1 && $quantity < 100) {
            $quantity = 100;
        }

        $data = Db::name('cart')->where([
            'u_id' => $user['id'],
            'p_id' => $bookId,
        ])->find();

        if ($data) {
            $data['quantity'] += $quantity;
            $result = Db::name('cart')->update($data);

        } else {
            $data = [
                'u_id' => $user['id'],
                'p_id' => $bookId,
                'quantity' => $quantity,
            ];
            $result = Db::name('cart')->insert($data);

        }

        return json_result($result);
    }

    //结算购物车，$checkId是购物车表id，
    //生成订单，并跳转到订单页面
    public function settleCart()
    {
        $checkId = input('checkId/a');
        if (!$checkId) {
            return json_result(false, '参数错误');
        }

        //开始事务
        Db::startTrans();
        $user = session('user');

        try {
            $data = Db::name('cart')
                ->alias([
                    'cart' => 'c',
                    'publication' => 'p',
                ])
                ->join('publication', 'c.p_id = p.id')
                ->field("c.*, p.price")
                ->where([
                    'c.u_id' => $user['id']
                ])
                ->whereIn('c.id', $checkId)
                ->select();

            $total_price = 0;
            foreach ($data as $value)
            {
                $total_price = bcadd($total_price, bcmul($value['quantity'], $value['price'], 2), 2);
            }
            //先插入订单表
            $order_id = Db::name('order')->insertGetId([
                'u_id' => $user['id'],
                'c_id' => 0,
                'total_price' => $total_price,
                'real_price' => $total_price,
                'order_time' => date('Y-m-d H:i:m'),
                'status' => 0,
            ]);

            $op = [];
            foreach ($data as $value)
            {
                $op[] = [
                    'o_id' => $order_id,
                    'p_id' => $value['p_id'],
                    'quantity' => $value['quantity'],
                    'price' => $value['price'], //单件商品单价
                ];
            }
            $ops = Db::name('order_publication')->insertAll($op);

            $del = Db::name('cart')->whereIn('id', $checkId)->delete();

            Db::commit();
            return json_result(true, $order_id);
        } catch (Throwable $e) {
            Db::rollback();
            return json_result(false, $e->getMessage());
        }

    }

    public function order()
    {
        $order_id = input('order_id');
        if (!$order_id) {
            $this->error('没有此订单');
        }
        $user = session('user');

        $contact = Db::name('contact')->where("u_id = {$user['id']}")->select();

        $data = Db::name('user')->alias([
            'user'  => 'u',
            'order' => 'o',
            'order_publication' => 'op',
            'publication'   => 'p',
        ])
            ->join('order', 'o.u_id=u.id')
            ->join('order_publication', 'op.o_id')
            ->join('publication', 'op.p_id = p.id')
            ->where([
                'u.id' => $user['id'],
                'o.id' => $order_id,
                'op.o_id' => $order_id,
            ])
            ->field('o.id, p.id as p_id, p.cover, p.`name`, p.author, op.quantity, op.price, o.total_price')
            ->select();

        $total_price = 0;
        if (isset($data[0]['total_price'])) {
            $total_price = $data[0]['total_price'];
        }

        $this->assign('cid', '');
        $this->assign('o_id', $order_id);
        $this->assign('contact', $contact);
        $this->assign('data', $data);
        $this->assign('total_price', $total_price);
        switch ($user['type']) {
            case 0:
                return $this->fetch();
            case 1:
                return $this->fetch('big_order');
        }
    }

    //订单页面确认支付（其实是更新订单的地址）
    public function payment()
    {
        $contact_id = input('contact_id');
        $order_id = input('order_id');

        if (!$contact_id || !$order_id) {
            return json_result(false, '参数错误');
        }

        $result = Db::name('order')->where("id = $order_id")->update(['c_id' => $contact_id]);

        return json_result(true);
    }

    public function paymentConfirm()
    {
        //支付成功，订单记录状态修改
        $order_id = input('order_id');

        if (!$order_id) {
            return json_result(false, '参数错误');
        }

        //这里应该顺便更新real_price字段，保留大订单客户真实付款金额
        $result = Db::name('order')->where("id = $order_id")->update(['status' => 1]);

        return $this->success('支付成功，跳转到订单列表。', url('index/user/orderList'));
    }
}