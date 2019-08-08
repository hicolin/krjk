<?php
/* *
 * 功能：支付宝手机网站支付接口(alipay.trade.wap.pay)接口调试入口页面
 * 版本：2.0
 * 修改日期：2016-11-01
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 请确保项目文件有可写权限，不然打印不了日志。
 */
use yii\helpers\Url;
header("Content-type: text/html; charset=utf-8");
require_once dirname(__FILE__).'/../../../alipay-wap/alipay/config.php';
require_once dirname(__FILE__).'/../../../alipay-wap/alipay/wappay/service/AlipayTradeService.php';
require_once dirname(__FILE__).'/../../../alipay-wap/alipay/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php';
    //商户订单号，商户网站订单系统中唯一订单号，必填
    $out_trade_no =$order->order_sn;
    //订单名称，必填
    $subject = '易起名';
    //付款金额，必填
    $total_amount = '0.01';
    //商品描述，可空
    $body = '';
    //超时时间
    $timeout_express="1m";
    $payRequestBuilder = new AlipayTradeWapPayContentBuilder();
    $payRequestBuilder->setBody($body);
    $payRequestBuilder->setSubject($subject);
    $payRequestBuilder->setOutTradeNo($out_trade_no);
    $payRequestBuilder->setTotalAmount($total_amount);
    $payRequestBuilder->setTimeExpress($timeout_express);
    $payResponse = new AlipayTradeService($config);
    $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);
    return ;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>支付宝支付</title>
    <script src="<?=Url::base()?>/frontend/web/js/jquery-1.8.3.min.js"></script>
</head>
<script>
    var order_sn=$('#order_sn').val();
    setInterval("checkOrderStatus()",500);
    function checkOrderStatus(){
        $.ajax({
            url:"<?=Url::toRoute(['index/check-order-status'])?>",
            type:"POST",
            data:{
                order_sn:order_sn
            },
            success:function(data){

            }
        })
    }
</script>
<body>
<input type="hidden" id="order_sn" value="<?=$order->order_sn?>">
</body>
</html>
