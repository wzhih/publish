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

//将后台用户的角色转换出来
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
 * @param $phone
 * @return bool
 */
function sendMessage($phone){

    return true;
}

/**
 * 获取用户token
 * @param $userId
 * @param $name
 * @param $portraitUri
 * @return mixed
 */
function getUserToken($userId, $name, $portraitUri=null){
    $appKey = config('APP_KEY');
    $appSecret = config('APP_SECRET');

    $RongCloud = new RongCloud($appKey,$appSecret);
    // 获取 Token 方法
    $result = $RongCloud->user()->getToken($userId ,$name , $portraitUri);

    return $result;
}

