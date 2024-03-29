<?php
/**
 * Created by PhpStorm.
 * User: winton
 * Date: 2017/10/29
 * Time: 20:53
 */

namespace app\index\controller;

use think\Db;
use think\Controller;

class Base extends Controller
{
    public function _initialize()
    {
        $user = session('user');
        $this->assign('cid', '');
        $this->assign('user', $user);
    }

    //获取一级分类
    protected function getFirstCategory()
    {
        return Db::name('category')
            ->where("parent_id", 1)
            ->where('id', '<>', 1)
            ->select();
    }

    //根据父id获取二级分类
    protected function getSecondCategory($cid)
    {
        if ($cid) {
            return Db::name('category')
                ->where('parent_id', '<>', 1)
                ->where('parent_id', '=', $cid)
                ->select();
        }

        return Db::name('category')
            ->where('parent_id', '<>', 1)
            ->select();
    }

    public function about()
    {
        $this->assign('cid', 'about');
        return $this->fetch('layout/about');
    }

    public function proprieter()
    {
        $this->assign('cid', '');
        return $this->fetch('layout/proprieter');
    }

    public function structure()
    {
        $this->assign('cid', '');
        return $this->fetch('layout/structure');
    }

    public function department()
    {
        $this->assign('cid', '');
        return $this->fetch('layout/department');
    }
}