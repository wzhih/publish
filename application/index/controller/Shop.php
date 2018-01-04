<?php
/**
 * Created by PhpStorm.
 * User: winton
 * Date: 2017/11/4
 * Time: 10:37
 */

namespace app\index\controller;


use think\Db;

class Shop extends Base
{
    private function getCartData()
    {
        $user = session('user');
        return Db::name('cart')
            ->alias([
                'cart' => 'c',
                'publication' => 'p',
            ])
            ->join('publication', 'c.p_id = p.id')
            ->field("c.u_id, c.p_id, c.quantity, p.id, p.cover, p.img, p.ISBN, p.`name`, p.price, p.author, p.chinaClassification, p.classificationName, p.date, p.number, p.c_id, p.pages, p.size")
            ->where([
                'c.u_id' => $user['id']
            ])
            ->select();
    }

    //显示购物车页面
    public function cart()
    {
        $data = $this->getCartData();

        $this->assign('cid', '');
        $this->assign('cart', $data);

        return $this->fetch();
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

    //结算购物车，$checkId是购物车表id
    public function settleCart()
    {
        $checkId = input('checkId/a');
        if (!$checkId) {
            return json_result(false, '参数错误');
        }

        return json_result($checkId);
    }

}