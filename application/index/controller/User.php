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
    public function user()
    {
        $user = session('user');
        $order = Db::name('order')->where([
            'u_id' => $user['id']
        ])
            ->select();

        $offset = mt_rand(99, 1999);
        $rand = Db::name('publication')->limit($offset, 6)->select();

        $this->assign('cid', '');
        $this->assign('rand', $rand);
        $this->assign('order', $order);
        $this->assign('user', $user);
        return $this->fetch();
    }
}