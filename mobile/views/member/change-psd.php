<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
<div class="tel_main">

    <div class="tel_m_con">
        <div class="tel_main_con">
            <ul>
                <li>
                    <i class="iconfont icon-yanzhengma"></i>
                    <input name="code" class="revise_tel" type="text" placeholder="请填写验证码">
                    <input type="button" value="获取验证码" class="rev_get send_code fr" onclick="sms(this)">
                </li>
                <li>
                    <i class="iconfont icon-weibiaoti-"></i>
                    <input name="psd1" class="revise_psd" type="password" placeholder="请输入密码">
                </li>
                <li>
                    <i class="iconfont icon-weibiaoti-"></i>
                    <input name="psd2" class="revise_psd2" type="password" placeholder="请确认密码">
                </li>
            </ul>
        </div>
        <div class="tel_main_text">
            <span>验证码将发送至<i><?=$user_info->tel?></i>，请注意查收。</span>
        </div>
        <div class="tel_main_bot">
            <button type="button" onclick="psd_check()">保存</button>
        </div>
    </div>
</div>
<!--main end-->
<script>
    //适应不同屏幕
   /* window.onload=function(){
        document.documentElement.style.fontSize= document.documentElement.clientWidth/16+'px' ;
    };*/

    function sms(obj) {
        var tel = "<?=$tel?>";
        //alert(tel);
        $.ajax({
            url : '<?=Url::toRoute('member/sms')?>',
            type : 'get',
            data : {'tel':tel},
            dataType:'json',
            success:function(res){
                if(res.status === 200) {
                    layer.tips('发送成功', ".revise_tel", {tips:3,time:2000});
                    set_code(obj)
                }else{
                    layer.tips('发送失败', ".revise_tel", {tips:3,time:2000});
                }
            }
        })
    }
    //验证
    var countdown=60;
    function set_code(obj) {
        if (countdown == 0){
            obj.removeAttribute("disabled");
            $(".send_code").val("重新获取");
            countdown = 60;
            return;
        } else {
            obj.setAttribute("disabled", true);
            $(".send_code").val("重新发送(" + countdown + ")");
            countdown--;
        }
        setTimeout(function() {set_code(obj)},1000)
    }

    function revise_tel(){
        var str=$(".revise_tel").val();
        var reg=/^[\w]{6}$/g;
        if(!reg.test(str)){
            layer.tips('请填写验证码', ".revise_tel", {tips:3,time:2000});
            return false;
        }
        else{
            $('.tel_m_con').eq(1).removeClass('hiddendiv').siblings(".tel_m_con").addClass("hiddendiv");
            return false;
        }
    }
    function apply_check() {
        var str=$(".revise_tel").val();
        var reg=/^[\w]{6}$/g;
        if(!reg.test(str)){
            layer.tips('请填写验证码', ".revise_tel", {tips:3,time:2000});
            return false;
        }else{
            return true;
        }
    }
    function revise_psd(){
        var str=$(".revise_psd").val();
        var reg=/^[\w]{2,20}$/g;
        if(!reg.test(str)){
            layer.tips('请填写验证码', ".revise_psd", {tips:3,time:2000});
            return false;
        }
        else{return true;}
    }
    function revise_psd2(){
        var psd1 = $(".revise_psd").val();
        var psd2 = $(".revise_psd2").val();
        if(psd1 != psd2){
            layer.tips('两次密码不一样', ".revise_psd2", {tips:3,time:2000});
            return false;
        }
        else{return true;}
    }
    function psd_check() {
        var code=$(".revise_tel").val();
        var psd1 = $(".revise_psd").val();
        var psd2 = $(".revise_psd2").val();
        if(apply_check()&&revise_psd()&&revise_psd2()){
            layer.load(2);
            $.ajax({
                url : '<?=Url::toRoute('member/change-psd')?>',
                type : 'post',
                data : {'code':code, 'psd1':psd1, 'psd2':psd2},
                dataType:'text',
                error: function (xmlHttpRequest, textStatus, errorThrown) {
                    layer.closeAll();
                    layer.msg('系统出错！',{icon:5})
                },
                success:function(data){
                    layer.closeAll();
                    if(data==101) {
                        layer.tips('两次密码不一样', ".revise_psd2", {tips:3,time:2000});
                        return false;
                    }else if(data==300) {
                        layer.tips('验证码不正确', ".revise_tel", {tips:3,time:2000});
                        return false;
                    }else if(data==500) {
                        layer.closeAll();
                        layer.msg('系统出错！',{icon:5})
                    }else if(data==100) {
                        layer.msg('修改成功',{icon:1,time:2000},function () {
                            window.location.reload();
                        });
                    }
                }
            })
        }else{
            return false;
        }
    }

</script>
<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>  
