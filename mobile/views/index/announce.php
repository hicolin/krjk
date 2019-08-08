<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<style>
    .notice-cate{display: flex;justify-content: space-around;;font-size: 16px;border-bottom: 1px dotted #ccc;text-align: center;height: 2rem;}
    .notice-cate a{display: inline-block;width: 50%;height: 2rem;line-height: 2rem}
    .notice-active{color:rgb(71, 178, 255);border-bottom: 1px solid rgb(95, 183, 255)}
</style>
<?php $this->endBlock(); ?>

<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
<!--main-->
<div class="jindu_main mt10">
    <div class="notice-cate">
        <a href="<?= Url::toRoute(['index/announce'])?>" class="notice-active"><span>公告</span></a>
        <a href="javascript:viewNotice()"><span>私信</span></a>
    </div>
    <?php
    if($info) { ?>
        <div class="nkm_con_list ">
            <ul>
                <?php
                foreach ($info as $arr) {
                    $url = Yii::$app->urlManager->createAbsoluteUrl(['index/announce-detail','id'=>$arr->art_id]);
                    ?>
                    <li>
                        <a href="<?=$url?>"> 
                            <p><?=PublicController::substr($arr->title, 40)?></p>
                            <span style="float: right"><?=date('Y-m-d H:i',$arr->create_time)?></span>
                            <div class="clear"></div>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    <?php }else{ ?>
        <div class="jm_con">
            <img src="<?= Url::base() ?>/mobile/web/images/nosear.png" class="nothing"/><br/>
            <em>没有内容</em>
        </div>
    <?php }
    ?>
</div>
<div style="height: 100px"></div>

<!--main end-->
<?php $this->beginBlock('footer'); ?>
<script>
    // 查看私信
    function viewNotice() {
       var user_id = '<?= Yii::$app->session['user_id']?>';
       if(!user_id){
           layer.msg('请登录后查看');
           return false;
       }
       location.href = '<?= Url::toRoute(['member/notice'])?>';
    }
</script>
<?php $this->endBlock(); ?>
