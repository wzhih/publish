<?php
/**
 * Created by PhpStorm.
 * User: winton
 * Date: 2017/11/4
 * Time: 10:37
 */

namespace app\index\controller;


use think\Db;

class Shop extends Base
{
    public function index()
    {

    }

    public function getShopCartList()
    {
        $user = session('user');
        $data = Db::name('cart')
            ->alias([
                'cart' => 'c',
                'publication' => 'p',
            ])
            ->join('publication', 'c.p_id = p.id')
            ->where([
                'c.u_id' => $user['id']
            ])
            ->select();

        return json_result(true, 'success', $data);
    }


}