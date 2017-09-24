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
    }

    public function index()
    {
        return $this->fetch();
    }

    public function test(){
        echo "getToken    ";
        print_r(getUserToken(1,'admin' , 'logo'));
        return;
    }

}
