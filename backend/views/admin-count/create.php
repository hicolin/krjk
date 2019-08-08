<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminDaiList;

$modelLabel = new \backend\models\AdminDaiList()
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
                        <!--<div class="form-group">
                            <label for="logo"
                                   class="col-sm-2 control-label">头像</label>
                            <div class="col-sm-8">
                                <img class="change_img" id="thumb" width="60" height="60" src="/backend/web/images/user.jpg">
                                <?php /*echo $form->field($model, 'logo')->hiddenInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("logo"), "id" => 'logo']) */?>
                            </div>
                        </div>
                        <div class="clear"></div>-->
                        <div class="form-group">
                            <label for="tel"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("tel") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'tel')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("tel"), "id" => 'tel']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="p_name"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("p_name") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'p_name')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("p_name"), "id" => 'p_name']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="money"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("money") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'money')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("money"), "id" => 'money']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="status"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("status") ?></label>
                            <div class="col-sm-8">
                                <?php $model->status = 1?>
                                <?= $form->field($model, 'status')->checkbox() ?>
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
<div style="display: none">
    <input type="file" name="file" class="file" id="file" onchange="document.getElementById('textfield').value=this.value" />
    <span onclick="UploadFile()" class="mybtn">上传</span>
</div>
<script>
    $('.change_img').on('click',function(){
        $('#file').click();
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
                $('.change_img').attr('src',result)
                $('#logo').val(result)
                //alert(result)
                /*var json = eval("(" + result + ")");
                 alert('图片链接:\n'+json.file);*/
            }
        }
    }
</script>
<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
