<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminPromoCode;
$modelLabel=new \backend\models\AdminPromoCode()
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
                            <a id="create_btn" href="<?=Url::toRoute([$this->context->id.'/index'])?>" class="btn btn-xs btn-primary">admin-promo-codes列表</a>
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
   <label for="user_id" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("user_id")?></label>
   <div class="col-sm-8">
   <?php echo $form->field($model,'user_id')->textInput(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("user_id"),"id"=>'user_id']) ?>
   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="p_id" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("p_id")?></label>
   <div class="col-sm-8">
   <?php echo $form->field($model,'p_id')->textInput(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("p_id"),"id"=>'p_id']) ?>
   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="promo_code" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("promo_code")?></label>
   <div class="col-sm-8">
   <?php echo $form->field($model,'promo_code')->textInput(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("promo_code"),"id"=>'promo_code']) ?>
   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="promo_url" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("promo_url")?></label>
   <div class="col-sm-8">
   <?php echo $form->field($model,'promo_url')->textInput(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("promo_url"),"id"=>'promo_url']) ?>
   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="money" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("money")?></label>
   <div class="col-sm-8">
   <?php echo $form->field($model,'money')->textInput(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("money"),"id"=>'money']) ?>
   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="status" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("status")?></label>
   <div class="col-sm-8">
   <?php echo $form->field($model,'status')->textInput(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("status"),"id"=>'status']) ?>
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
