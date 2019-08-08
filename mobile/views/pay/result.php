<?php
use yii\helpers\Url;
?>
<?php $this->beginBlock('header'); ?>
<?php $this->endBlock(); ?>

<h3 style="padding: 30px;font-weight: bold;letter-spacing: 2px">支付成功</h3>

<?php $this->beginBlock('footer'); ?>
<script>
    // 自动跳转
    setTimeout(function () {
        location.href = '<?= Url::to(['member/index'])?>'
    },2000)
</script>
<?php $this->endBlock(); ?>
