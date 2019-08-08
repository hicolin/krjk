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
.jm_con li img {
    display: block;
    width: 100%;
    margin: auto;
}

     /*找客服*/
 .kf_main{background: url("/mobile/web/images/img/bg_kf01.png") no-repeat;background-size: 100% 100%;padding: 4rem 1.6rem;height: 100%;}
.kf_main_center{background: url("/mobile/web/images/img/bg_kf02.png") no-repeat;background-size: 100% 100%;padding:  2rem 0rem;text-align: center;margin-bottom: 1rem;}
.kf_main_center .card_proce img{width: 7rem;height: 7rem;}
.kf_main_center p{font-size: 0.68rem;color: #40445c;margin-bottom: 0.8rem;}
.kf_main_center p span{color: #50a0fb;}
.kf_main_center div{display: flex;align-items: center;justify-content: center;margin-bottom: 1.4rem;}
.kf_main_center div img{width: 1rem;}
.kf_main_center div span{font-size: 0.68rem;color: #000;margin: 0 0.1rem;}
.kf_main_center div a{display: inline-block;padding: 0.4rem;border: 1px solid #df2a10;border-radius: 6px;font-size: 0.6rem;color: #df2a10;margin-left: 0.2rem;}
.kf_line{position: absolute;left: -0.4rem;bottom: 1.7rem;width: 1rem;}
.kf_main_div01{width: 12rem;font-size: 0.8rem;color: #50a0fb;text-align: center;background: #fff;margin: 0 auto;border-radius: 4px;line-height: 2rem;position: relative;}

</style>

<div class="kf_main" style="padding-top: 2em">
    <div class="kf_main_center">
        <p>有问题就找我，<span> 微信客服(1) </span>给您解决</p>
        <a class="card_proce">
            <img src="<?=PublicController::getSysInfo(27)?:Url::base().'/mobile/web/images/banner3.png'?>">
        </a>
    </div>

    <div class="kf_main_div01">
        <img src="/mobile/web/images/img/img_line01.png" class="kf_line">
        长按识别二维码联系微信客服
    </div>

    <div class="kf_main_center" style="margin-top: 30px">
        <p>有问题就找我，<span> 微信客服(2) </span>给您解决</p>
        <a class="card_proce">
            <img src="<?=PublicController::getSysInfo(28)?:Url::base().'/mobile/web/images/banner3.png'?>">
        </a>
    </div>

    <div class="kf_main_div01">
        <img src="/mobile/web/images/img/img_line01.png" class="kf_line">
        长按识别二维码联系微信客服
    </div>

</div>



<script>
    $(function(){
        var width =  document.body.clientWidth;
        $('.aem_img img').style.width = width+'px'
    })

    //    适应不同屏幕
    /*window.onload=function(){
        document.documentElement.style.fontSize= document.documentElement.clientWidth/16+'px' ;
    };*/
</script>
<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
