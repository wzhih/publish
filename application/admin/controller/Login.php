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
            return $this->error('用户名,密码以及验证码皆不能为空' , url('admin/Login/login'));
        }
        if(!captcha_check($captcha)){
            return $this->error('验证码错误' , url('admin/Login/login'));
        }

        $result = Db::name('admin')
            ->where('name' , ':name')
            ->where('password' , ':password')
            ->bind([
                    'name' => [$username , \PDO::PARAM_INT] ,
                    'password' => [md5($password) , \PDO::PARAM_STR]
                ])
            ->find();
        if(!$result){
            return $this->error('用户名或密码错误' , url('admin/Login/login'));
        }
        //登陆之后，将信息存入session
        session('userInfo' , $result);
        session('login' , true);

        return $this->redirect('admin/Index/index');
    }

    public function logout(){
        //清除登陆信息
        session(null);
        return $this->fetch('login');
    }

    public function getCaptcha(){
        return generateCaptcha();
    }

    public function test(){
//        $result = Db::name('category')
//            ->where('parent_id' , '<>' , 0)
//            ->order('parent_id')
//            ->select();
//
//        var_dump((list_to_tree($result)));

        // var_dump(sendMessage());
        // return;

//        echo "getToken    ";
        // print_r(getUserToken(1,'admin' , 'logo'));
        return;
    }

}