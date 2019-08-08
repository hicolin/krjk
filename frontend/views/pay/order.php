<?php
use yii\helpers\Url;
use backend\models\AdminRegions;
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
header("Content-type: text/html; charset=utf-8");
require_once(dirname(__FILE__)."/../../../wxpay/lib/WxPay.Api.php");
require_once(dirname(__FILE__)."/../../../wxpay/example/WxPay.NativePay.php");
require_once(dirname(__FILE__)."/../../../wxpay/example/log.php");
$notify = new NativePay();
$input = new WxPayUnifiedOrder();
$input->SetBody("易起名");
$input->SetAttach("易起名");
$input->SetOut_trade_no($order->order_sn);
$input->SetTotal_fee($order->order_price*100);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("易起名");
$input->SetNotify_url("http://bbqm.yiqiming.net/wxpay/example/notify.php");
$input->SetTrade_type("NATIVE");
$input->SetProduct_id($order->order_id);
$result = $notify->GetPayUrl($input);
$url2 = $result["code_url"];
$arr=[1=>'男',0=>'女'];
$arr1=[1=>'双字名',0=>'单字名'];
?>
<?php
if(($order->add_time+60*15)<time()){
    ?>
    <script>
        aler('该订单已过期!');
        window.location.href="/";
    </script>
    <?php
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>收银台</title>
    <script src="<?=Url::base()?>/frontend/web/js/jquery-1.8.3.min.js"></script>
    <script src="<?=Url::base()?>/backend/web/plugins/layer/layer.js"></script>
    <link rel="stylesheet" type="text/css" href="<?=Url::base()?>/frontend/web/css/order.css">
    <style>
        /*蒙层*/
        .mengcheng{width:100%;position:fixed;top:0;left:0;z-index:198712272396;display:none;}
        .mengcheng01{width:100%;background:#000;opacity: 0.5;filter: alpha(opacity=50);}
        .mengcheng02{border:1px solid #CFCFCF;width:343px;height:260px;background:#FFF;margin: auto;position:fixed;top: 0; left: 0; bottom: 0; right: 0;}
        .mengcheng02 h1{height:47px;position:relative;}
        .ppcs{border:1px solid #53e022;width:220px;height:45px;margin:auto;text-align:center;line-height:45px;font-size:15px;color:#535353;border-radius:5px;cursor:pointer;}
        #mc-p{margin-top:40px;margin-bottom:25px;}
        .mc-paction{background:#53e022;color:#FFF;}
        .xx-xx{width:16px;height:17px;position:absolute;top:13px;right:9px;cursor:pointer;}
        .wx_ewm_zq{width:80%;margin:-55% 0px 0px 10%;z-index:4;position:relative;border:0px solid red;}
        .wx_ewm_zq_bg{height:252px;margin-top:-252px;opacity:0.8; background:#666;filter:alpha(opacity=80);z-index:2;position:relative;}
        .dn{display:none;}.cur{cursor:pointer;}
        .dit{display:inline-table;}
        .dib{display:inline-block;}
        .db{display:block;}
        .fl{float:left;}
        .fr{float:right;}
        .cor4{color:#FFF;}
        .mrt5{margin-top:5px;}
        .mrl5{margin-left:5px;}
    </style>
    <script>
        $(document).ready(function(e) {
            //保存支付数据
            setTimer("#backTimerID",14,60);
        });
        function setTimer(obj,minute,second){
            minute = (new RegExp(/\d+/)).test(minute)?minute:15;
            second = (new RegExp(/\d+/)).test(second)?second:59;
            if(parseInt(minute) <=0 && parseInt(second)<=0){
                clearTimeout( $("div").data("back_timer_flag") );return;
            }
            if(second==0){
                minute-- ;
                second = 60 ;
            }
            second-- ;
            $(obj).text( ((new RegExp(/^\d{2}$/)).test(minute)?minute:"0"+minute) +"分"+((new RegExp(/^\d{2}$/)).test(second)?second:"0"+second)+"秒" );
            $("div").data("back_timer_flag",setTimeout(function(){setTimer(obj,minute,second)},1000) );

        }
    </script>
</head>

<body style="height: 944px;">
<div class="header">
    <div class="box"><span>收银台</span></div>
</div>

<div class="order boxcom">
    <h2><span>订单信息</span><a class="cur" onClick="history.go(-1)">&lt;&lt;返回修改</a></h2>
    <div class="box on">
        <div class="box-content">
            <div class="content">
                <p>
                    <span>易起名订单号：</span>
                    <span> <?=$order->order_sn?></span>
                </p>
                <p>
				  <span> <?=$order->surname?><span>
				  <span><?=$arr[$order->sex]?></span>
				  <span> <?=$arr1[$order->name_type]?></span>
				  <span>公历</span>
				  <span> <?=$order->birthday?></span>
				</span></span>
                </p>
                <p>
                    <span>邮箱</span><span><?=$order->email?></span>
                </p>
                <p>
                    <?php
                    if($order->taboo_word!=null){
                        ?>
                    <span>禁忌<span>: <?=$order->taboo_word?></span>-
                        <?php
                    }
                    if($order->seniority!=null){
                        ?>
                        <span>字辈</span><span><?=$order->seniority?></span>-
                        <?php
                    }
                    if($order->province!=null){
                        ?>
                        <span>出生地</span><span><?=AdminRegions::getRegionName($order->province)?>-<?=AdminRegions::getRegionName($order->city)?>-<?=AdminRegions::getRegionName($order->district)?></span>-
                        <?php
                    }
                    ?>

                </span></p>
                <?php
                if($order->remark!=null){
                    ?>
                    <p>
                        <span>备注</span>
                        <span> <?=$order->remark?></span>
                    </p>
                    <?php
                }
                ?>

            </div>
        </div>
        <div class="more more1"><span >查看详情</span></div>
        <div class="more more2"><span >收起详情</span></div>
        <div class="money">支付金额:<span>￥<?=$order->order_price?>元</span></div>
    </div>
</div>

<div class="payaway boxcom">
    <h2>请选择支付方式  <span>剩余支付时间<em id="backTimerID">13分57秒</em>，逾期订单将自动取消</span></h2>

    <form target="_blank" action="<?=Url::toRoute(['pay/alipay'])?>" onSubmit="return submit_pay()" method="post">
        <dl>
            <dt>第三方支付 <span>￥<?=$order->order_price?></span></dt>
            <dd>
                <div class="pay on"  data-type="1"><img src="<?=Url::base()?>/frontend/web/images/wxp.jpg"><i></i></div>
                <div class="pay"  data-type="2"><img src="<?=Url::base()?>/frontend/web/images/alp.jpg"><i></i></div>
            </dd>
            <input type="hidden" id="type" value="1">
            <input type="hidden" id="order_sn" value="<?=$order->order_sn?>">
            <input type="hidden" name="order_id" id="order_id" value="<?=$order->order_id?>">
        </dl>
        <button style="border: none;" type="submit" class="submit" >确认支付</div>
    </form>
<input type="hidden" id="add_time" value="<?=$order->add_time?>">
</div>

<div class="footer">
    ©2017 起名网
</div>

<!--微信支付弹出层-->
<div class="mask"></div>
<div class="welchatpay">
    <h2>使用微信支付<em>￥<?=$order->order_price?></em></h2>
    <div class="img"><img class="wx_ewm_img" src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo urlencode($url2);?>">
        <div class="w_100 wx_ewm_zq_bg dn"></div>
        <div id="tip" class="wx_ewm_zq father cor4 dn">
            <img src="<?=Url::base()?>/frontend/web/images/correct.png" class="db fl mrt5" style="width:16px;height:12px;">
            <span id="time" class="db fl mrl5" style="width:150px;font-size:16px;">支付成功3秒后跳转</span>
        </div>

    </div>
    <div class="hint">
        请用微信扫一扫<br>扫描二维码即可完成支付
    </div>
    <span class="close" onClick="close_weixin()">×</span>
</div>
<!--付款提示-->
<div class="mengcheng">
    <div class="mengcheng01" style="height: 944px;"></div>
    <div class="mengcheng02">
        <h1 style="background:url(/images/mc.jpg) no-repeat;"><p class="xx-xx">×</p></h1>
        <p id="mc-p" class="ppcs mc-paction">更改支付方式</p>
        <p id="ppcs" class="ppcs">已付款</p>
    </div>
</div>

<script>
    $(function(){
        $('.pay').click(function(){
            $(this).addClass('on');
            $(this).siblings().removeClass('on');
            $('#type').val($(this).attr('data-type'))
        })
    })
</script>
<script>
    var order_sn=$('#order_sn').val();
    var a=3;
    function submit_pay(){
        var order_id=$('#order_id').val();
        var timestamp = $.now();
        var time=$('#add_time').val();

        if((parseInt(time)+parseInt(15*60))<parseInt(timestamp/1000)){
            alert('该订单已过期!');
            window.location.href="/";
            return false;
        }else if($('#type').val()==1){
            $('.mask').show();
            $('.welchatpay').show();
            setInterval("checkOrderStatus()",2000);
            return false;
        }else if($('#type').val()==2){
            setInterval("checkOrderStatus2()",2000);
            $('.mengcheng').show();
            return true;
        }
    }
    function close_weixin(){
        $('.mask').hide();
        $('.welchatpay').hide();
    }
    function checkOrderStatus(){
        $.ajax({
            url:"<?=Url::toRoute(['index/check-order-status2'])?>",
            type:"POST",
            data:{
                order_sn:order_sn
            },
            success:function(data){
                var obj=jQuery.parseJSON(data);
                if(obj.status==1){
                    $('.dn').show();
                   $('#tip').show();
                    setInterval("daojishi()",1000)

                }else{

                }
            }
        })
    }
    function checkOrderStatus2(){
        $.ajax({
            url:"<?=Url::toRoute(['index/check-order-status2'])?>",
            type:"POST",
            data:{
                order_sn:order_sn
            },
            success:function(data){
                var obj=jQuery.parseJSON(data);
                if(obj.status==1){
                    window.location.href="<?=Url::toRoute(['index/index','out_trade_no'=>$order->order_sn])?>";
                }else{

                }
            }
        })
    }

    function daojishi(){
        a=a-1;
        $('#time').html("支付成功"+a+"秒后跳转");
        if(a==0){
            window.location.href="<?=Url::toRoute(['index/index','out_trade_no'=>$order->order_sn])?>";
        }
    }
</script>
<script>
    var order_sn=$('#order_sn').val();
    $(function(){
        $('#mc-p').click(function(){
            $.ajax({
                url:"<?=Url::toRoute(['index/check-order-status2'])?>",
                type:"POST",
                data:{
                    order_sn:order_sn
                },
                success:function(data){
                    var obj=jQuery.parseJSON(data);
                    if(obj.status==1){
                       alert('该订单已支付!',{icon:1});
                        window.location.href="/";
                    }else{
                       alert('该订单未支付!',{icon:2});
                        $('.mengcheng').hide();
                    }
                }
            })
        });
        $('#ppcs').click(function(){
            $.ajax({
                url:"<?=Url::toRoute(['index/check-order-status2'])?>",
                type:"POST",
                data:{
                    order_sn:order_sn
                },
                success:function(data){
                    var obj=jQuery.parseJSON(data);
                    if(obj.status==1){
                       alert('该订单已支付!',{icon:1});
                        window.location.href="/";
                    }else if (obj.status==2){
                        alert('该订单未支付!',{icon:2,tips:1});
                    }
                }
            })
        });
        $('.xx-xx').click(function(){
            $.ajax({
                url:"<?=Url::toRoute(['index/check-order-status2'])?>",
                type:"POST",
                data:{
                    order_sn:order_sn
                },
                success:function(data){
                    var obj=jQuery.parseJSON(data);
                    if(obj.status==1){
                       alert('该订单已支付!',{icon:1});
                        window.location.href="/";
                    }else{
                       alert('该订单未支付!',{icon:2});
                        $('.mengcheng').hide();
                    }
                }
            })
        });
        $('.more1').click(function(){
            $('.box-content').css('height',"auto");
            $(this).hide();
            $('.more2').show();
        });
        $('.more2').click(function(){
            $('.box-content').css('height',"54px");
            $(this).hide();
            $('.more1').show();
        })

    })
</script>
<!--js脚本-->

</body>
</html>
