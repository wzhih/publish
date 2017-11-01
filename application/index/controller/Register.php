<?php
/**
 * Created by PhpStorm.
 * User: winton
 * Date: 2017/10/31
 * Time: 23:58
 */

namespace app\index\controller;

use think\Controller;
use think\Db;

class Register extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function register()
    {
        $phone = input('username');
        $password = input('password');
        $confirmPwd = input('confirmPwd');
        $captcha = input('verifyCode');
        $code = input('dynamicCode');

        if ($confirmPwd != $password) {
            return json_result(false, '密码不一致');
        }

        if (!captcha_check($captcha)) {
            return json_result(false, '验证码错误');
        }

        $verifyCode = session('verifyCode');
        if (empty($verifyCode) || (time() - 600) > $verifyCode['time']) {
            return json_result(false, '此验证码已过期，请重新获取');
        }

        if ($code != $verifyCode['code']) {
            return json_result(false, '短信验证码错误');
        }

        $data = [
            'phone' => $phone,
            'password' => md5($password),
        ];

        $user = Db::name('user')->where(['phone' => $phone])->find();
        if ($user) {
            $data['id'] = $user['id'];
            $result = Db::name('user')->data($data)->update();
        } else {
            $result = Db::name('user')->data($data)->insert();
        }

        if ($result === false) {
            return json_result(false, '注册失败');
        }

        session(null);
        return json_result(true, '注册成功');
    }

    //获取手机验证码
    public function getPhoneCode()
    {
        $phone = input('phone');
        $captcha = input('verifyCode');
        $update = input('update');

        if (!captcha_check($captcha)) {
            return json_result(false, '验证码错误');
        }

        if ($verifyCode = session('verifyCode')) {
            if ($phone == $verifyCode['phone'] && (time() - 60) < $verifyCode['time']) {
                return json_result(false, '请在60s之后重新获取短信验证码');
            }
        }

        $result = Db::name('user')->where('phone',$phone)->find();

        if ($result && !$update) {
            return json_result(false, '此手机号码已注册');
        }

//        $code = mt_rand(1000, 9999);
//        $result = sendMessage($phone, $code);
        $code = 1234;
        $result = true;
        if ($result) {
            session('verifyCode', [
                'phone' => $phone,
                'code'  => $code,
                'time'  => time(),
            ]);
            return json_result(true);
        }

        return json_result(false, '发送短信失败');
    }



}