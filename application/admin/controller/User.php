<?php
/**
 * Created by PhpStorm.
 * User: winton
 * Date: 2017/10/12
 * Time: 23:12
 */

namespace app\admin\controller;

use think\Db;

class User extends Base
{
    public function index() {
        $data = Db::name('user')->paginate(10);

        $this->assign('data', $data);
        return $this->fetch();
    }

}