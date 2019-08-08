<?php
use yii\helpers\Url;
use common\controllers\PublicController;

?>
<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
<style type="text/css">
    .agent_page_main {
        width: 100%;
        height: 100%;
        background: url(<?= Url::base() ?>/mobile/web/images/agent_bg.png) center center;
        background-size: cover;
        position: relative;
    }

.agent_page_main_bottom a {
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

.header {
    height: 1.5rem;
    line-height: 1.5rem;
    background: #f8f8f8;
    width: 100%;
    position: relative;
    text-align: center;
}
.agent_page_main_bottom p {
    line-height: 2.5rem;
    color: #666;
    font-size: 0.6rem;
}
.agent_page_main_bottom {
    width: 100%;
    position: absolute;
    bottom: 1.5rem;
    left: 0;
    text-align: center;
}
.person_foot{
        display: none;
    }
</style>
<!--head-->

<!--main-->
<div class="agent_page_main">
    <div class="agent_page_main_bottom">
        <a href="<?= Url::toRoute('share/immediate-promotion') ?>">立即推广</a>
        <p>分享好友您拿佣金</p>
    </div>
</div>
<!--main end-->

<script>

    $(function () {
        var h1 = $(".head").height();
        var h2 = $(".header").height();
        var h_body = $("html").height();
        h3 = h_body - h1 -h2;
        $(".agent_page_main").css("height",h3+'px');
    })
</script>

