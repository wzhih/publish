<?php
/**
 * Created by PhpStorm.
 * User: winton
 * Date: 2017/10/11
 * Time: 22:38
 */

namespace app\admin\controller;

use think\Controller;
use think\Db;

class Base extends Controller
{
    public $userInfo;

    public function _initialize()
    {
        //此处判断是否登陆
        $isLogin = session('login');
        if (!$isLogin) {
            $this->error('你还未登录，请登录之后操作!', url('admin/Login/login'));
        }
        $this->userInfo = session('userInfo');
        $this->assign('appkey', config('APP_KEY'));
        $this->assign('user', $this->userInfo);
    }

    public function userList() {
        $data = Db::name('admin')->select();

        $result = [];
        foreach ($data as $d) {
            $result[] = ['id' => $d['id'], 'name' => $d['name'], 'portraitUri' => 'http://hr-publish.oss-cn-shenzhen.aliyuncs.com/im/images/logo.png'];
        }

        return json(['userlist' => $result]);
    }

    public function online() {
        $data = Db::name('admin')->select();

        $result = [];
        foreach ($data as $d) {
            $result[] = ['id' => $d['id'], 'status' => true];
        }

        return json(['data' => $result]);
    }
}