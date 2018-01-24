<?php
/**
 * Created by PhpStorm.
 * User: winton
 * Date: 2018/1/24
 * Time: 15:53
 */

namespace app\index\controller;

use think\Db;
use think\Controller;
use alipay\payment\Pay;


class Payment extends Controller
{
    /**
     * 输出支付页面
     * @param $order_id
     * @param $subject
     * @param $total_amount
     * @param string $body
     * @return bool|mixed|SimpleXMLElement|string|提交表单HTML文本
     */
    public function ali_payment($order_id, $subject, $total_amount, $body = '')
    {
        $config = config('alipay');
        $order = [
            'order_id' => $order_id,
            'subject' => $subject,
            'total_amount' => $total_amount,
            'body' => $body,
        ];

        $pay = new Pay();
        return $pay->payPage($config, $order);
    }

    //未完善
    public function refund()
    {
        $config = config('alipay');
        $order = [];

        $pay = new Pay();
        return $pay->refundPage($config, $order);
    }

    //支付宝异步通知
    public function notify_url()
    {
        $data = input('post.');

        $config = config('alipay');
        $pay = new Pay($config);
        if (!$pay->check($config, $data)) {
            //没有通过支付宝异步通知验签
            return ;
        }

        if ($config['app_id'] != $data['app_id']) {
            return ;
        }
        $order_id = $data['out_trade_no'];
        $total_amount = $data['total_amount'];
        $order_status = $data['trade_status'];
        switch ($order_status) {
            case 'TRADE_SUCCESS':
            case 'TRADE_FINISHED':
                //支付成功更新订单状态
                Db::name('order')
                    ->where("id = $order_id")
                    ->where('status = 0')
                    ->update([
                        'status' => 1,
                        'real_price' => $total_amount,
                    ]);
                break;
            default:
                return;
        }

    }

    //支付宝同步通知
    public function return_url()
    {
        $data = input('get.');

        $config = config('alipay');
        $pay = new Pay($config);
        if (!$pay->check($config, $data)) {
            //没有通过支付宝异步通知验签
            return ;
        }

        if ($config['app_id'] != $data['app_id']) {
            return ;
        }
        $order_id = $data['out_trade_no'];
        $total_amount = $data['total_amount'];
        $order_status = $data['trade_status'];
        switch ($order_status) {
            case 'TRADE_SUCCESS':
            case 'TRADE_FINISHED':
                //支付成功更新订单状态
                Db::name('order')
                    ->where("id = $order_id")
                    ->where('status = 0')
                    ->update([
                        'status' => 1,
                        'real_price' => $total_amount,
                    ]);
                break;
            default:
                return;
        }

        return $this->success('支付成功', url('index/User/orderList'));
    }
}