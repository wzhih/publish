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

    public function getBook()
    {
        $bookId = input('bookId', 1);

        
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

    //根据类别，返回书籍信息
    public function getBookListByCategory()
    {
        $cid = $this->getInputCId();//大类型
        $childId = input('childId', 0);//小类型

        $childIds[] = $childId;
        if ($childId == 0) {
            $childIds = Db::name('category')->where('parent_id', $cid)->column('id');
        }

        $config = [
            'query' => ['cid' => $cid, 'childId' => $childId],
        ];
        $data = DB::name('publication')->where('c_id', 'IN', $childIds)->paginate(12, false, $config);

        $this->assign('cid', $cid);
        $this->assign('data', $data);
        return $this->fetch('list');
    }
}
