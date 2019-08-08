<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>

<!--nav-->
<div class="tuiguang_nav">
    <div class="tn_head">
        <a href="javascript:;">
            <i class="iconfont icon-zhanghu1"></i><?=$user_info->tel?>
        </a>
        <a href="<?=Url::toRoute('member/customer-list')?>" class="fr">
            <i class="iconfont icon-kehu"></i>客户列表
        </a>
    </div>
    <div class="tn_con">
        <span>本月收入（元）</span><br/>
        <p class="mon_num"><?=!empty($commission)?$commission:'0.00'?></p>
    </div>
</div>
<!--nav end-->


<!--main-->
<div class="tuiguang_main" style="padding: 0.4rem 0;">
    <ul>
        <li>
            <a href="<?=Url::toRoute('member/customer')?>"  style="border-right: 1px solid #eee;">
                <span>累计赚取（元）</span><br/>
                <em><?=number_format($user_info->promotion_commission+$user_info->dai_commission,2)?></em>
            </a>
        </li>
        <li>
            <a href="<?=Url::toRoute('member/withdraw-record')?>"  style="border-right: 1px solid #eee;">
                <span>累计领取（元）</span><br/>
                <em><?=!empty($tx_money)?$tx_money:'0.00'?></em>
            </a>
        </li>
        <div class="clear"></div>
    </ul>

</div>
<!--main end-->


<!--main list-->
<div class="reward_main">
    <div class="reward_main_head">
        <i class="iconfont icon-xinyongqia"></i>
        <span>信用卡</span>
    </div>
    <div class="reward_main_con">
        <ul>
            <?php
            foreach ($data['card'] as $bank) { ?>
                <li>
                    <a href="javascript:;" onclick="join('<?=$bank->permission?>','<?=$bank->links?>')">
                        <img src="<?=$bank->logo?>"/><br/>
                        <span><?=$bank->title?></span>
                    </a>
                </li>
            <?php }
            ?>
            <div class="clear"></div>
        </ul>

    </div>

</div>
<!--main list end-->

<!--main list-->
<div class="reward_main">
    <div class="reward_main_head">
        <i class="iconfont icon-daikuan"></i>
        <span>贷款</span>
    </div>
    <div class="reward_main_con2">
        <ul>
            <?php
            foreach ($data['loan'] as $loan) { ?>
                <li>
                    <a href="javascript:;" onclick="join('<?=$loan->permission?>','<?=$bank->links?>')">
                        <img src="<?=$loan->logo?>"/><br/>
                        <span><?=$loan->title?></span>
                    </a>
                </li>
            <?php }
            ?>
            <div class="clear"></div>
        </ul>

    </div>

</div>
<!--main list end-->
<script>
    function join(is_agent,url) {
        if(is_agent==1) {
            var agent = "<?=$user_info->agent?>";
            if(agent==1) {
                window.location.href=url;
                return false;
            }else{
                layer.confirm('没有权限查看？您未开通会员资格！点击确定即可开通会员', {
                    btn: ['确定'] //按钮
                }, function () {
                    window.location.href = "<?=Url::toRoute('list/buy-agent')?>";
                }, function (e) {
                    layer.close(e);
                    return false;
                });
                return false;
            }
        }else{
            window.location.href=url;
        }
    }
</script>

<script>
    //    适应不同屏幕
    /*window.onload=function(){
        document.documentElement.style.fontSize= document.documentElement.clientWidth/16+'px' ;
    };*/

    //    切换
    $(function(){
        $(".tuib_top li").click(function(){
            $(this).addClass("curr").siblings().removeClass("curr");
            $('.tuib_son').hide().eq($(this).index()).show();
        })
    });

</script>

<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
