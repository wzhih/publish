<?php
/**
 * Created by PhpStorm.
 * User: winton
 * Date: 2018/2/17
 * Time: 21:35
 */

namespace app\admin\controller;

use think\Db;

class Order extends Base
{
    //交易订单控制器
    public function index()
    {
        $u_id = input('uid');
        $p_id = input('pid');

        $condition = [];
        if ($u_id) {
            $condition['o.u_id'] = $u_id;
        }
        if ($p_id) {
            $condition['op.p_id'] = $p_id;
        }

        $data = Db::name('`order` o')
            ->join('user u', 'o.u_id = u.id')
            ->join('order_publication op', 'o.id = op.o_id')
            ->join('contact c', 'o.c_id = c.id', 'LEFT')
            ->where($condition)
            ->field('o.*, u.name as username, c.`name`, c.phone, c.province, c.city, c.area, c.address')
            ->order('id', 'DESC')
            ->group('o.id')
            ->paginate(10, false, [
                'query' => [
                    'uid' => $u_id,
                    'pid' => $p_id,
                ]
            ]);

        $this->assign('uid', $u_id);
        $this->assign('pid', $p_id);
        $this->assign('data', $data);
        return $this->fetch();
    }

    public function save()
    {
        $o_id = input('oid');
        if (!$o_id) {
            return $this->error('订单id参数错误');
        }

        $order = Db::name('`order` o')
            ->join('user u', 'o.u_id = u.id', 'LEFT')
            ->join('contact c', 'o.c_id = c.id', 'LEFT')
            ->where('o.id', '=', $o_id)
            ->field("o.*, u.`name` as username, c.`name`, c.phone, c.province, c.city, c.area, c.address")
            ->find();

        $publication = Db::name('order_publication op')
            ->join('publication p', "op.p_id = p.id")
            ->where('op.o_id', '=', $o_id)
            ->field("op.quantity, op.price, p.id, p.cover, p.`name`")
            ->select();

        $this->assign('order', $order);
        $this->assign('publication', $publication);
        return $this->fetch();
    }

    public function update()
    {
        $o_id = input('oid');
        $c_id = input('cid');
        if (!$o_id) {
            return $this->error('参数错误');
        }

        $info = input('post.');

        Db::startTrans();
        try {
            Db::name('order')->where('id', '=', $o_id)->update([
                'status' => $info['status'],
                'fms' => $info['fms'],
                'fms_number' => $info['fms_number'],
            ]);

            if ($c_id) {
                Db::name('contact')->where('id', '=', $c_id)->update([
                    'name' => $info['name'],
                    'phone' => $info['phone'],
                    'province' => $info['province'],
                    'city' => $info['city'],
                    'area' => $info['area'],
                    'address' => $info['address'],
                ]);
            }

            Db::commit();
            $result = true;
        } catch (\Exception $e) {
            Db::rollback();
            $result = false;
        }

        if ($result) {
            return $this->success('更新成功');
        }

        return $this->error('更新失败');
    }


}