<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminArticle;
use common\controllers\PublicController;
$modelLabel = new \backend\models\AdminArticle()
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
                            <a id="create_btn" href="<?= Url::toRoute([$this->context->id . '/page']) ?>"
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
                            <label for="title"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("title") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'title')->textInput(["class" => "form-control","value"=>PublicController::filter_decode($model->title), "placeholder" => $modelLabel->getAttributeLabel("title"), "id" => 'title']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>

                          <div class="form-group" style="display: none">
                            <label for="cat_id"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("cat_id") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'cat_id')->textInput(["value"=>9,"class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("cat_id"), "id" => 'cat_id']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
  
               
                        <div class="form-group" style="display: none;">
                           <label for="create_tim" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("create_time")?></label>
                           <div class="col-sm-8">
                           <?php echo $form->field($model,'create_time')->textInput(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("create_time"),"id"=>'create_time']) ?>
                           </div>
                        </div>
                         <div class="clear"></div>


                        <div class="form-group">
                            <label for="datail"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("detail") ?></label>
                            <div class="col-sm-8">

                                <textarea name="AdminArticle[detail]" id="detail"  style="float:left;width:100%; height:300px; ;border:0;"><?=$model->detail?></textarea>
                                <script type="text/javascript">
                                    UE.getEditor("detail",{
                                        theme:"default", //皮肤
                                        lang:"zh-cn",    //语言
                                        wordCount:true,
                                        maximumWords:1000,
                                    });
                                </script>
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
<<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
