<?php $this->beginBlock('header'); ?>
<style>
    .info-main{padding: 10px;background-color: #fff}
    .list-item{display: flex;justify-content: space-between;height: 35px;border-bottom: 1px solid #eee;line-height: 35px}
    .list-item span:nth-child(1){font-weight: bold}
</style>
<?php $this->endBlock(); ?>

<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>

<!--main-->
<div class="info-main">
    <div class="list-item">
        <span>昵称</span>
        <span><?= $user_info['nickname']?></span>
    </div>
    <div class="list-item">
        <span>推荐人</span>
        <span><?= $pre_member['tel'] ? $pre_member['tel'].' | '.$pre_member['nickname']: '无'?></span>
    </div>
</div>
<!--main end-->
<?php $this->beginBlock('footer');?>
<?php $this->endBlock();?>
