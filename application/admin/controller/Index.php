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
        $isLogin = session('login');
        if(!$isLogin){
            $this->error('你还未登录，请登录之后操作!' , url('admin/Login/login'));
        }
    }

    public function index()
    {

        return $this->fetch();
    }

    public function category(){
        $result = Db::name('category')
            ->where('id' , '<>' , 1)
            ->order('parent_id')
            ->select();
        $result = arraySequence(list_to_tree($result) , 'id');

        $this->assign('data' , $result);

        return $this->fetch();
    }

    //删除出版物类别
    public function deleteCategory(){
        $cid = input('cid');
        if(empty($cid)){
            return false;
        }
        $result = Db::name('category')
            ->where('id' , ':cid')
            ->bind([
                'cid' => [$cid , \PDO::PARAM_INT]
            ])
            ->delete();

        return $result;
    }

    //增加出版物类别
    public function addCategory(){
        $category = input('cname');
        $pid = input('pid',1);
        if(empty($category)){
            return $this->error('添加失败');
        }

        $result = Db::name('category')->data(['category'=>$category,'parent_id'=>$pid])->insert();

        if($result){
            return $this->success('添加成功');
        }
        return $this->error('添加失败');
    }

    //出版物类别修改
    public function saveCategory(){
        $cid = input('cid');
        $category = input('cname');

        if(empty($cid) || empty($category)){
            return $this->error('类别名不能为空');
        }
        $result = Db::name('category')->update(['category'=>$category,'id'=>$cid]);

        if($result !== false){
            return $this->success('更改成功');
        }
        return $this->error('更改失败');
    }
}
