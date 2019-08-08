<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<style>
    .person_foot{display: none}
    .cat-title{margin-top: 5px;background-color: #fff;display: flex;justify-content: space-around;border-bottom: 1px solid #eee;}
    .cat-title span{display: inline-block;height: 100%;padding: 12px}
    .cat-title .active{color:#33aaff;border-bottom: 2px solid #33aaff}
    .user-comment{display: flex;background-color: #fff;padding: 10px;line-height: 25px;border-bottom: 1px solid #eee;margin-top: 1px}
    .user-comment .avatar{width: 15%}
    .user-comment .avatar img{width: 40px;height: 40px;border-radius: 50%}
    .user-comment .comment{width: 85%}
    .comment span:nth-child(1){font-weight: bold}
    .comment span:nth-child(2){float: right;color:#999}
    .comment-apply{background-color: #fff;padding-bottom: 20px}
    .comment-apply .more-comment{display: block;text-align: center;padding-top: 20px}
    .comment-apply .comment-button{display: block;text-align: center;padding: 12px 0;background-color:#33aaff;margin-top: 20px;font-size: 16px;color:#fff;width: 80%;margin-left: 10%;border-radius: 2px}

    .buy_foot01_div{position: fixed;bottom: 0;background-color: #fff;width: 100%;height: 70px}
    .buy_foot01_div>a{display: inline-block;background-color: #3af;width: 30%;text-align: center;padding: 10px;height: 70px;color:#fff;font-size: 16px;line-height: 50px}
    .buy_foot01_div>div>p:nth-child(1){font-size: 0.8rem;color: #000;margin-bottom: 0.6rem;}
    .buy_foot01_div{display: flex;justify-content: space-between;align-items: center;}
    .buy_foot01_div>p{font-size: 0.7rem;color: #999;}
    .buy_foot01_div>div>p:nth-child(1) span{color: #f5583d;}
    .buy_foot01_div>div>p:nth-child(1) span i{font-size: 1rem;}
    .buy_foot01_div .buy_foot01_agree{display: flex;align-items: center;font-size: 0.6rem;color: #999;}
    .buy_foot01_div .buy_foot01_agree img{width: 0.8rem;height: 0.8rem;margin-right: 0.28rem;}
    .buy_foot01_div .buy_foot01_agree i{color: #4199fb;}
</style>
<?php $this->endBlock(); ?>

<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>

<div class="cat-title">
    <span class="cat-title-detail active">产品详情</span>
    <span class="cat-title-comment">用户评价</span>
</div>

<div class="product-img" style="margin-top:1px">
    <img src="<?= $poster?>" alt="" style="width: 100%">
</div>

<div class="comment-list" style="display: none">
    <?php foreach ($comments as $comment):?>
        <div class="user-comment">
            <div class="avatar">
                <img src="<?=$comment->member['pic'] ? : Url::base().'/mobile/web/images/tx2.png'?>" alt="">
            </div>
            <div class="comment">
                <span><?=$comment->member['nickname'] ? : 'null'?></span><span><?=date('Y-m-d',$comment->create_time)?></span>
                <p><?=PublicController::subStr($comment->content,40)?></p>
            </div>
        </div>
    <?php endforeach;?>
    <div class="comment-apply">
        <a href="<?= Url::to(['list/comment'])?>" class="more-comment">更多用户评价 >></a>
        <a href="<?= Url::to(['list/sub-comment'])?>" class="comment-button">立即评价</a>
    </div>
</div>

<div class="buy_foot01_div">
    <div style="padding: 10px">
        <p><?= $webTitle?>金牌会员<span>￥<i id="price"><?= $model['price']?></i></span></p>
        <div class="buy_foot01_agree">
            <img src="/mobile/web/images/img/icon_choosed01.png" class="img-check">
            <img src="/mobile/web/images/img/icon_choose01.png" style="display: none;" class="img-uncheck">
            我已阅读并同意<i><a href="<?= Url::to(['list/page','id'=>68])?>">《<?= $webTitle?>协议》</a></i>
        </div>
    </div>
    <a href="javascript:;" onclick="gradeCheck()" class="buy_now1">立即购买</a>
</div>

<div style="height: 100px"></div>

<?php $this->beginBlock('footer');?>
<script>
    var buy_now_url = $('.buy_now1').attr('href');
    // 用户协议
    $('.buy_foot01_agree img').click(function () {
        var buy_url = $('.buy_now1').attr('href');
        if(buy_url){
            $('.img-check').css({"display":"none"});
            $('.img-uncheck').css({'display':''});
            $('.buy_now1').removeAttr('href');
            $('.buy_now1').css({'background-color':'#bfbfbf'});
        }else{
            $('.img-uncheck').css({"display":"none"});
            $('.img-check').css({'display':''});
            $('.buy_now1').attr('href',buy_now_url);
            $('.buy_now1').css({'background-color':'#33aaff'});
        }
    });

    // 选项切换
    $('.cat-title-detail').click(function () {
        $(this).addClass('active').siblings().removeClass('active');
        $('.comment-list').hide();
        $('.product-img').show();
        $('.buy_foot01_div').show();
    });
    $('.cat-title-comment').click(function () {
        $(this).addClass('active').siblings().removeClass('active');
        $('.product-img').hide();
        $('.buy_foot01_div').hide();
        $('.comment-list').show();
    });

    // 会员检测
    function gradeCheck() {
        var grade = <?= $user_info->grade?>;
        var url = '<?= Url::to(['list/sub-data','id'=>18])?>';
        if(grade && grade === 3){     // 金牌会员
            layer.msg('你已经是金牌会员，无需重复购买');
            return false;
        }
        location.href = url;
    }
</script>
<?php $this->endBlock();?>
