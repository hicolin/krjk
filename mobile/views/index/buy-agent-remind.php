<?php
use yii\helpers\Url;
?>

<?php $this->beginBlock('header') ?>
<style>
    body{background-color: #fff;}
    .main{background-color: #fff;}
    .warn-title{padding-top: 1.6rem; display: flex;justify-content: center}
    .warn-title img{width: 3rem; height: 3rem}
    .description{margin-top: 1.2rem;text-align: center;font-size: .65rem;}
    .btn-box{text-align: center;display: flex;justify-content: center}
    .btn{display: block;background-color: rgb(59, 173, 255);;color: #fff;margin-top: 1rem;width: 25%;padding: .6rem; border-radius: 3px}
    .person_foot{display: none}
</style>
<?php $this->endBlock() ?>

<?= $this->render('@app/views/layouts/header') ?>

<div class="main">
    <div class="warn-title">
        <img src="<?= Url::base()?>/mobile/web/images/warning.png" alt="" align="center">
    </div>
    <p class="description">抱歉，普通会员不能查看分类大全，请升级会员！</p>
    <div class="btn-box">
        <a href="<?= Url::to(['list/buy-agent']) ?>" class="btn">购买会员</a>
    </div>
</div>