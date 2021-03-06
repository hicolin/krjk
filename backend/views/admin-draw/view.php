<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminDrawMoney;
$modelLabel=new \backend\models\AdminDrawMoney();
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
                            <a id="create_btn" href="<?=Url::toRoute([$this->context->id.'/index'])?>" class="btn btn-xs btn-primary">admin-draw-moneys列表</a>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->

                <div class="box-body">

                    <div class="tab-content">
                        <div class="form-group">
   <label for="id" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("id")?></label>
   <div class="col-sm-8">
<div class="form-control" style="height: auto;min-height: 34px;"><?=$model->id?></div>   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="pic" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("pic")?></label>
   <div class="col-sm-8">
<div class="form-control" style="height: auto;min-height: 34px;"><?=$model->pic?></div>   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="name" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("name")?></label>
   <div class="col-sm-8">
<div class="form-control" style="height: auto;min-height: 34px;"><?=$model->name?></div>   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="url" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("url")?></label>
   <div class="col-sm-8">
<div class="form-control" style="height: auto;min-height: 34px;"><?=$model->url?></div>   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="permission" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("permission")?></label>
   <div class="col-sm-8">
<div class="form-control" style="height: auto;min-height: 34px;"><?=$model->permission?></div>   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="type" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("type")?></label>
   <div class="col-sm-8">
<div class="form-control" style="height: auto;min-height: 34px;"><?=$model->type?></div>   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="created_time" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("created_time")?></label>
   <div class="col-sm-8">
<div class="form-control" style="height: auto;min-height: 34px;"><?=$model->created_time?></div>   </div>
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
