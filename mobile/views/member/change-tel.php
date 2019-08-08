<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
<!--main-->
<div class="tel_main">
    <div class="tel_main_con">
        <ul>
            <li>
                <i class="iconfont icon-shouji"></i>
                <input class="revise_tel" name="tel" type="text" placeholder="请填写您的手机号码">
                <input type="button" value="获取验证码" class="rev_get send_code fr" onclick="sms(this)">
            </li>
            <li>
                <i class="iconfont icon-yanzhengma"></i>
                <input name="code" class="revise_yzm" type="text" placeholder="请填写验证码">
            </li>
        </ul>
    </div>
    <div class="tel_main_bot">
        <button type="button" onclick="apply_check()">保存</button>
    </div>
</div>
<!--main end-->
<script>
    //    适应不同屏幕
    /*window.onload=function(){
        document.documentElement.style.fontSize= document.documentElement.clientWidth/16+'px' ;
    };*/
    function sms(obj) {
        var str=$(".revise_tel").val();
        var reg=/^1(3|4|5|7|8)\d{9}$/;
        if(!reg.test(str)){
            layer.tips('请输入正确的手机号', ".revise_tel", {tips:3,time:2000});
            //layer.msg('请输入正确的手机号',{icon:1,time:2000})
            return false;
        }
        $.ajax({
            url : '<?=Url::toRoute('member/sms')?>',
            type : 'get',
            data : {'tel':str},
            dataType:'text',
            success:function(data){
                if(data==0) {
                    layer.tips('发送成功', ".revise_tel", {tips:3,time:2000});
                    set_code(obj)
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
        setTimeout(function() {
                set_code(obj) }
            ,1000)
    }
    function revise_tel(){
        var str=$(".revise_tel").val();
        var reg=/^1(3|4|5|7|8)\d{9}$/;
        if(!reg.test(str)){
            layer.tips('请输入您的手机号', ".revise_tel", {tips:3,time:2000});
            return false;
        }
        else{return true;}
    }

    function revise_yzm(){
        var str=$(".revise_yzm").val();
        var reg=/^[\w]{6}$/g;
        if(!reg.test(str)){
            layer.tips('输入正确的验证码', ".revise_yzm", {tips:3,time:2000});
            return false;
        }
        else{return true;}
    }

    function apply_check() {
        var tel=$(".revise_tel").val();
        var code=$(".revise_yzm").val();
        if(revise_tel()&&revise_yzm()){
            layer.load(2);
            $.ajax({
                url : '<?=Url::toRoute('member/change-tel')?>',
                type : 'post',
                data : {'tel':tel, 'code':code},
                dataType:'text',
                error: function (xmlHttpRequest, textStatus, errorThrown) {
                    layer.closeAll();
                    layer.msg('系统出错！',{icon:5})
                },
                success:function(data){
                    layer.closeAll();
                    if(data==200) {
                        layer.tips('不是当前手机号', ".revise_tel", {tips:3,time:2000});
                        return false;
                    }else if(data==300) {
                        layer.tips('验证码不正确', ".revise_yzm", {tips:3,time:2000});
                        return false;
                    }else if(data==500) {
                        layer.closeAll();
                        layer.msg('系统出错！',{icon:5})
                    }else if(data==100) {
                        layer.msg('修改成功',{icon:1,time:2000},function () {
                            window.location.reload();
                        });
                    }else if(data==600){

                         layer.msg('手机号码已经存在，无需更换',{icon:5,time:2000},function () {
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
