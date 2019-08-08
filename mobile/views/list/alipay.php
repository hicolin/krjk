<?php
$root = Yii::getAlias('@root');
require_once $root . '/mobilealipay/alipay/wappay/service/AlipayTradeService.php';
require_once $root . '/mobilealipay/alipay/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php';
require $root . '/mobilealipay/alipay/config.php';

$out_trade_nos = $post['order_sn'];
//$daili_type = $post['$daili_type'];

if (!empty($out_trade_nos) && trim($out_trade_nos) != "") {

    //商户订单号，商户网站订单系统中唯一订单号，必填
    $out_trade_no = $out_trade_nos;

    //订单名称，必填
    $subject = \common\controllers\PublicController::getSysInfo(14);

    //付款金额，必填
    $total_amount = $post['money'];

    //商品描述，可空
    $body = '金牌会员';

    //超时时间
    $timeout_express = "1m";

    $payRequestBuilder = new AlipayTradeWapPayContentBuilder();
    $payRequestBuilder->setBody($body);
    $payRequestBuilder->setSubject($subject);
    $payRequestBuilder->setOutTradeNo($out_trade_no);
    $payRequestBuilder->setTotalAmount($total_amount);
    $payRequestBuilder->setTimeExpress($timeout_express);

    $payResponse = new AlipayTradeService($config);
    $result = $payResponse->wapPay($payRequestBuilder, $config['return_url'], $config['notify_url']);
    return;
}




