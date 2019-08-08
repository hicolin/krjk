<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminCardProgress;
$modelLabel=new \backend\models\AdminCardProgress()
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
                            <a id="create_btn" style="font-size: 16px"  href="<?=Url::toRoute([$this->context->id.'/index'])?>" class="btn btn-xs btn-primary">返回</a>
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
                        <div class="form-group" style="display: none;">
   <label for="id" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("id")?></label>
   <div class="col-sm-8">
   <?php echo $form->field($model,'id')->textInput(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("id"),"id"=>'id']) ?>
   </div>
</div>
 <div class="clear"></div>
  <div class="form-group">
      <label for="image"
             class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("img") ?></label>
      <div class="col-sm-8">
          <img class="change_img" id="thumb" width="120" height="136" src="/backend/web/images/default.jpg">
          <?php echo $form->field($model, 'img')->hiddenInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("img"), "id" => 'img']) ?>
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
   <label for="permission" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("permission")?><span style="font-size: 12px;color: red;">(*注：勾选为可见) </span></label>
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
   <label for="type" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("type")?></label>

     <div class="col-sm-3">
      <?= $form->field($model, 'type')->dropDownList(
         ['1'=>'在线查询','2'=>'其他渠道']
      ) ?>
  </div>
</div>
 <div class="clear"></div>
<div class="form-group" style="display: none;">
   <label for="create_tim" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("create_time")?></label>
   <div class="col-sm-8">
   <?php echo $form->field($model,'create_time')->textInput(["value"=>time(),"class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("create_time"),"id"=>'create_time']) ?>
   </div>
</div>
 <div class="clear"></div>
                        <div class="form-group">
                            <label for="resource" class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-8">
                                <?php echo Html::submitButton('保存', ['class' =>"btn btn-primary",'id'=>"put_up"]); ?>
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
<div style="display: none">
    <input type="file" name="file" class="file" id="file" onchange="document.getElementById('textfield').value=this.value" />
    <span onclick="UpladFile('deal')" class="mybtn">上传</span>
</div>

<script>
    $('.change_img').on('click',function(){
        $('#file').click();
    })
    var img = document.getElementById("file");
    img.onchange=function () {
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

    function UpladFile()
    {
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

    function deal()
    {
        if(xhr.readyState == 4)
        {
            if (xhr.status == 200 || xhr.status == 0)
            {
                var result = xhr.responseText;
                $('.change_img').attr('src',result)
                $('#img').val(result)
                //alert(result)
                /*var json = eval("(" + result + ")");
                 alert('图片链接:\n'+json.file);*/
            }
        }
    }


    $('#put_up').click(function(){

        var name = $('#name').val();
    
       
        if(!name){
            layer.msg('请输入名称！');
            return false;
        }


         $('#form').submit();
            
    })
</script>
<?php  $this->beginBlock('footer');  ?>
<?php  $this->endBlock(); ?>
