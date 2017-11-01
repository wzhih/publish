<?php
/**
 * Created by PhpStorm.
 * User: winton
 * Date: 2017/10/29
 * Time: 20:53
 */

namespace app\index\controller;

use think\Controller;

class Base extends Controller
{
    public function _initialize()
    {

    }

    public function test()
    {
        echo "Base Controller test";
    }

}