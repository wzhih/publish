<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use api\RongCloud;
use api\Test;

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
        //(new Test())->index();return;
        $appKey = config('APP_KEY');
        $appSecret = config('APP_SECRET');
        $jsonPath = "jsonsource/";
        $RongCloud = new RongCloud($appKey,$appSecret);
        // 获取 Token 方法
        $result = $RongCloud->user()->getToken('userId1', 'username', 'http://www.rongcloud.cn/images/logo.png');
        echo "getToken    ";
        print_r($result);
        return;
        return $this->fetch('/layout/main');
    }

}
