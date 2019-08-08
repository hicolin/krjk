<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
<style type="text/css">
.buy_foot a {
    display: block;
    width: 100%;
    height: 2rem;
    background: #4199fb;
    text-align: center;
    line-height: 2rem;
    border-radius: 0.2rem;
    color: #fff;
    font-size: 0.7rem;
}
.buy_foot a {
    line-height: 2rem;
    height: auto;
}
</style>
<!--main-->
<div class="kouzi_main" style="padding: 0 15px;">
    <!--<h1 style="font-size: 0.8rem;color: #000;text-align: center;padding-top: 10px;"></h1>-->
    <!--<div style="padding: 10px  5px;font-size: 0.5rem">-->
       <!-- <span style="color: #888;">aaas</span>
        <span style="margin-left: 20px;">文章来源：<i style="color: #00A9EF">we32323233</i></span>
        &nbsp&nbsp
        <a href="<?/*=Url::toRoute(['sub-comment','grade_id'=>$model->grade_id])*/?>" style="font-size: 18px;color: rgb(230,188,90);">
            立即评价
        </a>
         &nbsp&nbsp
        <a href="<?/*=Url::toRoute(['comment','grade_id'=>$model->grade_id])*/?>" style="font-size: 18px;color: #4199fb;">
            评价预览
        </a>-->
    <!--</div>-->
    <?=$model->detail?>
</div>

<?php if (!strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')) { ?>
    <div class="buy_foot" style="height: 5rem;">
        <?php
        if($user_info->grade==3) { ?>
            <a href="<?=Url::toRoute('sub-comment')?>">
                立即评价
            </a>
        <?php }else{ ?>
            <a href="<?=Url::toRoute('sub-data')?>">
                立即购买
            </a>
        <?php }
        ?>
    </div>
    <?php }else{?>

    <div class="buy_foot" style="height: 5rem;">
        <?php
        if($user_info->grade==3) { ?>
            <a href="<?=Url::toRoute('sub-comment')?>">
                立即评价
            </a>
        <?php }else{ ?>
            <a href="<?=Url::toRoute('sub-data')?>">
                立即购买
            </a>
        <?php }
        ?>

    <?php }  ?>

<!--main end-->
<script>
    //    适应不同屏幕
    /*window.onload=function(){
        document.documentElement.style.fontSize= document.documentElement.clientWidth/16+'px' ;
    };*/
</script>

<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>