<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;

class Login extends Controller{

    public function login(){
        return $this->fetch();
    }

    public function logout(){
        //清除登陆信息

        return $this->fetch('login');
    }

}