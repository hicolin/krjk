<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
<style>
    .apply_main_text{width: 76%;position: absolute;bottom: 25%;left: 12%;background: #fff;padding: 0.5rem;}
    .apply_main_text li{padding: 0.4rem 0;line-height: 1.2rem;border-bottom: 1px solid #eee;}
    .apply_main_text li i{font-size: 0.8rem;color: #bbb;}
    .apply_main_text li input{width: 50%;border: none;font-size: 0.6rem;color: #666;}
    .apply_main_text li input.send_code{width: 30%;color: #33aaff;font-size: 0.6rem;background: none;}
    .apply_main_text button{width: 100%;height: 1.4rem;border: none;background: #33aaff;font-size: 0.65rem;color: #fff;margin-top: 0.3rem;}
</style>
<style type="text/css">
    .person_foot{
        display: none;
    }
</style>
<!--top-->
<div class="poster_main" id="main">
    <img src="<?=$model->sub_pic?:Url::base().'/mobile/web/images/poster1.png'?>" class="bg" id="bg"/>
    <div class="apply_main_text">
        <form method="post" onsubmit="return apply_check()">
            <ul>
                <li>
                    <i class="iconfont icon-rengezhongxin"></i>
                    <input name="name" type="text" class="apply_name" placeholder="请输入姓名">
                </li>
                <li>
                    <i class="iconfont icon-shouji"></i>
                    <input name="tel" type="text" class="apply_tel" placeholder="请输入手机号">
                    <!--<input type="button" class="send_code" value="获取验证码" onclick="set_code(this)">-->
                </li>
              <!--  <li>
                    <i class="iconfont icon-yanzhengma"></i>
                    <input name="code" type="text" class="apply_yzm" placeholder="请输入验证码">
                </li>-->
                <input type="hidden" name="uid" value="<?=$uid?>">
                <input type="hidden" name="pid" value="<?=$model->id?>">
                <input type="hidden" name="sign"  value="<?=isset($sign)&&!empty($sign)?$sign:''?>">
            </ul>
            <button type="submit" onclick="layer.msg('正在提交，请稍后。。。', {time:2500,icon: 16,shade: 0.4})" >立即申请</button>
        </form>
    </div>
</div>

<script>
    //    适应不同屏幕
    /*window.onload=function(){
        document.documentElement.style.fontSize= document.documentElement.clientWidth/16+'px' ;
    };*/

    window.onload=function(){
        document.getElementById("main").style.height=document.getElementById("bg").offsetHeight+"px";
    }

    //    验证
    var countdown=60;
    function set_code(obj) {
        var str=$(".apply_tel").val();
        var reg=/^1(3|4|5|7|8)\d{9}$/;
        if(!reg.test(str)){
            layer.tips('请输入正确手机号码', ".apply_tel", {tips:3,time:2000});
            return false;
        }else if (countdown == 0){
            obj.removeAttribute("disabled");
            $(".send_code").val("重新获取)");
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

    function apply_name(){
        var str=$(".apply_name").val();
        //var reg=/^[\u4E00-\u9FA5]{2,10}$/;
        var reg=/^[\u4E00-\u9FA5A-Za-z]{2,10}$/;
        if(!reg.test(str)){
            layer.tips('请输入正确姓名', ".apply_name", {tips:3,time:2000});
            return false;
        }
        else{return true;}
    }


    function apply_tel(){
        var str=$(".apply_tel").val();
        var reg=/^1(3|4|5|7|8)\d{9}$/;
        if(!reg.test(str)){
            layer.tips('请输入正确手机号码', ".apply_tel", {tips:3,time:2000});
            return false;
        }
        else{return true;}
    }


    function apply_yzm(){
        var str=$(".apply_yzm").val();
        var reg=/^[\w]{6}$/g;
        if(!reg.test(str)){
            layer.tips('请输入正确的验证码', ".apply_yzm", {tips:3,time:2000});
            return false;
        }
        else{return true;}
    }
    function apply_check() {
        if(apply_name()&&apply_tel()){
            return true;
        }else{
            return false;
        }
    }


</script>
<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
