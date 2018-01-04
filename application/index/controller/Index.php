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
        $cid = $this->getInputCId();//在哪一大分类下查询
        $name = input('name');
        $order = input('order','date desc');

        $childIds = Db::name('category')->where('parent_id', $cid)->column('id');

        $config = [
            'query' => ['cid' => $cid, 'name' => $name, 'order' => $order],
        ];
        $data = DB::name('publication')
            ->where('c_id', 'IN', $childIds)
            ->where('name', 'LIKE', "%$name%")
            ->order($order)
            ->paginate(12, false, $config);

        switch ($order){
            case 'date DESC':
                $order = 1;
                break;
            case 'price':
                $order = 2;
                break;
            case 'price DESC':
                $order = 3;
                break;
        }

        $this->assign('cid', $cid);
        $this->assign('name', $name);
        $this->assign('order', $order);
        $this->assign('data', $data);
        return $this->fetch();
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

    //获取图书详情，与随机9本书籍
    public function getBook()
    {
        $bookId = input('bookId', 1);

        $bookInfo = Db::name('publication')->find(['id' => $bookId]);
        $cid = Db::name('category')->where(['id' => $bookInfo['c_id']])->value('parent_id');

        $offset = mt_rand(99, 1999);
        $rand = Db::name('publication')->limit($offset, 9)->select();

        $this->assign('book', $bookInfo);
        $this->assign('rand', $rand);
        $this->assign('cid', $cid);
        return $this->fetch('book');
    }

    //根据二级类别，分页列出所有图书
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

    //根据类别和排序选项，分页列出所有图书（用于查找图书结果页面）
    public function getBookListByCategory()
    {
        $cid = $this->getInputCId();//大类型
        $childId = input('childId', 0);//小类型
        $order = input('order','date desc');

        $childIds[] = $childId;
        if ($childId == 0) {
            $childIds = Db::name('category')->where('parent_id', $cid)->column('id');
        }

        $config = [
            'query' => ['cid' => $cid, 'childId' => $childId, 'order' => $order],
        ];
        $data = DB::name('publication')
            ->where('c_id', 'IN', $childIds)
            ->order($order)
            ->paginate(12, false, $config);

        switch ($order){
            case 'date DESC':
                $order = 1;
                break;
            case 'price':
                $order = 2;
                break;
            case 'price DESC':
                $order = 3;
                break;
        }

        $this->assign('cid', $cid);
        $this->assign('childId', $childId);
        $this->assign('order', $order);
        $this->assign('data', $data);
        return $this->fetch('list');
    }
}
