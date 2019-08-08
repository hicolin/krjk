<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminArticle;
$modelLabel=new \backend\models\AdminArticle();
?>
<?php  $this->beginBlock('header');  ?>
<!-- <head></head>中代码块 -->
<?php  $this->endBlock(); ?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <a id="create_btn" href="<?=Url::toRoute([$this->context->id.'/index'])?>" class="btn btn-xs btn-primary">admin-articles列表</a>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->

                <div class="box-body">

                    <div class="tab-content">
                        <div class="form-group">
   <label for="art_id" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("art_id")?></label>
   <div class="col-sm-8">
<div class="form-control" style="height: auto;min-height: 34px;"><?=$model->art_id?></div>   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="title" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("title")?></label>
   <div class="col-sm-8">
<div class="form-control" style="height: auto;min-height: 34px;"><?=$model->title?></div>   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="img" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("img")?></label>
   <div class="col-sm-8">
<div class="form-control" style="height: auto;min-height: 34px;"><?=$model->img?></div>   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="permission" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("permission")?></label>
   <div class="col-sm-8">
<div class="form-control" style="height: auto;min-height: 34px;"><?=$model->permission?></div>   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="is_recom" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("is_recom")?></label>
   <div class="col-sm-8">
<div class="form-control" style="height: auto;min-height: 34px;"><?=$model->is_recom?></div>   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="datail" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("detail")?></label>
   <div class="col-sm-8">

<div class="form-control" style="height: auto;min-height: 34px;"><?=$model->detail?></div>   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="create_time" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("create_time")?></label>
   <div class="col-sm-8">
<div class="form-control" style="height: auto;min-height: 34px;"><?=date('Y-m-d H:i:s',$model->create_time)?></div>   </div>
</div>
 <div class="clear"></div>
                        <div class="form-group">
                            <label for="logo" class="col-sm-2 control-label" >&nbsp;</label>
                            <div class="col-sm-8">
                                <div class="form-control" style="height: auto;min-height: 34px;border: none;">
                                    <a href="javascript:history.back(-1)" class="btn btn-primary"> 返&nbsp;回</a>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<?php  $this->beginBlock('footer');  ?>
<?php  $this->endBlock(); ?>
