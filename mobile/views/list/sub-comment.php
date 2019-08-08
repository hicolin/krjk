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
<div class="pl_main">
    <textarea name="content" placeholder="请输入2-100字数内的评论内容" class="pl_con"></textarea>
</div>
<!--main end-->
<!--foot -->
<div class="pl_foot">
    <button type="button" onclick="pl_check()">确认发布</button>
</div>
<!--foot end-->

<script>
    function pl_con(){
        var str=$(".pl_con").val();
        var csrfToken = "<?= Yii::$app->request->csrfToken ?>";
        var grade_id ="<?=$grade_id?>";
        if(str.length<2 || str.length>100) {
            layer.tips('请输入2-100字数内的评论内容', ".pl_con", {tips:3,time:2000});
            return false;
        }else{
            var url_pl = "<?=Url::toRoute(['list/comment','grade_id'=>$grade_id])?>";
            // alert(url_pl);return;
            $.ajax({
                url  : "<?= Url::toRoute(['list/sub-comment'])?>",
                type : 'post',
                data : {'content':str,'grade_id':grade_id,'_csrf':csrfToken,},
                dataType:'text',
                success:function(data){
                    if(data==100){
                        layer.confirm('评论成功，需要等管理员审核通过才能显示', {
                            btn: ['确定'] //按钮
                        }, function(){
                            window.location.href=url_pl;
                        }, function(e){
                            layer.close(e);
                            return false;
                        });
                    }else{
                        layer.tips('评论失败', ".pl_con", {tips:3,time:2000});
                    }
                }
            });
        }
    }

    function pl_check() {
        if(pl_con()){
            return true;
        }else{
            return false;
        }
    }
</script>

<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
