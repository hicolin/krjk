<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminKey;
$modelLabel=new \backend\models\AdminKey()
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
                            <a id="create_btn" href="<?=Url::toRoute([$this->context->id.'/index'])?>" class="btn btn-xs btn-primary">admin-keys列表</a>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <?php                    $form=ActiveForm::begin([
                    'fieldConfig' => [
                    'template' => '<div class="span12 field-box">{input}</div>{error}',
                    ],
                    'options' => [
                    'class' => 'new_user_form inline-input',
                    ],
                    'id'=>'form',
                    ])
                    ?>
                    <div class="tab-content">
                        <div class="form-group">
   <label for="id" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("id")?></label>
   <div class="col-sm-8">
   <?php echo $form->field($model,'id')->textInput(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("id"),"id"=>'id']) ?>
   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="name" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("name")?></label>
   <div class="col-sm-8">
   <?php echo $form->field($model,'name')->textInput(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("name"),"id"=>'name']) ?>
   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="sex" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("sex")?></label>
   <div class="col-sm-8">
   <?php echo $form->field($model,'sex')->textInput(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("sex"),"id"=>'sex']) ?>
   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="mobile" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("mobile")?></label>
   <div class="col-sm-8">
   <?php echo $form->field($model,'mobile')->textInput(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("mobile"),"id"=>'mobile']) ?>
   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="province" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("province")?></label>
   <div class="col-sm-8">
   <?php echo $form->field($model,'province')->textInput(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("province"),"id"=>'province']) ?>
   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="city" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("city")?></label>
   <div class="col-sm-8">
   <?php echo $form->field($model,'city')->textInput(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("city"),"id"=>'city']) ?>
   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="area" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("area")?></label>
   <div class="col-sm-8">
   <?php echo $form->field($model,'area')->textInput(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("area"),"id"=>'area']) ?>
   </div>
</div>
 <div class="clear"></div>
                        <div class="form-group">
                            <label for="resource" class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-8">
                                <?php echo Html::submitButton('保存', ['class' =>"btn btn-primary"]); ?>
                                <span>&nbsp;</span>
                                <?php echo Html::resetButton('重置', ['class' =>"btn btn-primary"]); ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <?php  ActiveForm::end();?>
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
