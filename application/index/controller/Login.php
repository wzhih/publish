<?php
/**
 * Created by PhpStorm.
 * User: winton
 * Date: 2017/10/29
 * Time: 20:56
 */

namespace app\index\controller;

use think\Controller;
use think\Db;

class Login extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function login()
    {
        $name = input('username');
        $password = md5(input('password'));
        $captcha = input('verifyCode');

        if (!captcha_check($captcha)) {
            //验证失败
            return json_result(false, '验证码错误', []);
        };

        $result = Db::name('user')
            ->where(['name' => $name])
            ->whereOr(['phone' => $name])
            ->find();
        if (!$result) {
            return json_result(false, '不存在此用户', []);
        }

        $result = Db::name('user')
            ->where(['name|phone' => $name, 'password' => $password])
            ->find();
        if (!$result) {
            return json_result(false, '用户密码错误', []);
        }

        session('user', $result);
        return json_result(true, '登陆成功', url('index/index/index'));
    }

    public function logout(){
        //清除登陆信息
        session(null);
        return $this->redirect(url('index/index/index'));
    }

    public function resetPassword()
    {
        return action('index/register/register', input('post.'));
    }

    public function getChaptCha()
    {
        return generateCaptcha();
    }


}