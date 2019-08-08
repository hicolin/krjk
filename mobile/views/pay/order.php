<?php
use yii\helpers\Url;
?>
<?php

if(($order->add_time+60*15)<time()){
    ?>
    <script>
        alert('该订单已过期!');
        window.location.href="/";
    </script>
    <?php
}
?>

<html style="font-size: 64.6875px;"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>在线支付</title>
    <meta content="telephone=no" name="format-detection">
    <meta http-equiv="x-rim-auto-match" content="none">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">

    <link type="text/css" rel="stylesheet" href="<?=Url::base()?>/mobile/web/css/order.css">
    <link rel="stylesheet" href="<?=Url::base()?>/mobile/web/css/swiper.min.css">
    <script type="text/javascript" src="<?=Url::base()?>/mobile/web/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?=Url::base()?>/mobile/web/js/layout.js"></script>
    <!-- 头部公用JS end -->
</head>
<body>
<div class="header2">在线支付</div>
<div class="main" style="padding:0;overflow:hidden;">
    <div style="padding:0.2rem;">
        <div class="time">
            <p>支付剩余时间</p>
            <div class="timenum" id="countdown"></div>
            <div class="" style="font-size:12px;margin-top:25px;">易起名订单（单号：<?=$order->order_sn?>）<font style="color:#b1b1b1;"></font>
                <font style="color:#ff6713;" id="amount_id">￥<?=$order->order_price?></font>
            </div>
        </div>
    </div>
    <div style="padding:0 0.3rem;font-size:12px;width:100%;height:30px;line-height:33px;background:#f5f5f5;color:#5b5b5b;">选择支付方式</div>
    <div style="padding:0.2rem;">
        <div class="btnbox">

            <div class="btn3" data-type="2" data-text="12"><i><img src="<?=Url::base()?>/mobile/web/img/z1.png"></i>支付宝  <em class="on"></em></div>
            <div class="btn3" data-type="1" data-text="1301"><i><img src="<?=Url::base()?>/mobile/web/img/z3.png"></i>微信支付  <em></em></div>
            <input type="hidden" id="type" value="2">
            <input type="hidden" id="add_time" value="<?=$order->add_time?>">
        </div>
    </div>
</div>
<input type="hidden" id="order_id" value="<?=$order->order_id?>">
<div  class="footer3" onclick="pay_order()"  >确认支付￥<?=$order->order_price?></div>
<div class="tz_load">
    <div class="payLoad"></div>
    <div class="loadTip">跳转支付链接中，请稍后</div>
</div>
<!--js脚本-->
<script type="text/javascript">
    setCountdown(15,"#countdown");
    $(function(){
        $(".btn3").click(function(){
            $(".btnbox em").removeClass("on");
            $(this).find("em").addClass("on");
            $('#type').val($(this).attr('data-type'));
            setCountdown(15,"#countdown");
        });
    });
    function setCountdown(t,obj){
        if((new RegExp(/^[1-9]\d*$/g)).test(t)){
            t = t- 1 ;
            seconds = 59 ;
        }else{
            //console.log(t);
            $t_arr = t.split(":");
            t = parseInt($t_arr[0]) ;
            seconds = parseInt( $t_arr[1] );
        }
        if(t>0 ||( t==0&&seconds>0) ){
            var time = "";
            if(seconds > 0){
                seconds-- ;
                seconds = seconds.toString().length==1?"0"+seconds:seconds;
                t = t.toString().length==1?"0"+t:t;
                time = t+":"+seconds ;
            }else{
                t-- ;
                t = t.toString().length==1?"0"+t:t;
                time = t+":59" ;
            }
            $(obj).html(time);
            var num = setTimeout(function(){setCountdown(time,obj)},1000);
            $("div").data("wap_pay_countdown",num);
        }else{
            clearTimeout($("div").data("wap_pay_countdown"));
        }
    }
</script>
<script>
    function pay_order(){
        var order_id=$('#order_id').val();
        var timestamp = $.now();
        var time=$('#add_time').val();
        if((parseInt(time)+parseInt(15*60))<parseInt(timestamp/1000)){
            alert('该订单已过期!');
            window.location.href="/";
        }else if($('#type').val()==1){
            alert('暂未开通微信支付！')
        }else if($('#type').val()==2){
            window.location.href="<?=Url::toRoute(['pay/alipay'])?>&order_id="+order_id
        }

    };
</script>

</body></html>
