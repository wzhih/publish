<?php
/**
 * Created by PhpStorm.
 * User: winton
 * Date: 2018/1/24
 * Time: 15:31
 */
namespace alipay\payment;

require_once dirname(dirname(__FILE__)).'/pagepay/service/AlipayTradeService.php';
require_once dirname(dirname(__FILE__)).'/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';
require_once dirname(dirname(__FILE__)).'/pagepay/buildermodel/AlipayTradeRefundContentBuilder.php';

//SDK接口转换类
class Pay
{
    public function payPage($config, $order)
    {
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $order['order_id'];

        //订单名称，必填
        $subject = $order['subject'];

        //付款金额，必填
        $total_amount = $order['total_amount'];

        //商品描述，可空
        $body = $order['body'];

        //构造参数
        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);

        $aop = new \AlipayTradeService($config);

        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
         */
        $response = $aop->pagePay($payRequestBuilder, $config['return_url'], $config['notify_url']);

        //输出表单
        return $response;
    }

    /**
     * 退款操作（还没写好）
     * @param $config
     * @param $order
     * @return bool|mixed|\SimpleXMLElement|string|\提交表单HTML文本
     * @throws \Exception
     */
    public function refundPage($config, $order)
    {
        //商户订单号，商户网站订单系统中唯一订单号
        $out_trade_no = $order['order_id'];

        //需要退款的金额，该金额不能大于订单金额，必填
        $refund_amount = $order['refund_amount'];


        //构造参数
        $RequestBuilder=new \AlipayTradeRefundContentBuilder();
        $RequestBuilder->setOutTradeNo($out_trade_no);
        $RequestBuilder->setRefundAmount($refund_amount);

        $aop = new \AlipayTradeService($config);

        /**
         * alipay.trade.refund (统一收单交易退款接口)
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @return $response 支付宝返回的信息
         */
        $response = $aop->Refund($RequestBuilder);
        return $response;
    }

    function check($config, $data)
    {
        return (new \AlipayTradeService($config))->check($data);
    }
}