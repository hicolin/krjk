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
                            <label for="cat_id"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("cat_id") ?></label>
                            <div class="col-sm-8">
                                <select name="AdminArticle[cat_id]" id="cat_id">
                                    <?php
                                    foreach($cat_list as $cat){
                                        ?>
                                        <option value="<?=$cat['id']?>" <?=$model['cat_id'] == $cat['id']?'selected':''?>><?=$cat['name']?></option>
                                    <?php }?>
                                </select>
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
                            <label for="source"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("source") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'source')->textInput(["class" => "form-control","value"=>PublicController::filter_decode($model->source), "placeholder" => $modelLabel->getAttributeLabel("source"), "id" => 'source']) ?>
                            </div>

                        </div>
                        <div class="clear"></div>

                        <div class="form-group">
                            <label for="img"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("img") ?></label>
                            <div class="col-sm-8">
                                <img class="change_img" id="thumb" width="120" height="136"src="/backend/web/images/default.jpg">
                                <?php echo $form->field($model, 'img')->hiddenInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("img"), "id" => 'img']) ?>
                                <img src="<?=$model->img?>" style="width: 150px;">
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                                  <div class="form-group">
                                   <label for="name" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("permission")?><span style="font-size: 12px;color: red;">(*注：勾选为可见) </span></label>
                                   <div class="col-sm-8">
                                    
                                  <?= $form->field($model, 'permission')->checkbox() ?>

                                   </div>
                                </div>
                                 <div class="clear"></div>

                          
                        </div>
                        <div class="clear"></div>

                        <!--添加开始-->
                        <div class="form-group">
                            <label for="interest"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("grade") ?></label>
                            <div class="col-sm-8">
                                <select name="grade" id="grade">
                                    <option value="0" <?= $model['grade'] == 0 ?'selected':''?>>普通会员</option>
                                    <?php
                                    foreach($grade as $list)
                                    {
                                        ?>
                                        <option value="<?= $list['id'] ?>" <?= $list['id'] == $model['grade'] ? 'selected': '' ?> ><?= $list['grade_name'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <!--添加结束-->

                        <div class="form-group">
                            <label for="is_recom"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("is_recom") ?></label>
                            <div class="col-sm-8">

                                <label><input type="radio" name="AdminArticle[is_recom]" <?=$model['is_recom'] == 1?'checked':''?> value="1">否</label>
                                <label><input type="radio" name="AdminArticle[is_recom]" <?=$model['is_recom'] == 2?'checked':''?> value="2">是</label>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="datail"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("detail") ?></label>
                            <div class="col-sm-8">

                                <textarea name="AdminArticle[detail]"  id="detail"  style="float:left;width:100%; height:300px; ;border:1;"><?=$model->detail?></textarea>
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
                                <?php echo Html::submitButton('保存', ['class' => "btn btn-primary",'id'=>"put_up"]); ?>
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
    <input type="file" name="file" class="file" id="file"
           onchange="document.getElementById('textfield').value=this.value"/>
    <span onclick="UpladFile('deal')" class="mybtn">上传</span>
</div>
<script>
    $('.change_img').on('click', function () {
        $('#file').click();
    })
    var img = document.getElementById("file");
    img.onchange = function () {
        $('.mybtn').click()
    }
</script>
<script type="text/javascript">
    var xhr;
    function createXMLHttpRequest() {
        if (window.ActiveXObject) {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }
        else if (window.XMLHttpRequest) {
            xhr = new XMLHttpRequest();
        }
    }

    function UpladFile() {
        var fileObj = document.getElementById("file").files[0];
        //服务器端的路径
        var FileController = "<?=Url::toRoute('/public/file')?>";
        var form = new FormData();
        //file可更改，在服务器端获取$_FILES['file']
        form.append("file", fileObj);
        createXMLHttpRequest();
        xhr.onreadystatechange = deal;
        xhr.open("post", FileController, true);
        xhr.send(form);
    }

    function deal() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200 || xhr.status == 0) {
                var result = xhr.responseText;
                $('.change_img').attr('src', result)
                $('#img').val(result)
                //alert(result)
                /*var json = eval("(" + result + ")");
                 alert('图片链接:\n'+json.file);*/
            }
        }
    }


    $('#put_up').click(function(){

        var title = $('#title').val();
        var detail = $('#detail').val();
        var grade = $('#grade').val();
       
        if(!title){
            layer.msg('请输入文章标题！');
            return false;
        }


         $('#form').submit();
            
    })
</script>
<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
