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
<div class="record_main">
    <div class="re_con_li2">
        <ul>
            <?php
            foreach ($model as $list) { ?>
                <li>
                    <h1>提现:￥<?=$list->money?></h1>
                    <h2><?=date('Y-m-d H:i:s',$list->created_time)?></h2>
                    <span class="fr"><?=$state[$list->status]?></span>
                    <div class="clear"></div>
                </li>
            <?php }
            ?>
        </ul>
        <div class="jiazai_more" style="display: none"><a href="javascript:;">加载更多</a></div>
        <div class="jiazai_nomore"><a href="javascript:;">没有更多了哦</a></div>
    </div>
</div>
<!--main end-->
<script>
    //    适应不同屏幕
   /* window.onload=function(){
        document.documentElement.style.fontSize= document.documentElement.clientWidth/16+'px' ;
    };*/
    /*加载更多*/
    var page = 0;
    $(window).scroll(function () {
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        if ((scrollTop + windowHeight >= scrollHeight && $(".jiazai_more").css("display") == "block") || !page) {
            $(".jiazai_more").show();
            Load();
        }
    });
    function Load() {
        setTimeout(function () {
            page++;
            $.ajax({
                url: "<?= Url::toRoute(['member/load-more'])?>",
                type: 'get',
                data: {'page': page},
                dataType: 'text',
                success: function (data) {
                    if (data) {
                        $(".re_con_li2 ul").append(data);
                    } else {
                        $(".jiazai_more").hide().next().show();
                    }
                }
            });
        }, 1000);
    }
</script>
<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
