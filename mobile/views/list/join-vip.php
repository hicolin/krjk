<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<style>
    /*隐藏轮播图小圆点*/
    .slideBox .hd{display: none}
</style>
<?php $this->endBlock(); ?>
 <script src="<?= Url::base() ?>/mobile/web/vipjs/jquery-1.8.3.min.js"></script>
 <script src="<?= Url::base() ?>/mobile/web/vipjs/mobile/layer.js"></script>
<body class="join_body">

<div class="content_w">
    <!--选择开通会员等级-->
    <div class="join_VIP_main1">
        <div id="slideBox" class="slideBox">
            <div class="bd">
                <ul>
                    <?php foreach ($model as $key => $value): ?>
                        <li>
                            <div class="jVm1_con">
                                <div class="jVm1_con_head">
                                    <span>会员购买</span>
                                </div>
                                <a style="display: block;" href="<?=Url::toRoute(['list/vip-details','id'=>$value->id])?>">
                                <div class="jVm1_con_main">
                                    <div class="jVm1_con_main_bg">
                                        <img src="<?=$value->pic?>"/>
                                    </div>
                                    <div class="jVm1_con_main_son">
                                        <div class="jVm1_con_main_son_bg">
                                            <img src="<?=$value->bei_pic?>"/>
                                        </div>
                                        <div class="jVm1cms_text">
                                            <h1><?=$value->title?></h1>
                                            <h2>
                                                <span><?=$value->price?></span>元
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                                </a>
                                <div class="jVm1_con_foot">
                                    <a href="<?=Url::toRoute(['list/vip-details','id'=>$value->id])?>">立即购买<i class="iconfont icon-arrow-right"></i></a>
                                </div>
                            </div>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
            <div class="hd">
                <ul></ul>
            </div>
        </div>
        <script src="<?= Url::base() ?>/mobile/web/vipjs/TouchSlide.1.1.js"></script>
        <!--<script type="text/javascript">
            TouchSlide({
                slideCell:"#slideBox",
                titCell:".hd ul",
                mainCell:".bd ul",
                effect:"leftLoop",
                autoPage:false,
                autoPlay:false,
            });
        </script>-->
        <div class="vip-img">
            <img src="<?= PublicController::getSysInfo(31)?>" alt="" style="width: 100%">
        </div>
    </div>
    <!--选择开通会员等级 end-->
</div>

</body>

<?php $this->beginBlock('footer');?>
<?php $this->endBlock(); ?>
