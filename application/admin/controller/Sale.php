<?php
/**
 * Created by PhpStorm.
 * User: winton
 * Date: 2018/2/24
 * Time: 23:02
 */

namespace app\admin\controller;

use think\Db;

class Sale extends Base
{
    public function ranking()
    {
        $day = date('Y-m-d');
        $today = Db::name('order_publication op')
            ->join('`order` o', "op.o_id = o.id", 'LEFT')
            ->join('publication p', "op.p_id = p.id", 'LEFT')
            ->field("op.p_id, SUM(op.quantity) AS counts, o.order_time, p.`name`")
            ->where('order_time', 'like', "$day%")
            ->group('op.p_id')
            ->order('counts', 'DESC')
            ->limit(0, 10)
            ->select();

        $mon = date('Y-m');
        $month = Db::name('order_publication op')
            ->join('`order` o', "op.o_id = o.id", 'LEFT')
            ->join('publication p', "op.p_id = p.id", 'LEFT')
            ->field("op.p_id, SUM(op.quantity) AS counts, o.order_time, p.`name`")
            ->where('order_time', 'like', "$mon%")
            ->group('op.p_id')
            ->order('counts', 'DESC')
            ->limit(0, 10)
            ->select();

        $yea = date('Y');
        $year = Db::name('order_publication op')
            ->join('`order` o', "op.o_id = o.id", 'LEFT')
            ->join('publication p', "op.p_id = p.id", 'LEFT')
            ->field("op.p_id, SUM(op.quantity) AS counts, o.order_time, p.`name`")
            ->where('order_time', 'like', "$yea%")
            ->group('op.p_id')
            ->order('counts', 'DESC')
            ->limit(0, 10)
            ->select();

        $this->assign('day', $today);
        $this->assign('month', $month);
        $this->assign('year', $year);
        return $this->fetch();
    }

    public function chart()
    {
        $year = input('year');
        $mon = input('mon');
        if (is_numeric($mon) && $mon > 0 && $mon < 13) {
            if (!is_numeric($year)) {
                //$year年$mon月每日销售情况
                $year = date('Y');
            }
            $time = $year . '-' . sprintf('%02d', $mon);
        } else {
            //本月每日销售情况
            $time = date('Y-m');
        }

        $data = Db::name('order_publication op')
            ->join('`order` o', "op.o_id = o.id", 'LEFT')
            ->where('order_time', 'like', "$time%")
            ->field("DATE_FORMAT(order_time,'%e') as `day`, SUM(op.quantity) as sum")
            ->group('`day`')
            ->select();

        $this->assign('t', date('t', strtotime($mon)));
        $this->assign('calcula', json_encode($data));
        $this->assign('datetime', $time);
        return $this->fetch();
    }

}