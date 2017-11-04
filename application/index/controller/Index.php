<?php

namespace app\index\controller;

use think\Db;

class Index extends Base
{
    public function index()
    {
        $cid = $this->getInputCId();

        $this->assign('cid', $cid);
        return $this->fetch();
    }

    public function getHeadNav()
    {
        $cid = $this->getInputCId();
        $result = $this->getFirstCategory();

        foreach ($result as &$value) {
            $value['url'] = url("index/index/index", "cid={$value['id']}");

            if ($value['id'] == $cid) {
                $value['class'] = 'active';
            } else {
                $value['class'] = '';
            }
        }

        return json_result(true, 'success', $result);
    }

    private function getInputCId()
    {
        $cid = input('cid', 0);//根据类型id的不同显示不同的页面

        if ($cid == 0) {
            $cid = Db::name('category')
                ->where("parent_id", 1)
                ->where('id', '<>', 1)
                ->order('id')
                ->value('id');
        }

        return $cid;
    }

    public function search()
    {
        $name = input('searchName');

        return $name;
    }

    public function getSwiper()
    {
        $result = Db::name('swiper')->select();

        return json_result(true, 'success', $result);
    }

    public function getCategory()
    {
        $cid = input('cid');

        $result = $this->getSecondCategory($cid);

        return json_result(true, 'success', $result);
    }

    public function getBookList()
    {
        $cid = input('childId');
        $row = input('row', 9);
        $page = input('page', 1);

        $data = Db::name('publication')->where('c_id', $cid)
            ->limit($row * ($page - 1), $row)
            ->select();

        return json_result(true, 'success', $data);
    }
}
