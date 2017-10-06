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

    //根据条件显示出版物
    public function showPublication(){
        //列出出版物类别，以便根据类别查询出版物
        $category = Db::name('category')
            ->where('id' , '<>' , 1)
            ->order('parent_id')
            ->select();
        $category = arraySequence(list_to_tree($category) , 'id');

        //传入类别id，查询出该类别所有出版物
        $cid = input('cid');
        $map = [];
        if(!empty($cid)){
            $map['c.id'] = $cid;
        }
        //默认日期倒序显示最新的出版物
        $dateSort = input('dateSort' , 'desc');

        //传入搜索关键字，以此搜索出版物
        $publication = input('publication');
        if(!empty($publication)){
            $map['p.name'] = ['like','%'.$publication.'%'];

        }

        $result = Db::name('publication')
                    ->alias('p')
                    ->join('category c' , 'c.id=p.c_id')
                    ->field('p.id as pid,p.*,c.*')
                    ->where($map)
                    ->order('p.date '.$dateSort.',p.id '.$dateSort)
                    ->paginate(10 , false , [
                        'query'=>['publication'=>$publication]
                    ]);

        $this->assign('category' , $category);
        $this->assign('data' , $result);
        return $this->fetch();
    }
    //增加出版物
    public function addPublication(){

    }
    //删除出版物
    public function deletePublication(){
        $id = input('pid');
        if(empty($id)){
            return false;
        }
        $result = Db::name('publication')
            ->where('id' , ':id')
            ->bind([
                'id' => [$id , \PDO::PARAM_INT]
            ])
            ->delete();

        if($result === false){
            $this->error('删除失败');
        }
        return $this->success('删除成功');
    }
    //修改出版物
    public function savePublication(){
        $id = input('pid');

    }


}
