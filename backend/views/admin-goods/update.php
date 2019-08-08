<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminGoods;
use common\controllers\PublicController;
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
                            'enctype' => 'multipart/form-data', 
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
                                <?php echo $form->field($model, 'title')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("title"),"value"=>PublicController::filter_decode($model->title), "id" => 'title']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>

                        <!--<div class="form-group">
                            <label for="title"
                                   class="col-sm-2 control-label"><?php /*echo $modelLabel->getAttributeLabel("grade_id") */?></label>
                            <div class="col-sm-8">
                                <select style="width: 150px;" onchange="gradeChange()"  id="myselect" name="AdminGoods[grade_id]" >
                                           <option value="0">请选择</option>
                                    <?php /*foreach ($grade as $key => $value): */?>
                                            <option value="<?/*=$value->id*/?>" <?/*= ($value->id ==$model->grade_id)?'selected':'' */?> > <?/*=$value->grade_name*/?></option>
                                    <?php /*endforeach */?>
                                </select>
                            </div>
                        </div>-->
                        <script type="text/javascript">
                                function gradeChange(){
                                     var id=$("#myselect").val();
                                    $.ajax({
                                        type: "GET",
                                        url: "<?=Url::toRoute($this->context->id . '/getprice')?>",
                                        data: {"id": id},
                                        cache: false,
                                        dataType: "json",
                                        error: function (xmlHttpRequest, textStatus, errorThrown) {
                                            alert("出错了，" + textStatus);
                                        },
                                        success: function (data) {
                                            $("#price").val(data);
                                        }   
                                    });
                                }
                        </script>
                        <div class="form-group">
                            <label for="price"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("price") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'price')->textInput(["class" => "form-control","placeholder" => $modelLabel->getAttributeLabel("price"), "id" => 'price']) ?>
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
                            <label for="pic"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("pic") ?>   <p style="color: red">*请务必上传vip  banner图片</p>
                                   <p style="color: red">*建议上传602*295</p>
                            </label>
                            <div class="col-sm-8">
                                <img src="<?=$model->pic?>" width="200" height="100" >
                                <?= $form->field($model, 'pic')->fileInput() ?>
                            </div>
                        </div> 
                         <div class="clear"></div>
                        <div class="form-group">
                            <label for="bei_pic"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("bei_pic") ?>   <p style="color: red">*请务必上传vip  banner背景图片</p>
                                   <p style="color: red">*建议上传423*232</p>
                               </label>
                            <div class="col-sm-8">
                                <img src="<?=$model->bei_pic?>" width="200" height="100" >
                                <?= $form->field($model, 'bei_pic')->fileInput() ?>
                            </div>
                        </div>   
                        <div class="form-group">
                            <label for="detail"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("detail") ?></label>
                            <div class="col-sm-8">
                                <?php /*echo $form->field($model,'detail')->textarea(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("detail"),"id"=>'detail']) */ ?>
                                <textarea name="AdminGoods[detail]" id="detail"
                                          style="float:left;width:100%; height:300px; ;border:0;"><?= $model->detail; ?></textarea>
                                <p class="help-block help-block-error"></p>
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
