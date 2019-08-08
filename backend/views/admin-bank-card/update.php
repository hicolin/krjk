<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminBankCard;
use common\controllers\PublicController;
$modelLabel = new \backend\models\AdminBankCard()
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
                            <a id="create_btn" href="<?= Url::toRoute([$this->context->id . '/' . $view]) ?>"
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
                        'action' => Url::toRoute(['admin-bank-card/update','id'=>$model->id]),
                        'options' => [
                            'class' => 'new_user_form inline-input',
                        ],
                        'id' => 'form',
                    ])
                    ?>
                    <div class="tab-content">
                        <div class="form-group">
                            <label for="image"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("logo") ?></label>
                            <div class="col-sm-8">
                                <img class="change_img" img_type="logo" id="thumb_logo" width="80" height="90" src="<?=$model->logo?:'/backend/web/images/default.jpg'?>">
                                <?php echo $form->field($model, 'logo')->hiddenInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("logo"), "id" => 'logo']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="title"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("title") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'title')->textInput(["class" => "form-control","value"=>PublicController::filter_decode($model->title), "placeholder" => $modelLabel->getAttributeLabel("title"), "id" => 'title']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="recommend"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("recommend") ?></label>
                            <div class="col-sm-8">
                                <?= $form->field($model, 'recommend')->checkbox() ?>
                            </div>
                        </div>
                        <div class="clear"></div>


                          <div class="form-group">
                               <label for="name" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("permission")?><span style="font-size: 12px;color: red;">(*注：勾选为可见) </span></label>
                               <div class="col-sm-8">
                               <?= $form->field($model, 'permission')->checkbox() ?>

                               </div>
                            </div>
                        <div class="clear"></div>


                        <div class="form-group">
                           <label for="links" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("links")?></label>
                           <div class="col-sm-8">
                           <?php echo $form->field($model,'links')->textInput(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("links"),"id"=>'links']) ?>
                           </div>
                        </div>
                        <div class="clear"></div>                        
                        <div class="form-group">
                            <label for="price"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("price") ?>/万元</label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'price')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("price").',请填写数字', "id" => 'price']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="beizhu"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("beizhu") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'beizhu')->textInput(["class" => "form-control","value"=>PublicController::filter_decode($model->beizhu), "placeholder" => $modelLabel->getAttributeLabel("beizhu"), "id" => 'beizhu']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="rate"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("rate") ?>/%</label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'rate')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("rate").',请填写数字', "id" => 'rate']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="interest"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("interest") ?>/%</label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'interest')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("interest").',请填写数字', "id" => 'interest']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="interest"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("hk_way") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'hk_way')->textInput(["class" => "form-control","value"=>PublicController::filter_decode($model->hk_way), "placeholder" => $modelLabel->getAttributeLabel("hk_way"), "id" => 'hk_way']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="interest"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("range") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'range')->textInput(["class" => "form-control", "value"=>PublicController::filter_decode($model->range),"placeholder" => $modelLabel->getAttributeLabel("range"), "id" => 'range']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="interest"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("time_limit") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'time_limit')->textInput(["class" => "form-control", "value"=>PublicController::filter_decode($model->time_limit),"placeholder" => $modelLabel->getAttributeLabel("time_limit"), "id" => 'time_limit']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>

                        <div class="form-group">
                            <label for="price"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("flow") ?></label>
                            <div class="col-sm-8">
                                <img class="change_img" id="thumb_flow" img_type="flow" width="120" height="136" src="<?=$model->flow?:'/backend/web/images/default.jpg'?>">
                                <?php echo $form->field($model, 'flow')->hiddenInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("flow"), "id" => 'flow']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="condition" class="col-sm-2 control-label" >申请条件</label>
                            <div class="col-sm-8">
                                <textarea name="condition" id="condition" style="float:left;width:100%; height:300px; ;border:0;"><?=$model->condition?></textarea>
                                <script type="text/javascript">
                                    UE.getEditor("condition",{
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
                            <label for="attention" class="col-sm-2 control-label" >注意事项</label>
                            <div class="col-sm-8">
                                <textarea name="attention" id="attention" style="float:left;width:100%; height:300px; ;border:0;"><?=$model->attention?></textarea>
                                <script type="text/javascript">
                                    UE.getEditor("attention",{
                                        theme:"default", //皮肤
                                        lang:"zh-cn",    //语言
                                        wordCount:true,
                                        maximumWords:1000,
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <input type="hidden" name="type" value="<?=$type?>">
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
<div style="display: none">
    <input type="file" name="file" class="file" id="file" onchange="document.getElementById('textfield').value=this.value" />
    <span onclick="UploadFile()" class="mybtn">上传</span>
</div>
<script>
    var img_type = '';
    $('.change_img').on('click',function(){
        $('#file').click();
        img_type = $(this).attr('img_type');
    })
    var head_img = document.getElementById("file");
    head_img.onchange=function () {
        $('.mybtn').click()
    }
</script>
<script type="text/javascript">
    var xhr;
    function createXMLHttpRequest()
    {
        if(window.ActiveXObject)
        {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }
        else if(window.XMLHttpRequest)
        {
            xhr = new XMLHttpRequest();
        }
    }

    function UploadFile()
    {
        var fileObj = document.getElementById("file").files[0];
        //服务器端的路径
        var FileController = "<?=Url::toRoute('public/file')?>";
        var form = new FormData();
        //file可更改，在服务器端获取$_FILES['file']
        form.append("file", fileObj);
        createXMLHttpRequest();
        xhr.onreadystatechange = deal;
        xhr.open("post", FileController, true);
        xhr.send(form);
    }

    function deal()
    {
        if(xhr.readyState == 4)
        {
            if (xhr.status == 200 || xhr.status == 0)
            {
                var result = xhr.responseText;
                $('#thumb_'+img_type).attr('src',result)
                $('#'+img_type).val(result)
            }
        }
    }
</script>
<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
