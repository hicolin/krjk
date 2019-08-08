<?php $this->beginBlock('header'); ?>
<?php $this->endBlock(); ?>

<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>

<!--main-->
<div class="kouzi_main" style="padding: 0 15px;">
    <h1 style="font-size: 0.8rem;color: #000;text-align: center;padding-top: 10px;"><?=$model->title?></h1>
    <div style="padding: 10px  5px;font-size: 0.5rem">
        <span style="color: #888;"><?=date('Y-m-d',$model->create_time)?></span>
        <span style="margin-left: 20px;">文章来源：<i style="color: #00A9EF"><?=$model->source?></i></span>
    </div>
   <?=$model->detail?>
</div>
<!--main end-->
