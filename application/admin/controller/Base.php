<?php
/**
 * Created by PhpStorm.
 * User: winton
 * Date: 2017/10/11
 * Time: 22:38
 */

namespace app\admin\controller;

use think\Controller;

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
        $this->assign('user', $this->userInfo);
    }

}