<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<style>
    .per_zh_main li{width: 33%}
    .invite_main{background: #fff;}
    .invite_main_nav{}
    .invite_main_nav li{width: 25%;float: left;text-align: center;}
    .invite_main_nav li a{font-size: 0.6rem;padding: 0 0.2rem;line-height: 1.5rem;display: inline-block;}
    .invite_main_nav li.curr a{color: #33aaff;border-bottom: 1px solid #33aaff;}

    .invite_main_con{width: 100%;}
    .invite_main_con table{width: 100%;text-align: center;border-collapse: collapse; min-height: 1.8rem; line-height: 1.8rem;}
    .invite_main_con table tr{}
    .invite_main_con table tr td{background: #fff;color: #666;font-size: 0.6rem;border-bottom: 1px solid #f0f0f0;overflow:hidden;white-space: nowrap;text-overflow: ellipsis;}
    .invite_main_con table tr:first-child{height: 2rem;line-height: 2rem;}
    .invite_main_con table tr:first-child td{background: #f2f2f2;font-size: 0.6rem;color: #333;}
    .invite_main_con table tr td{font-size: 0.6rem;color: #888;}
</style>
<?php $this->endBlock(); ?>
<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
<!--nav-->
<div class="per_zh_nav">
    <div class="pzn_head">
        <a href="person.html">
            <i class="iconfont icon-zhanghu1"></i><?=$user_info->tel?>
        </a>
        <a href="<?=Url::toRoute('member/withdraw-record')?>" class="fr">
            <i class="iconfont icon-qianbao"></i>领取记录
        </a>
    </div>
    <div class="pzn_con">
        <span>可领取金额（元）</span><br/>
        <p class="mon_num"><?=$user_info->available_money?></p>
        <a href="<?=Url::toRoute('member/withdraw')?>">去领取</a>
    </div>

</div>

<!--nav end-->


<!--main-->
<div class="per_zh_main">
    <ul>
        <li>
            <a href="<?=Url::toRoute('member/customer')?>"  style="border-right: 1px solid #eee;">
                <span>粉丝奖励（元）</span><br/>
                <em><?=$user_info->promotion_commission?><i class="iconfont icon-dayuhao"></i></em>
            </a>
        </li>
        <li>
            <a href="<?=Url::toRoute('member/product-award')?>"  style="border-right: 1px solid #eee;">
                <span>产品奖励（元）</span><br/>
                <em><?=$user_info->dai_commission?><i class="iconfont icon-dayuhao"></i></em>
            </a>
        </li>
        <li>
            <a href="<?=Url::toRoute('member/activity-award')?>">
                <span>活动奖励（元）</span><br/>
                <em><?=$award ? : 0 ?><i class="iconfont icon-dayuhao"></i></em>
            </a>
        </li>
        <div class="clear"></div>
    </ul>
</div>

<div class="invite_main">
    <div style="text-align: center;background-color: #ddd;padding: 8px;font-size: 15px">返现记录</div>
    <div class="invite_main_con">
        <table>
            <tr>
                <td>代理产品</td>
                <td>用户/级</td>
                <td>发放工资/￥</td>
                <td>发放时间</td>
            </tr>
            <?php
            foreach ($model as $list) { ?>
                <tr>
                    <td><?=$list->p_name?></td>
                    <td><?= \mobile\controllers\Service::dealTrader($list->jy_user_info) ?></td>
                    <td><?= $list->commission_money ?></td>
                    <td><?=date('Y-m-d',$list->created_time)?></td>
                </tr>
            <?php }
            ?>
        </table>
        <div class="jiazai_more" style="display: none"><a href="javascript:;">加载更多</a></div>
        <div class="jiazai_nomore"><a href="javascript:;">没有更多了哦</a></div>
    </div>
</div>

<!--main end-->

<?php $this->beginBlock('footer'); ?>
<script>
    /*加载更多*/
    var page=1;
    var show = true;
    $(window).scroll(function(){
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        if(scrollTop + windowHeight >= scrollHeight && show){
            $(".jiazai_more").show();
            Load();
        }
    });
    function Load() {
        show = false;
        setTimeout(function () {
            page++;
            $.ajax({
                url  : "<?= Url::toRoute(['member/account'])?>",
                type : 'post',
                data : {'page':page},
                dataType:'text',
                success:function(data){
                    if(data){
                        show = true;
                        $(".invite_main_con table").append(data);
                    }else{
                        show = false;
                        $(".jiazai_more").hide().next().show();
                    }
                }
            });
        },1000);
    }

    $(".invite_main_nav ul li a").each(function(){
        if ($(this)[0].href == String(window.location) && $(this).attr('href')!="") {
            $(this).parent('li').addClass("curr");
        }
    });
</script>
<?php $this->endBlock(); ?>
