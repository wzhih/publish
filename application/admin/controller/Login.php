<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;

class Login extends Controller{

    public function login(){
        return $this->fetch();
    }

    public function index(){
        $username = input('username');
        $password = input('password');
        $captcha = input('captcha');
        if(empty($username) || empty($password) || empty($captcha)){
            return $this->error('用户名,密码以及验证码皆不能为空');
        }
        if(!captcha_check($captcha)){
            return $this->error('验证码错误');
        }

        $result = Db::name('admin')
            ->where('name' , ':name')
            ->where('password' , ':password')
            ->bind([
                    'name' => [$username , \PDO::PARAM_INT] ,
                    'password' => [md5($password) , \PDO::PARAM_STR]
                ])
            ->find();
        if($result){
            return $this->error('用户名或密码错误');
        }
        //登陆之后，将信息存入session


        return $this->redirect('admin/Index/index');
    }

    public function logout(){
        //清除登陆信息

        return $this->fetch('login');
    }

    public function getCaptcha(){
        return generateCaptcha();
    }

}