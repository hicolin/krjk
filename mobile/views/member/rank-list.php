<?php
use yii\helpers\Url;
?>
<?php $this->beginBlock('header'); ?>
<style>
    .rank-box{padding: 10px 10px 5px 10px;background-color: #fff}
    .rank-item{display: flex;justify-content: flex-start;padding: 12px 0 }
    .rank-item .item-title{width: 10%}
    .item-body a{padding: 3px;border-radius: 3px;margin-left: 12px;color: #aaa}
    .item-body .active{border: 1px solid rgb(255, 95, 0);color: rgb(255, 95, 0)}
    .text-main{rgb(255, 95, 0)}
</style>
<?php $this->endBlock(); ?>

<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>

<div class="rank-box">
    <div class="rank-item">
        <div class="item-title">周期</div>
        <div class="item-body period">
            <a href="<?= Url::to(['member/rank-list','period'=>1,'type'=>$type])?>">总排行</a>
            <a href="<?= Url::to(['member/rank-list','period'=>2,'type'=>$type])?>">月排行</a>
            <a href="<?= Url::to(['member/rank-list','period'=>3,'type'=>$type])?>">周排行</a>
        </div>
    </div>
    <div class="rank-item">
        <div class="item-title">类型</div>
        <div class="item-body type">
            <a href="<?= Url::to(['member/rank-list','period'=>$period,'type'=>1])?>">佣金</a>
            <a href="<?= Url::to(['member/rank-list','period'=>$period,'type'=>2])?>">代理</a>
        </div>
    </div>
</div>

<!--main-->
<div class="rank_main">
    <ul>
        <?php
        foreach ($info as $key => $list) {
            if($key < 3) {
                $str = '<img src="'.Url::base().'/mobile/web/images/no'.($key+1).'.png">';
            }else{
                $str = '<span>'.($key+1).'</span>';
            }
            ?>
            <li>
                <div class="rm_left fl">
                    <?=$str?>
                </div>
                <div class="rm_mid fl">
                    <p>
                        <img src="<?=$list['pic']?:Url::base().'/mobile/web/images/tx4.png'?>"/><?=$list['nickname']?>
                    </p>
                </div>
                <div class="rm_right fr">
                    <i><?= $list['tip']?></i>
                </div>
                <div class="clear"></div>
            </li>
        <?php }
        ?>
    </ul>

</div>
<!--main end-->

<?php $this->beginBlock('footer'); ?>
<script>
    var period = <?= $period?>;
    var type = <?= $type?>;
    // 选项高亮
    $('.period a').eq(period-1).addClass('active');
    $('.type a').eq(type-1).addClass('active');
</script>
<?php $this->endBlock(); ?>
