<?php
/**
 * Created by PhpStorm.
 * User: winton
 * Date: 2017/10/11
 * Time: 22:34
 */

namespace app\admin\controller;

use think\Db;

class Admin extends Base
{
    public function _initialize()
    {
        parent::_initialize();
        if($this->request->action() != 'changepassword'){
            if($this->userInfo['role'] != 1){
                //如果不是管理员角色，不能操作此控制器
                $this->error('权限不足');
            }
        }
    }

    public function addAdmin(){
        $name = input('name');
        $password = input('password' , '123456');
        $role = input('role' , 2);

        $data = ['name'=>$name , 'password'=>md5($password) , 'role' => $role];

        $result = Db::name('admin')->data($data)->insert();

        if($result){
            $this->success('添加成功');
        }
        $this->error('添加失败');
    }

    public function deleteAdmin(){
        $id = input('id');

        if($id == 1){
            $this->error('没有权限删除第一管理员');
        }

        $result = Db::name('admin')->where(['id' => $id])->delete();

        if($result){
            $this->success('删除成功');
        }
        $this->error('删除失败');

    }

    public function saveAdmin(){
        $id = input('id');
        $old = md5(input('old'));
        $new = md5(input('new'));

        if($name = input('name')){
            $data['name'] = $name;
        }

        if($role = input('role')){
            $data['role'] = $role;
        }

        if($old && $new){
            $user = Db::name('admin')->where('password' , $old)->find();
            if($user){
                $data['password'] = $new;
            }
        }

        $result = Db::name('admin')->where('id' , $id)->update($data);

        if($result){
            $this->success('更新成功');
        }

        $this->error('更新失败');

    }

    public function showAdmin(){
        $admins = Db::name('admin')->paginate(10);

        $this->assign('data' , $admins);
        return $this->fetch();
    }

    public function changePassword(){
        $id = input('id');
        $old = input('old');
        $new = input('new');

        if(empty($old) || empty($new)){
            $this->error('密码填写不能为空');
        }

        $admin = Db::name('admin')->where(['id'=>$id , 'password'=>md5($old)])->find();
        if(empty($admin)){
            $this->error('密码错误');
        }

        $result = Db::name('admin')->where('id' , $id)->update(['password'=>md5($new)]);

        if($result){
            $this->success('更新成功');
        }

        $this->error('更新失败');
    }

}