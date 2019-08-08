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
    
<!--menu-->

<!--menu end-->
<div class="card_main">
    <div class="card_main_top">
        <a href="<?=Url::toRoute(['member/index'])?>"><img src="<?=PublicController::getSysInfo(21)?:Url::base().'/mobile/web/images/banner3.png'?>"/></a>
    </div>

    <div class="card_main_list">
        <ul>
            <?php foreach ($handcard as $key => $value) { ?>
            <li>
                <a href="<?= $value->links ?>" id="card-<?=$value->id?>">
                    <img src="<?=$value->img?>"/>
                    <h1><?=$value->name?></h1>
                    <h2><?=$value->remark?></h2>
                </a>
            </li>
             <?php }  ?>
            <div class="clear"></div>
        </ul>
    </div>

</div>
<!--main end-->

 <style type="text/css">
    .person_foot{
        display: none;
    }
</style>
<!--foot-->
<div class="card_foot">
    <ul>
        <li class="curr">
            <a href="javascript:;">
                <i class="iconfont icon-banlishuiqia"></i>
                <span>快速办卡</span>
            </a>
        </li>
        <li>
            <a href="<?=Url::toRoute('list/card-rate')?>">
                <i class="iconfont icon-xinyongqia"></i>
                <span>办卡进度</span>
            </a>
        </li>
        <div class="clear"></div>
    </ul>
</div>
<div class="clear"></div>
<!--foot end-->

<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
