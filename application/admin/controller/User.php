<?php
/**
 * Created by PhpStorm.
 * User: winton
 * Date: 2017/10/12
 * Time: 23:12
 */

namespace app\admin\controller;

use think\Db;

class User extends Base
{
    public function index() {
        $data = Db::name('user')->order('id DESC')->paginate(10);

        $this->assign('data', $data);
        return $this->fetch();
    }

    public function update() {
        $user = input('post.');

        if (empty($user['password'])) {
            unset($user['password']);
        } else {
            $user['password'] = md5($user['password']);
        }

        $result = Db::name('user')->data($user)->update();
        if ($result !== false) {
            $this->success('更新成功');
        }

        $this->error('更新失败');
    }

    public function add() {
        $user = input('post.');
        $user['password'] = md5($user['password']);

        $result = Db::name('user')->data($user)->insert();
        if ($result) {
            $this->success('添加成功');
        }

        $this->error('添加失败');
    }

    public function delete() {
        $uid = input('id');

        $result = Db::name('user')->delete(['id' => $uid]);
        if ($result) {
            $this->success('删除成功');
        }

        $this->error('删除失败');
    }
}