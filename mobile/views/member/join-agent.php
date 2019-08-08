<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
<style type="text/css">
    .mui-bar-nav {
    background: #1a1a1f;
}
.mui-bar-nav.mui-bar .mui-icon {
    margin-right: -10px;
    margin-left: -10px;
    padding-right: 10px;
    padding-left: 10px;
}
.mui-bar .mui-icon {
    font-size: 24px;
    position: relative;
    z-index: 20;
    padding-top: 10px;
    padding-bottom: 10px;
}
.mui-icon-left-nav {
    color: #fff;
}
.mui-action-back span a{
    font-size: 16px!important;
    margin-right: 10px;
}

.mui-icon {
    font-family: Muiicons;
    font-size: 24px;
    font-weight: 400;
    font-style: normal;
    line-height: 1;
    display: inline-block;
    text-decoration: none;
    -webkit-font-smoothing: antialiased;
}
</style>

<div class="agent_main" >

    <div class="agent_main_top">
        <img src="<?=$model->pic?:Url::base().'/mobile/web/images/banner.png'?>"/>
    </div>

    <div class="agent_main_nav">
        <div class="amn_title">
            <img src="<?= Url::base() ?>/mobile/web/images/reck.png"/>
            <span>参与方式</span>
            <img src="<?= Url::base() ?>/mobile/web/images/reck.png"/>
        </div>
        <div class="amn_con">
            <img src="<?= Url::base() ?>/mobile/web/images/agent2.png"/>
        </div>
    </div>

    <div class="agent_main_money mt10">
       <div class="amn_title tc">
            <img src="<?= Url::base() ?>/mobile/web/images/reck.png"/>
            <span>工资介绍</span>
            <img src="<?= Url::base() ?>/mobile/web/images/reck.png"/>
        </div>
        <div class="amm_con">
            <?=$model->detail?>
        </div>
    </div>

    <div class="agent_main_btn" style="max-width: 600px;">
        <!--<a href="<?/*=Url::toRoute(['member/sub-join','pid'=>$model->id,'type'=>$type])*/?>">立即加入</a>-->
        <a href="javascript:;" onclick="join()">立即加入</a>
    </div>

    <style type="text/css">

        body{
            margin-bottom: 60px;
        }
        .person_foot{
            display: none;
        }
    </style>
</div>
<script>
    function join() {
        var qq = "<?=PublicController::getSysInfo(15)?>";
        var pid = "<?=$model->id?>";
        var url = "<?=Yii::$app->urlManager->createAbsoluteUrl(['member/sub-join','pid'=>$model->id,'type'=>$type])?>";
        $.ajax({
            url : '<?=Url::toRoute('member/validate-agent')?>',
            type : 'get',
            data : {'pid':pid},
            dataType:'text',
            success:function(data){
                // alert(data)
                if(data==100 || data==400) {
                    window.location.href=url;
                }else if(data==200) {
                    layer.msg('加入失败，请重试！',{icon:2,time:2000})
                }else if(data==300) {
                    layer.confirm('您已提交申请，请等待管理员审核',{
                        btn: ['确定','联系客服'] //按钮
                    }, function(){
                        layer.closeAll();
                    }, function(){
                        window.location.href="http://wpa.qq.com/msgrd?v=&uin="+qq+"&site=qq&menu=yes";
                    });
                    //layer.msg('您已提交申请，请等待管理员审核！',{icon:1,time:2000})
                }else if(data==500) {
                    layer.msg('您的代理身份已禁止，如需回复请联系管理员',{icon:1,time:2000})
                }
            }
        })
    }
</script>
<!--main end-->

<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
