<?php
use yii\helpers\Url;
use common\controllers\PublicController;

// echo $minmoney;exit;
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
<!--main-->

<div class="go_tx_main">
    <p>提现金额</p>
    <h1>
        <i class="iconfont icon-2"></i>
        <input name="money" type="number" class="tx_num">
    </h1>
    <p><i style="color: red">*</i> 最低提现金额为<i><?=$minmoney?:'0.00'?></i>元</p>
    <p><i style="color: red">*</i> 提现时间： <i></i>09:00--18:00</p>
</div>

<div class="tx_btn">
    <button type="button" onclick="tx_check()">确认提现</button>
</div>
<!--main end-->
<script src="<?= Url::base() ?>/mobile/web/js/LAreaData1.js" ></script>
<script src="<?= Url::base() ?>/mobile/web/js/LAreaData2.js"></script>
<script src="<?= Url::base() ?>/mobile/web/js/LArea.js"></script>
<script>
    //提现验证
    function tx_check(){
        var account_number = "<?=$user_info->account_number?>";
        var account_name = "<?=$user_info->account_name?>";
        var pay_code = "<?= $user_info->pay_code?>";
        var minmoney = "<?=$minmoney?>";
        if(!account_number || !account_name || !pay_code) {
            layer.confirm('您提现账户信息未完善，马上完善？',{
                btn: ['确定','取消'] //按钮
            }, function(){
                window.location.href="<?=Url::toRoute(['member/change-account'])?>";
            }, function(){
                layer.close();
            });
            return false;
        }
        var str=$(".tx_num").val();
        if(!str) {
            layer.tips('请输入提现金额', ".tx_num", {tips:3,time:2000});
            return false;
        }else if(isNaN(str)) {
            layer.tips('请输入正确的金额', ".tx_num", {tips:3,time:2000});
            return false;
        }else if(str <= 0) {
            layer.tips('提现金额必须大于0', ".tx_num", {tips:3,time:2000});
            return false;
        }
        else if(!time_range('09:00','18:00')) {
            layer.msg('提现时间为: 09:00--18:00');
            return false;
        }
        else{
            layer.load(1);
            $.ajax({
                url  : "<?= Url::toRoute(['member/withdraw'])?>",
                type : 'post',
                data : {'money':str},
                dataType:'text',
                success:function(data){
                    layer.closeAll();
                    if(data) {
                        if(data==200) {
                            layer.msg('最低提现额度为'+minmoney+'元',{icon:2,time:2000});
                            return false;
                        }else if(data==300) {
                            layer.msg('提现的金额大于可提现金额',{icon:2,time:2000});
                            return false;
                        }else if(data==304) {
                            layer.confirm('您提现账户信息未完善，马上完善？',{
                                btn: ['确定','取消'] //按钮
                            }, function(){
                                window.location.href="<?=Url::toRoute(['member/change-account'])?>";
                            }, function(){
                                layer.close();
                            });
                            return false;
                        }else if(data==400){
                            layer.msg('申请失败，请稍后再试',{icon:2,time:2000});
                            return false;
                        }else {
                            layer.confirm('提现成功，请等待管理员审核', {
                                btn: ['确定'] //按钮
                            }, function(){
                                window.location.href="<?=Url::toRoute('member/account')?>";
                            }, function(e){
                                layer.close(e);
                                return false;
                            });
                        }
                    }
                }
            });
        }
    }

    // 时间段验证
    var time_range = function (beginTime, endTime) {
        var strb = beginTime.split (":");
        if (strb.length != 2) {
            return false;
        }

        var stre = endTime.split (":");
        if (stre.length != 2) {
            return false;
        }

        var b = new Date ();
        var e = new Date ();
        var n = new Date ();

        b.setHours (strb[0]);
        b.setMinutes (strb[1]);
        e.setHours (stre[0]);
        e.setMinutes (stre[1]);

        if (n.getTime () - b.getTime () > 0 && n.getTime () - e.getTime () < 0) {
            return true;
        } else {
            return false;
        }
    }
</script>

<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
