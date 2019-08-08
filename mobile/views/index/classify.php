<?php
/* @var $this \yii\web\View */
?>
<?= $this->render('@app/views/layouts/header.php') ?>

<style>
    .wrap , .container{height: 100%;}
    .thirdPageBox{width: 100%;min-height: 100%;}
    .thirdPageBox iframe{position: absolute;left: 0;top:2rem;width: 100%;height: 100%;}
    .person_foot{display: none;}
</style>
<div class="thirdPageBox">
    <iframe  src="<?= \common\controllers\PublicController::getSysInfo(35) ?>" scrolling="no">
    </iframe>
</div>

