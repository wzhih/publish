<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{
    public function index()
    {
        return 'admin/index/index';
    }

    public function test(){
        if(!checkPermission(1,2)){
            $this->error('权限不足');
        }
        $data = Db::name('admin')->find();
        $this->assign("data", $data);
        return $this->fetch();
    }
}
