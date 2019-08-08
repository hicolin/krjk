<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<style>
    .son_more_1 li,.son_more_0 li{display: flex;justify-content: space-around}
    .son_more_1 li span,.son_more_0 li span{display:inline-block;width: 25%;text-align: center}
</style>
<?php $this->endBlock(); ?>

<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
<!--nav-->
<div class="tuiguang_nav">
    <div class="tn_head">
        <a href="javascript:;">
            <i class="iconfont icon-zhanghu1"></i><?=$user_info->tel?>
        </a>
    </div>
    <div class="tn_con">
        <span>累计赚取（元）</span><br/>
        <p class="mon_num"><?=number_format(($user_info->promotion_commission+$user_info->dai_commission),2)?></p>
    </div>
</div>
<!--nav end-->

<!--main-->
<div class="tuiguang_main">
    <ul>
        <li>
            <a href="javascript:;"  style="border-right: 1px solid #eee;">
                <span>团队奖励（元）</span><br/>
                <em><?=$user_info->promotion_commission?></em>
            </a>
        </li>
        <li>
            <a href="<?=Url::toRoute(['member/dai-commission'])?>"  style="border-right: 1px solid #eee;">
                <span>产品奖励（元）</span><br/>
                <em><?=$user_info->dai_commission?></em>
            </a>
        </li>
        <div class="clear"></div>
    </ul>

</div>
<!--main end-->

<!--tui_bot-->
<div class="tui_bot">
    <div class="tuib_top">
        <ul>
            <li class="curr" state="0"><span>关注成功[<?= $member_num?>]</span></li>
            <li state="1"><span>购买成功[<?= $buy_member_num?>]</span></li>
            <div class="clear"></div>
        </ul>
    </div>
    <div class="tuib_con">
        <div class="tuib_son">
            <ul class="son_more_0">
                <?php
                foreach ($member as $list) { ?>
                    <li>
                        <span><?= date('Y-m-d',$list->created_time)?></span>
                        <span><?= $list->tel?></span>
                        <span><?= $list->nickname?></span>
                        <span><?= $list->grade > 0 ? '已购买' : '未购买' ?></span>
                    </li>
                <?php }
                ?>
            </ul>
            <div class="jiazai_more" id="more_0" style="display: none"><a href="javascript:;">加载更多</a></div>
            <div class="jiazai_nomore" id="nmore_0"><a href="javascript:;">没有更多了哦</a></div>
        </div>
        <div class="tuib_son hiddendiv">
            <ul class="son_more_1">
                <?php
                foreach ($buy_member as $buy_list) { ?>
                    <li>
                        <span><?= date('Y-m-d',$buy_list->created_time)?></span>
                        <span><?= $buy_list->tel?></span>
                        <span><?= $buy_list->nickname?></span>
                        <span><?= $buy_list->grade > 0 ? '已购买' : '未购买' ?></span>
                    </li>
                <?php }
                ?>
            </ul>
            <div class="jiazai_more" id="more_1" style="display: none"><a href="javascript:;">加载更多</a></div>
            <div class="jiazai_nomore" id="nmore_1"><a href="javascript:;">没有更多了哦</a></div>
        </div>
    </div>
</div>
<div style="height: 100px"></div>
<!--tui_bot end-->
<script>
    var state = 0;
    var page = 1;
    var show = true;
    //切换
    $(function(){
        $(".tuib_top li").click(function(){
            $(this).addClass("curr").siblings().removeClass("curr");
            $('.tuib_son').hide().eq($(this).index()).show();
            page = 1;
            show = true;
            $('.jiazai_nomore').hide();
            state = $(this).attr('state');
        })
    });
</script>
<script>
    /*加载更多*/
    $(window).scroll(function () {
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        if ((scrollTop + windowHeight >= scrollHeight && show)) {
            $("#more_"+state).show();
            Load();
        }
    });
    function Load() {
        show = false;
        setTimeout(function () {
            page++;
            $.ajax({
                url: "<?= Url::toRoute(['member/load-customer','type'=>4])?>",
                type: 'get',
                data: {'page': page,'state':state},
                dataType: 'text',
                async: false,
                success: function (data) {
                    if (data) {
                        $(".son_more_"+state).append(data);
                        show = true;
                    } else {
                        show = false;
                        $("#more_"+state).hide().next().show();
                    }
                }
            });
        }, 1000);
    }
</script>

<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
