<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminPay;
$modelLabel=new \backend\models\AdminPay()
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
                            <a id="create_btn" href="<?=Url::toRoute([$this->context->id.'/index'])?>" class="btn btn-xs btn-primary">admin-pays列表</a>
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
                        <div class="form-group" style="display: none;" >
   <label for="id" class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("id")?></label>
   <div class="col-sm-8">
   <?php echo $form->field($model,'id')->textInput(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("id"),"id"=>'id']) ?>
   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="pay_name" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("pay_name")?></label>
   <div class="col-sm-8">
   <?php echo $form->field($model,'pay_name')->textInput(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("pay_name"),"id"=>'pay_name']) ?>
   </div>
</div>
 <div class="clear"></div>
<div class="form-group">
   <label for="is_open" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("is_open")?></label>
   <div class="col-sm-8">
        <?php $model->is_open=($model->is_open)?:1?>
        <?=$form->field($model, 'is_open')->radioList(
            [1 => '是', 0=> '否'],
            ['item' => function($index, $label, $name, $checked, $value){
                $checked=$checked?"checked":"";
                $return = '<label>';
                $return .= '<input type="radio" '.$checked.' name="' . $name . '" value="' . $value . '" tabindex="3">';
                $return .= '<i>&nbsp;</i>';
                $return .= '<span style="font-size:14px;font-weight: normal">' . $label . '</span>';
                $return .= '</label>&nbsp;&nbsp;&nbsp;';
                return $return;
            }
            ]
        )->label(false);
        ?>
   </div>
</div>
 <div class="clear"></div>

<div class="form-group">
   <label for="pay_site" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("pay_site")?></label>
   <div class="col-sm-8">
   <?php echo $form->field($model,'pay_site')->textInput(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("pay_site"),"id"=>'pay_site']) ?>
   </div>
</div>
 <div class="clear"></div>
<div class="form-group"  style="display: none;"> 
   <label for="created_time" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("created_time")?></label>
   <div class="col-sm-8">
   <?php echo $form->field($model,'created_time')->textInput(["value"=>time(),"class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("created_time"),"id"=>'created_time']) ?>
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
