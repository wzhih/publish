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
            $this->assign('status', $status+1);
        } else {
            $db->where([
                'u.id' => $user['id'],
            ]);
            $this->assign('status', '');
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
                case 5:
                    $items[$key]['status_zh'] = '已退款';
                    break;
                case 6:
                    $items[$key]['status_zh'] = '已退货';
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

    //用户评论出版物
    public function userEvaluation()
    {
        $p_id = input('p_id');
        $star = input('star', 1);
        $content = input('content', '用户并无详细评论！');

        if (!$p_id || !$star || !$content || ($star < 1) || ($star > 5)) {
            return json_result(false, '参数错误');
        }

        $user = session('user');
        $data = [
            'u_id' => $user['id'],
            'p_id' => $p_id,
            'star' => $star,
            'content' => $content,
            'etime' => date('Y-m-d H:i:s'),
        ];

        $count = Db::name('evaluation')
            ->where('u_id', $user['id'])
            ->where('p_id', $p_id)
            ->count();
        if ($count) {
            return json_result(false, '请勿多次评论！');
        }

        $result = Db::name('evaluation')->insertGetId($data);
        if ($result) {
            return json_result(true, '评论成功');
        }

        return json_result(false, '评论失败，错误:' . $result);
    }

    //用户评论列表
    public function evaluationList()
    {
        $user = session('user');
        $eval = Db::name('evaluation e')
            ->join('publication p', 'e.p_id = p.id')
            ->where('u_id', $user['id'])
            ->field('e.*, p.cover, p.`name`')
            ->order('e.etime', 'DESC')
            ->paginate(10);

        $this->assign('eval', $eval);
        return $this->fetch();
    }

    //用户退款
    public function userRefund()
    {
        $o_id = input('o_id');

        if ($o_id) {
            $result = Db::name('order')
                ->where('status', '=', 1)
                ->where('id', '=', $o_id)
                ->update(['status' => 5]);
        } else {
            return json_result(true, '没有发送订单号');
        }

        $refund_amount = Db::name('order')->where('id', '=', $o_id)->value('real_price');
        @$r = (new Payment())->refund($o_id, $refund_amount);

        if ($result === false) {
            return json_result(true, '退款失败，请联系工作人员。');
        }

        return json_result(true, '退款成功');
    }

    public function changePwd()
    {
        $old = input('old');
        $new = input('new');

        if (!$old || !$new) {
            return json_result(false, '发送参数错误');
        }

        $user = session('user');
        $data = [
            'id' => $user['id'],
            'password' => md5($old),
        ];
        $u = Db::name('user')->where($data)->find();
        if (!$u) {
            return json_result(false, '旧密码错误！');
        }

        $result = Db::name('user')->where($data)->update(['password' => md5($new)]);

        if ($result === false) {
            return json_result(false, '修改密码失败，请联系工作人员！');
        }

        session(null);
        return json_result(true, '修改成功，请重新登录');

    }

    public function changeInfoPage()
    {
        $user = session('user');
        $this->assign('user', $user);
        return $this->fetch('changeInfo');
    }

    public function changeInfo()
    {
        $name = input('name');
        $sex = input('sex');

        if (!$name || !$sex) {
            return $this->error('参数错误，不能为空');
        }

        switch ($sex) {
            case 1:
                $sex = '男';
                break;
            case 2:
                $sex = '女';
                break;
            default:
                $sex = '男';
        }

        $data = [
            'name' => $name,
            'sex' => $sex,
        ];


        $photo_url = uploading('img', 'static'. DS .'portal'. DS .'img'. DS .'user');

        $object = "user/" . basename($photo_url);
        $photo_url = upload_oss($object, $photo_url);

        if ($photo_url) {
            $data['img'] = $photo_url;
        }

        $user = session('user');

        $result = Db::name('user')->where('id', '=', $user['id'])->update($data);

        if ($result === false) {
            return $this->error('修改失败');
        }

        $user = Db::name('user')->where('id', '=', $user['id'])->find();
        session('user', $user);
        return $this->success('修改成功');
    }

}