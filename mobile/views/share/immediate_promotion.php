<?php

use yii\helpers\Url;

?>
<style>
    .layui-layer-content {
        background: #fff;
        z-index: 10000;
    }

    /*一键推广*/
    /*.tui_main{width: 100%;background: url("
    <?= Url::base() ?> /mobile/web/images/tui_bg.png ") center center;background-size: cover;position: relative;}*/
    .tui_main_con {
        width: 38%;
        position: absolute;
        bottom: 2rem;
        left: 33%;
        text-align: center;
    }

    .tui_main_con h1 {
        font-size: 0.7rem;
        line-height: 1rem;
        color: #66221b;
    }

    .tui_main_con img {
        width: 90%;
        margin: 5%;
    }

    .tui_link {
        padding: 2%;
        background: #fff;
    }

    .tui_link h1 {
        font-size: 0.8rem;
        color: #666;
        line-height: 2.5rem;
    }

    .tui_link h2 {
        height: 1.5rem;
    }

    .tui_link h2 span {
        width: 75%;
        height: 1.5rem;
        line-height: 1.5rem;
        background: #f3f3f3;
        border-radius: 3px;
        font-size: 0.6rem;
        color: #999;
        padding-left: 2%;
        display: inline-block;
    }

    .tui_link h2 i {
        width: 23%;
        height: 1.5rem;
        line-height: 1.5rem;
        background: #f3f3f3;
        border-radius: 3px;
        font-size: 0.6rem;
        color: #999;
        text-align: center;
        display: inline-block;
    }

    .tui_link h3 {
        font-size: 0.55rem;
        color: #666;
        line-height: 2.5rem;
    }

    .tui_link h4 {
        text-align: center;
    }

    .tui_link h4 a {
        display: inline-block;
        width: 7.5rem;
        height: 1.75rem;
        line-height: 1.75rem;
        border-radius: 1.75rem;
        background: #51c0f9;
        font-size: 0.65rem;
        color: #fff;
        box-shadow: 0 0 10px #aaa;
    }

    .person_foot {
        display: none;
    }

    .share-footer{height: 3rem;position: fixed;bottom: 0;background-color: #fff;width: 100%;max-width:600px;display: flex;justify-content:space-between;padding: 0.3rem}
    .share-footer .share-item{width: 50%;text-align: center;margin-top: 0.2rem;line-height: 1rem}
    .share-item:nth-child(1){border-right: 1px solid #ccc}
    .fa{font-size: 1.2rem}
</style>

<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent(); ?>

<div class="poster_main" id="main">
    <img src="<?= $picss ?>" class="bg" id="bg"/>
</div>
<!--main end-->

<div class="share-footer">
    <div class="share-item" onclick="downloadImage('<?= $picss?>')">
        <span><i class="fa fa-download"></i><br>保存海报</span>
    </div>
    <div class="share-item copy-text" data-clipboard-text="<?= $url?>" onclick="">
        <span><i class="fa fa-unlink"></i><br>复制链接</span>
    </div>
</div>
<?php $this->beginBlock('footer');?>
<script>
    $(function () {
        var h1 = $(".head").height();
        var h2 = $(".header").height();
        var h_body = $(window).height();
        h3 = h_body - h1 - h2;
        $(".tui_main").css("height", h3 + 'px');

        var w1 = $(".tui_main_con").width();
        $(".tui_main_con").css("height", w1 + 'px');
    });

</script>
<?php $this->endBlock();?>

