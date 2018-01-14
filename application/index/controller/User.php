<?php
/**
 * Created by PhpStorm.
 * User: winton
 * Date: 2018/1/5
 * Time: 15:31
 */

namespace app\index\controller;

use think\Db;

class User extends Base
{
    public function _initialize()
    {
        parent::_initialize();

        if (empty(session('user'))) {
            return $this->success('请先登录', url('index/login/index'));
        }
    }

    public function user()
    {
        $user = session('user');

        //用户统计订单时，先更新订单状态
        Db::name('order')
            ->where('status', '=', 0)
            ->where('order_time', '<', date('Y-m-d H:i:s', time() - (60 * 30)))
            ->where('u_id', '=', $user['id'])
            ->update(['status' => 4, 'real_price' => 0]);

        $status = Db::name('order')->where([
            'u_id' => $user['id']
        ])
            ->group("`status`")
            ->field("`status`, COUNT(*) as count")
            ->select();

        $order = [0,0,0,0];
        foreach ($status as $value) {
            $order[$value['status']] = $value['count'];
        }

        $offset = mt_rand(99, 1999);
        $rand = Db::name('publication')->limit($offset, 6)->select();

        $this->assign('rand', $rand);
        $this->assign('order', $order);
        $this->assign('user', $user);
        return $this->fetch();
    }

    public function orderList()
    {
        $user = session('user');
        $status = input('status');

        $db = Db::name('user')->alias([
            'user'  => 'u',
            'order' => 'o',
        ])
            ->join('order', 'o.u_id=u.id');

        if (is_numeric($status)) {
            $db->where([
                'u.id' => $user['id'],
                'o.status' => $status,
            ]);
        } else {
            $db->where([
                'u.id' => $user['id'],
            ]);
        }

        $orders = $db->field('o.*')
            ->order('order_time desc')
            ->paginate(10);


        $items = $orders->items();
        $page = $orders->render();
        $o_ids = array_column($items, 'id');

        $publications = Db::name('order_publication')->alias([
            'order_publication' => 'op',
            'publication'   => 'p',
        ])
            ->join('publication', 'op.p_id = p.id')
            ->where('op.o_id', 'in', $o_ids)
            ->field('p.cover, p.`name`, p.author, op.o_id, op.p_id, op.quantity, op.price')
            ->select();

        foreach ($items as $key => $item) {
            foreach ($publications as $publication) {
                if ($publication['o_id'] == $item['id']) {
                    $items[$key]['publication'][] = $publication;
                }
            }

            switch ($item['status']) {
                case 0:
                    $items[$key]['status_zh'] = '未支付';
                    break;
                case 1:
                    $items[$key]['status_zh'] = '已支付';
                    break;
                case 2:
                    $items[$key]['status_zh'] = '已发货';
                    break;
                case 3:
                    $items[$key]['status_zh'] = '已收货';
                    break;
                case 4:
                    $items[$key]['status_zh'] = '交易关闭';
                    break;
                default:
                    $items[$key]['status_zh'] = '订单状态未知';
            }
        }

        $this->assign('user', $user);
        $this->assign('items', $items);
        $this->assign('page', $page);
        return $this->fetch();
    }

    public function address()
    {
        $user = session('user');
        $concact = Db::name('contact')
            ->where(['u_id' => $user['id']])
            ->order('id DESC')
            ->paginate(4);

        $this->assign('user', $user);
        $this->assign('contact', $concact);
        return $this->fetch();
    }

    public function addAddress()
    {
        $user = session('user');
        $data = input('post.');

        $count = Db::name('contact')->where('u_id', $user['id'])->count();
        if ($count >= 8) {
            return json_result(false, '收货地址最多8条');
        }

        $data['u_id'] = $user['id'];
        $result = Db::name('contact')->insertGetId($data);

        if ($result) {
            return json_result(true, '添加成功');
        }

        return json_result(false, '添加失败');
    }

    public function saveAddress()
    {
        $user = session('user');
        $data = input('post.');
        $id = $data['id'];
        unset($data['id']);

        $result = Db::name('contact')
            ->where('u_id', $user['id'])
            ->where('id', $id)
            ->data($data)
            ->update();

        if ($result !== false) {
            $this->success('编辑成功');
        }

        $this->error('编辑失败');
    }

    public function delAddress()
    {
        $id = input('id');

        $result = Db::name('contact')->where('id', $id)->delete();
        if ($result) {
            return json_result(true, '删除成功');
        }

        return json_result(false, $result);
    }

}