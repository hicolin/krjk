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
<div class="agent_ewm_main">
    <div class="aem_img">
        <img src="<?= Url::base() ?>/mobile/web/images/guide.png"/>
    </div>
</div>
<!--main end-->
<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
