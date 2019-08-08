<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminGoods;

$modelLabel = new \backend\models\AdminGoods()
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
                        <div class="form-group" style="display: none">
                            <label for="id"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("id") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'id')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("id"), "id" => 'id']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="title"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("title") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'title')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("title"), "id" => 'title']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="price"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("price") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'price')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("price"), "id" => 'price']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="sold_num"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("sold_num") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'sold_num')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("sold_num"), "id" => 'sold_num']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="detail"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("detail") ?></label>
                            <div class="col-sm-8">
                                <?php /*echo $form->field($model,'detail')->textarea(['style'=>'float:left;width:100%; height:300px;border:0;',"class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("detail"),"id"=>'detail']) */ ?>

                                <textarea name="AdminGoods[detail]" id="detail"
                                          style="float:left;width:100%; height:300px; ;border:0;"></textarea>
                                <script type="text/javascript">
                                    UE.getEditor("detail", {
                                        theme: "default", //皮肤
                                        lang: "zh-cn",    //语言
                                        wordCount: true,
                                        maximumWords: 1000,
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="datail"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("info") ?></label>
                            <div class="col-sm-8">

                                <textarea name="AdminGoods[info]" id="info"  style="float:left;width:100%; height:300px; ;border:0;"><?=$model->info?></textarea>
                                <script type="text/javascript">
                                    UE.getEditor("info",{
                                        theme:"default", //皮肤
                                        lang:"zh-cn",    //语言
                                        wordCount:true,
                                        maximumWords:1000,
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group" style="display: none">
                            <label for="create_time"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("create_time") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'create_time')->textInput(['value' => time(), "class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("create_time"), "id" => 'create_time']) ?>
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
