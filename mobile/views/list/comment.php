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
<div class="dis_main" style="margin-bottom: 0.2rem">
    <div class="buy_main_pl">
        <div class="buy_main_pl_list" style="margin-bottom: 3rem">
            <ul>
                <?php
                foreach ($model as $list) { ?>
                    <li>
                        <div class="bmpl_left fl">
                            <img src="<?=$list->member['pic']?:Url::base().'/mobile/web/images/tx2.png'?>"/>
                        </div>
                        <div class="bmpl_right fl">
                            <h1>
                                <span><?=$list->member['nickname']?></span>
                                <i class="fr"><?=date('Y-m-d',$list->create_time)?></i>
                            </h1>
                            <p><?=$list->content?></p>
                        </div>
                        <div class="clear"></div>
                    </li>
                <?php }
                ?>
            </ul>
            <div class="jiazai_more" style="display: none" ><a href="javascript:;">加载更多</a></div>
            <div class="jiazai_nomore"><a href="javascript:;">没有更多了哦</a></div>
        </div>
    </div>
</div>
<!--main end-->

<!--foot -->
<div class="dis_foot" style="height: 1.6rem">
    <a href="<?=Url::toRoute('list/sub-comment')?>">
        立即评价
    </a>

</div>
<!--foot end-->

<script>
    //    适应不同屏幕
    /*window.onload=function(){
        document.documentElement.style.fontSize= document.documentElement.clientWidth/16+'px' ;
    };*/

</script>
<script>
    /*加载更多*/
    var page = 1;
    var show = true;
    $(window).scroll(function(){
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        if (scrollTop + windowHeight >= scrollHeight && show) {
            $(".jiazai_more").show();
            Load();
        }
    });
    function Load() {
        show = false;
        page++;
        var grade_id = '<?=$list->grade_id?>';
        setTimeout(function () {
            $.ajax({
                url  : "<?= Url::toRoute(['list/load-more','type'=>1])?>",
                type : 'get',
                data : {'page':page,'grade_id':grade_id},
                dataType:'text',
                success:function(data){
                    if(data){
                        show = true;
                        $(".buy_main_pl_list ul").append(data);
                    }else{
                        show = false;
                        $(".jiazai_more").hide().next().show();
                    }
                }
            });
        },1000);
    }
</script>
<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
