
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<?php $this->beginContent('@app/views/layouts/header.php') ?>
<?php $this->endContent(); ?>
<style>
    .aem_img p {
        color: #333;
        width: 100%;
    }

    .aem_img p img {
        width: 100%;
    }
</style>
<!--main-->
<div class="agent_ewm_main">
    <div class="aem_img" style="background: #f3f3f3">
        <?= $model->detail ?>
    </div>
</div>
<!--main end-->
<script>
    $(function () {
        var width = document.body.clientWidth;
        $('.aem_img img').style.width = width + 'px'
    })
</script>
<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
