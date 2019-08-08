<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminMember;
$modelLabel=new \backend\models\AdminMember();
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
                            <a id="create_btn" href="<?=Url::toRoute([$this->context->id.'/index'])?>" class="btn btn-xs btn-primary">admin-members列表</a>
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
   <label for="nickname" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("nickname")?></label>
   <div class="col-sm-8">
<div class="form-control" style="height: auto;min-height: 34px;"><?=$model->nickname?></div>   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="real_name" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("real_name")?></label>
   <div class="col-sm-8">
<div class="form-control" style="height: auto;min-height: 34px;"><?=$model->real_name?></div>   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="pic" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("pic")?></label>
   <div class="col-sm-8">
<div class="form-control" style="height: auto;min-height: 34px;"><?=$model->pic?></div>   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="openid" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("openid")?></label>
   <div class="col-sm-8">
<div class="form-control" style="height: auto;min-height: 34px;"><?=$model->openid?></div>   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="tel" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("tel")?></label>
   <div class="col-sm-8">
<div class="form-control" style="height: auto;min-height: 34px;"><?=$model->tel?></div>   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="agent" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("agent")?></label>
   <div class="col-sm-8">
<div class="form-control" style="height: auto;min-height: 34px;"><?=$model->agent?></div>   </div>
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
