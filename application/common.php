<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use api\RongCloud;
use AliyunSms\SmsDemo;
use think\captcha\Captcha;

/**
 * 将后台用户的角色转换出来
 *
 * @param $role
 * @return string
 */
function roleConversion($role){
    switch ($role){
        case 0:
            return '超级管理员';
        case 1:
            return '管理员';
        case 2:
            return '客服';
    }
}

/**
 * 后台用户检查权限，当用户角色等级低于权限等级时，跳转error
 *
 * @param int $role 用户角色等级
 * @param int $permission 权限等级
 * @return bool
 */
function checkPermission($role , $permission){
    if($role < $permission){
        return false;
    }
    return true;
}


/**
 * 发送验证消息
 *
 * @param $phone 验证手机号
 * @param $code 验证码
 * @return bool 是否发送成功
 */
function sendMessage($phone='18814128257' , $code='123456789'){
    $accessKeyId = config('AccessKeyID');//参考本文档步骤2
    $accessKeySecret = config('AccessKeySecret');//参考本文档步骤2

    $Sms = new SmsDemo($accessKeyId , $accessKeySecret);
    $acsResponse = $Sms->sendSms(
                        '华软出版社' ,
                        'SMS_98720006' ,
                        $phone ,
                        array('code'=>$code)
                    );
    if($acsResponse->Code == 'OK'){
        return true;
    }
    return false;
}

/**
 * 获取用户token
 *
 * @param $userId
 * @param $name
 * @param $portraitUri
 * @return mixed
 */
function getUserToken($userId, $name, $portraitUri='logo'){
    $appKey = config('APP_KEY');
    $appSecret = config('APP_SECRET');

    $RongCloud = new RongCloud($appKey,$appSecret);
    // 获取 Token 方法
    $result = $RongCloud->user()->getToken($userId ,$name , $portraitUri);

    return $result;
}

/**
 * 生成验证码
 *
 * @param null $config
 * @return \think\Response
 */
function generateCaptcha($config=null){
    if($config == null){
        $config = [
            'codeSet' => '0123456789',
            'length'  => 4,
            'imageH'  => 0,
            'imageW'  => 0
        ];
    }

    $captcha = new Captcha($config);
    return $captcha->entry();
}


/**
 * 无极限分类，把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pk 主键字段
 * @param string $pid parent标记字段
 * @param string $child 子数据集键名
 * @param string $root 初始等级标记字段
 * @return array
 */
function list_to_tree($list, $pk='id', $pid = 'parent_id', $child = 'child', $root = 1) {
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }

        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId =  $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}

