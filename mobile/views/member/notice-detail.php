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
<div class="kouzi_main" style="padding: 0 15px;">
    <h1 style="font-size: 0.8rem;color: #000;text-align: center;padding-top: 10px;"><?=$model->title?></h1>
    <div style="padding: 10px  5px;font-size: 0.5rem">
        <span style="color: #888;"><?=date('Y-m-d',$model->create_time)?></span> 
    </div>
    <div class="pcontent" style="font-size:0.6rem;min-height:400px;line-height: 25px">
        <?=$model->content?>
    </div>
   
</div>
<!--main end-->

<?php $this->beginBlock('footer');?>
<?php $this->endBlock(); ?>
