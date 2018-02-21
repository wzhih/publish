<?php
/**
 * Created by PhpStorm.
 * User: winton
 * Date: 2018/2/20
 * Time: 22:08
 */

namespace app\admin\controller;

use think\Db;

class Evaluation extends Base
{
    public function index()
    {
        $u_id = input('uid');
        $p_id = input('pid');

        $condition = [];
        $query = [];
        if ($u_id) {
            $condition['e.u_id'] = $u_id;
            $query['u_id'] = $u_id;
        }
        if ($p_id) {
            $condition['e.p_id'] = $p_id;
            $query['p_id'] = $p_id;
        }

        $data = Db::name('evaluation e')
                    ->join('user u', 'u.id = e.u_id')
                    ->join('publication p', 'p.id = e.p_id')
                    ->field('e.*, u.`name` as username, p.cover, p.`name` as pname ')
                    ->where($condition)
                    ->order('e.etime', 'DESC')
                    ->paginate(10, false, [
                        'query' => $query,
                    ]);

        $this->assign('uid', $u_id);
        $this->assign('pid', $p_id);
        $this->assign('data', $data);
        return $this->fetch();
    }

    public function save()
    {
        $condition = input('post.');

        $result = Db::name('evaluation')->update($condition);

        if ($result) {
            return $this->success('修改成功');
        }

        return $this->error('修改失败');
    }

    public function del()
    {
        $eid = input('eid');

        $result = Db::name('evaluation')->where('id', '=', $eid)->delete();

        if ($result) {
            return $this->success('删除成功');
        }

        return $this->error('删除失败');
    }

}