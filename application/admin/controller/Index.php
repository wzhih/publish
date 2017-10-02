<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;


class Index extends Controller
{
    public function _initialize()
    {
        //此处判断是否登陆
        $isLogin = session('login');
        if(!$isLogin){
            $this->error('你还未登录，请登录之后操作!');
        }
    }

    public function index()
    {

        return $this->fetch();
    }




}
