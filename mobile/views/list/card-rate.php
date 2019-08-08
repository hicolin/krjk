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
<div class="jindu_main">
    <div class="jm_head">
        <i class="iconfont icon-chaxun"></i>
        <span>在线查询</span>

    </div>

    <div class="jm_con">
        <ul>
            <?php  foreach ($process1 as $key => $value) { ?>
                
            <li>
                <a onclick="card_process('<?=$value->id?>','<?= $value-> grade ?>','<?= $user_info['grade'] ?>')" class="card_proce-<?=$value->id?>" link="<?=$value->links?>">
                    <img src="<?=$value->img?>"/>
                    <span><?=$value->name?></span>
                </a>
            </li>

             <?php } ?>

            <div class="clear"></div>
        </ul>

    </div>
</div>


<div class="jindu_main mt10">
    <div class="jm_head">
        <i class="iconfont icon-changjianwenti"></i>
        <span>其他渠道</span>

    </div>

    <div class="jm_con">
        <ul>
            <?php  foreach ($process2 as $key => $value) { ?>
                
            
            <li>
                <a onclick="card_process('<?=$value->id?>','<?= $value-> grade ?>','<?= $user_info['grade'] ?>')" class="card_proce-<?=$value->id?>" link="<?=$value->links?>" >
                    <img src="<?=$value->img?>"/>
                    <span><?=$value->name?></span>
                </a>
            </li>


        <?php } ?>            
            <div class="clear"></div>
        </ul>

    </div>
</div>

<!--main end-->


<!--foot-->
<div class="card_foot">
    <ul>
        <li>
            <a href="<?=Url::toRoute('list/card')?>">
                <i class="iconfont icon-banlishuiqia"></i>
                <span>快速办卡</span>
            </a>
        </li>
        <li class="curr">
            <a href="javascript:;">
                <i class="iconfont icon-xinyongqia"></i>
                <span>办卡进度</span>
            </a>
        </li>

        <div class="clear"></div>
    </ul>

</div>
<!--foot end-->
<script>
    //    适应不同屏幕
    /*window.onload=function(){
        document.documentElement.style.fontSize= document.documentElement.clientWidth/16+'px' ;
    };*/


    function card_process(id,grade,usergrade){
       
        if(grade > usergrade)
        {

            layer.confirm('没有权限查看？您的会员权限资格不够！点击确定即可升级', {
                btn: ['确定'] //按钮
            }, function () {
                window.location.href = "<?=Url::toRoute('list/buy-agent')?>";
                return false;
            }, function (e) {
                layer.close(e);
                return false;
            });
            return false;
        }
       
        var links =$(".card_proce-"+id).attr("link");
        $.ajax({
            type:"GET",
            data:{"id":id},
            url:"<?=Url::toRoute([$this->context->id.'/permission','status'=>3])?>",
            success:function(data){
                if(data==100){
                      window.location.href=""+links+"";
                }else if(data==200){
                    layer.confirm('您还未登录', {
                        btn: ['确定'] //按钮
                    }, function(){
                         window.location.href="#";
                    }, function(e){
                        layer.close(e);
                        return false;
                    });
                }
                else if(data==300){
                       window.location.href=""+links+"";
                } else if(data==400){
                    layer.confirm('没有权限查看？您未开通会员资格！点击确定即可开通会员', {
                        btn: ['确定'] //按钮
                    }, function(){
                       window.location.href="<?=Url::toRoute('list/buy-agent')?>";
                    }, function(e){
                        layer.close(e);
                        return false;
                    });

                }



            }



        })

    }

</script>

<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
