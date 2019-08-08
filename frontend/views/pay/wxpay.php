<?php
use yii\helpers\Url;
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
header("Content-type: text/html; charset=utf-8");
require_once(dirname(__FILE__)."/../../../wxpay/lib/WxPay.Api.php");
require_once(dirname(__FILE__)."/../../../wxpay/example/WxPay.NativePay.php");
require_once(dirname(__FILE__)."/../../../wxpay/example/log.php");
$fee=($order_msg->order_price*100);
$notify = new NativePay();
$input = new WxPayUnifiedOrder();
$input->SetBody("易起名");
$input->SetAttach("易起名");
$input->SetOut_trade_no($order_msg->order_sn);
$input->SetTotal_fee("$fee");
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("易起名");
$input->SetNotify_url("http://bbqm.yiqiming.net/wxpay/example/notify.php");
$input->SetTrade_type("NATIVE");
$input->SetProduct_id($order_msg->order_id);
$result = $notify->GetPayUrl($input);
$url2 = $result["code_url"];
?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script type="text/javascript" src="<?=Url::base()?>/frontend/web/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?=Url::base()?>/backend/web/plugins/layer/layer.js"></script>
    <title>易起名|支付页面</title>
</head>
<!--主体部分开始-->
<!---右侧开始-->
<script>
    setInterval("checkOrderStatus()",2000);
</script>
<style>
    .confirm_layer,.ewm_layer{ width:100%; height:100%; background:#eff1f1; position:fixed; top:0; left:0; z-index:999;}
    .fn-clear {zoom: 1;}
    .topbar{width:100%;height:26px; background:#81878b;}
    .topbar .topbar-wrap { width: 950px; margin: 0 auto;}
    .topbar .topbar-wrap span { float: right; color: #fff; font-size:13px; padding: 4px 15px;}
    #header {width: 100%; float:left; height: 60px; background-color: #fff; border-bottom: 1px solid #d9d9d9;}
    .header-container { width: 950px; margin: 0 auto;}
    #header .header-title { width: 250px;height: 60px;float: left;}
    .alipay-logo {display: block; position: relative; left: 0; top: 10px; float: left; background-position: 0 0;  background-repeat: no-repeat; }
    #header .logo-title {font-size: 16px;font-weight: normal;font-family: "Microsoft YaHei",微软雅黑,"宋体";border-left: 1px solid #676d70;color: #676d70;height: 20px;float: left;margin-top: 15px;margin-left: 10px;padding-top: 10px;padding-left: 10px;}
    .orderDetail-base{ width:840px; margin: 0 auto; margin-top:15px; padding: 16px 23px; position: relative;}
    .order-extand-explain span{ font-size:12px;}
    .commodity-message-row {height:30px;overflow: hidden; padding-top: 14px;}
    .commodity-message-row .first { font-weight: 700; font-size: 15px; float: left; margin-right:20px;}
    .commodity-message-row .second { float: left; max-width:160px; font-size: 14px; margin-top: 1px;overflow: hidden; text-overflow: ellipsis; white-space: nowrap;}
    .payAmount-area {position: absolute;bottom:36px;right: 23px;text-align: right;z-index: 1;}
    .payAmount-area .amount-font-22 {font-size: 21px;line-height: 21px;color: #3eb135;}


    .confirm_layer .sider{ width:740px; margin:0 auto; background:#fff; padding:40px 80px; padding-bottom:80px;text-align: center; border-top: 3px solid #b3b3b3; border-bottom: 3px solid #b3b3b3;}
    .confirm_layer .my_info{ padding:20px 0;}
    .confirm_clo{ position:absolute; top:0px; right:10px; font-size:30px; color:#ddd; cursor:pointer; transition:all 0.4s;}
    .confirm_clo:hover{ color:#999;}
    .zhyts{color: #F18A00}
    #footer { margin-top: 20px;text-align: center;width: 100%;padding: 10px 0;zoom: 1;}
    .copyright,.copyright a,.copyright a:hover{color:#808080; font-size:13px; text-decoration:none;}
    .ewm_layer img{ position:absolute; top:30%; left:50%; margin-left:-100px; width:200px; height:200px; padding:15px; border-radius:8px; background:#fff;}
    #partner { margin-top: 10px; padding: 20px 0 16px; text-align: center;}
</style>
<div class="confirm_layer">
    <div class="topbar">
        <div class="topbar-wrap fn-clear">
            <span class="topbar-link-first">你好，欢迎使用微信付款！</span>
        </div>
    </div>
    <div id="header">
        <div class="header-container fn-clear">
            <div class="header-title" style="">
                <div class="alipay-logo" style="">
                    <img src="<?=Url::base()?>/frontend/web/images/weipay.jpg" width="50px" height="50px">
                </div>
                <span class="logo-title">我的收银台</span>
            </div>
        </div>
    </div>
    <div style="clear:both;"></div>
    <div class="orderDetail-base">
        <div class="order-extand-explain fn-clear">
            <span>正在使用微信扫码支付</span>
        </div>
        <div class="commodity-message-row">
            <span class="first">
                微信付款
            </span>
                <span>
                收款方：易起名
            </span>
        </div>
            <span class="payAmount-area">
<!--            <strong class="amount-font-22 ">1.00</strong> 元-->
        </span>
    </div>
    <div class="sider">
        <span style="font-size:14px;">扫一扫付款（ <span style="font-size: 20px;font-weight: bold"><?=$order_msg->order_price?></span> 元）</span>
        <h2 style="margin-bottom: 10px; margin-top:10px; color:#3eb135"></h2>
        <div class="zhyts">
            <strong style="font-size:12px;">尊敬的客户，支付结束之后，我们会以短信的方式提醒卖方在最短的时间内发货，并查看已买到的宝贝</strong>
        </div>
        <div class="my_info">
            <dl class="clearfix linp">
                <dd>
                    <div class="icobox" style="height: 200px;width: 300px;">
                        <img alt="模式二扫码支付" src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo urlencode($url2);?>" style="width:200px;height:200px;margin-left: 75%"/>
                    </div>
                </dd>
            </dl>
            </dl>
        </div>
    </div>
    <div id="footer">
        <div class="copyright">
            <a href="#">易起名 </a>
        </div>
    </div>
    <div id="partner"></div>
</div>
<input type="hidden" id="order_sn" value="<?=$order_msg->order_sn?>">
<script>
    var order_sn=$('#order_sn').val();
    function checkOrderStatus(){
        $.ajax({
            url:"<?=Url::toRoute(['index/check-order-status'])?>",
            type:"POST",
            data:{
                order_sn:order_sn
            },
            success:function(data){
                var obj=jQuery.parseJSON(data);
                if(obj.status==1){
                    layer.msg('支付成功!',{icon:1,time:1500});
                    setTimeout(window.location.href="/",3000)
                }else{

                }
            }
        })
    }
</script>
</body>
</html>


