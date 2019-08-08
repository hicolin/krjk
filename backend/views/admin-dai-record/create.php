<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminDaiRecord;

$modelLabel = new \backend\models\AdminDaiRecord()
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <a id="create_btn" href="<?= Url::toRoute([$this->context->id . '/index']) ?>"
                               class="btn btn-xs btn-primary">返回列表</a>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <?php $form = ActiveForm::begin([
                        'fieldConfig' => [
                            'template' => '<div class="span12 field-box">{input}</div>{error}',
                        ],
                        'options' => [
                            'class' => 'new_user_form inline-input',
                        ],
                        'id' => 'form',
                    ])
                    ?>
                    <div class="tab-content">
                        <div class="form-group">
                            <label for="user_id"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("user_id") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'user_id')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("user_id"), "id" => 'user_id']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="name"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("name") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'name')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("name"), "id" => 'name']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="tel"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("tel") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'tel')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("tel"), "id" => 'tel']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="ip"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("ip") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'ip')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("ip"), "id" => 'ip']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="phone_system"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("phone_system") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'phone_system')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("phone_system"), "id" => 'phone_system']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="update_time"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("update_time") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'update_time')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("update_time"), "id" => 'update_time']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="resource" class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-8">
                                <?php echo Html::submitButton('保存', ['class' => "btn btn-primary"]); ?>
                                <span>&nbsp;</span>
                                <?php echo Html::resetButton('重置', ['class' => "btn btn-primary"]); ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
