<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
<style>
    .tip{margin: 15px auto 10px;text-align: center;padding: 5px;color: red}
    .down-box{background-color: #fff;padding: 15px;border-radius: 5px;display: flex;justify-content: space-between}
    .android{text-align: center;width: 50%}
    .android-right{border-right: 1px solid rgb(247, 247, 247)}
    .android a span{background-color: rgb(254, 78, 78);display: inline-block;width: 60%;margin: 10px 0 5px;border-radius: 3px;color:white;height: 20px;line-height: 20px}
</style>
<div class="content_w">
    <p class="tip">tip: 若微信中无法下载，请点击右上角，选择在浏览器打开</p>
    <div class="down-box">
        <div class="android android-right">
            <img src="<?= Url::base()?>/mobile/web/images/android.png" style="width: 80px"><br>
            <a href="<?= Url::base()?>/mobile/web/app/android.apk"><span>立即下载</span></a>
        </div>
        <div class="android">
            <img src="http://qr.liantu.com/api.php?text=<?= Yii::$app->request->hostInfo?>/mobile/web/app/android.apk&amp;w=200&amp;m=0" style="width: 50%"><br>
            <a href="javascript:;"><span style="background-color: rgb(247, 247, 247);color: #666">扫码下载</span></a>
        </div>
    </div>
    <div class="down-box" style="margin-top: 8px">
        <div class="android android-right">
            <img src="<?= Url::base()?>/mobile/web/images/ios.png" style="width: 80px"><br>
            <a href="<?= Url::base()?>/mobile/web/app/ios.ipa"><span>立即下载</span></a>
        </div>
        <div class="android">
            <img src="http://qr.liantu.com/api.php?text=<?= Yii::$app->request->hostInfo?>/mobile/web/app/ios.ipa&amp;w=200&amp;m=0" style="width: 50%"><br>
            <a href="javascript:;"><span style="background-color: rgb(247, 247, 247);color: #666">扫码下载</span></a>
        </div>
    </div>
</div>

<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
