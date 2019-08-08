<?php
use yii\helpers\Url;
use common\controllers\PublicController;
use mobile\controllers\MemberController;
?>
<?php $this->beginBlock('header'); ?>
<style>
    .tn_con{display: flex;justify-content: space-around}
    .tuiguang_nav{height: 6rem}
    .curr-month{color: #fff;text-align: center;font-size: 15px}
</style>
<?php $this->endBlock(); ?>

<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
<!--nav-->
<div class="tuiguang_nav">
    <div class="tn_head">
        <a href="javascript:;">
            <i class="iconfont icon-zhanghu1"></i><?= $user_info->tel ?>
        </a>
        <a href="<?=Url::toRoute(['list/page','id'=>67])?>" class="fr">
            <i class="iconfont icon-changjianwenti"></i>常见问题
        </a>
    </div>
    <p class="curr-month">申请数据(人)</p>
    <div class="tn_con">
        <div class="num">
            <span>&nbsp;&nbsp;待核对&nbsp;&nbsp;</span><br/>
            <p class="mon_num"><?= $num ?></p>
        </div>
        <div class="num">
            <span>&nbsp;&nbsp;申请成功</span><br/>
            <p class="mon_num"><?= MemberController::getApplySuccessNum(Yii::$app->session['user_id'])?></p>
        </div>
        <div class="num">
            <span>下级申请成功</span><br/>
            <p class="mon_num"><?= MemberController::getNextApplySuccessNum(Yii::$app->session['user_id'])?></p>
        </div>
    </div>
</div>
<!--nav end-->

<!--main-->
<div class="kehu_nav">
    <div class="kehu_nav_left fl">
        <i class="iconfont icon-laba"></i>

    </div>
    <div class="kehu_nav_right fl">
        <span>产品上方的红色气泡，代表您推广该产品的人数。</span>
    </div>
    <div class="clear"></div>
</div>
<!--main end-->

<!--main list-->
<div class="reward_main" style="margin-bottom: 50px">
    <div class="reward_main_head">
        <i class="iconfont icon-daikuan"></i>
        <span>推广记录</span>
    </div>
    <div class="reward_main_con2">
        <ul id="data-list">
            <?php
            if(isset($loans)) {
                foreach ($loans as  $list): ?>
                    <li>
                        <a href="<?=Url::toRoute(['member/apply-rate','pid'=>$list['pid']])?>">
<!--                            <img class="lazyload" data-original="--><?//=$list['product']['logo'] ? : Url::base().'/mobile/web/images/img.png'?><!--" style="height: 2rem;width: 2rem"/><br/>-->
                            <img src="<?= Url::base().'/mobile/web/images/img.png' ?>" style="height: 2rem;width: 2rem"/><br/>
                            <span><?=PublicController::substr($list['product']['title'],3) ?></span>
                            <i><?=MemberController::getNum($list['pid'],$user_info->id)?>人</i>
                        </a>
                    </li>
                <?php endforeach;} ?>
        </ul>
        <div class="clear"></div>
    </div>
</div>
<!--main list end-->
<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
