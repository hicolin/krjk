<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminSetting;
use yii\bootstrap\Tabs;

$modelLabel = new \backend\models\AdminSetting()
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="col-sm-12 row">
                        <table class="table table-bordered">
                            <?php
                            echo Tabs::widget([
                                'items' => [
                                    [
                                        'label' => $setting[1],
                                        'url' => Url::toRoute(['admin-setting/index','id'=>1]),
                                        'active' => $type==1?true:''
                                    ],

                                    [
                                        'label' => $setting[2],
                                        'url' => Url::toRoute(['admin-setting/index','id'=>2]),
                                        'active' => $type==2?true:''
                                    ],

                                    [
                                        'label' => $setting[3],
                                        'url' => Url::toRoute(['admin-setting/index','id'=>3]),
                                        'active' => $type==3?true:''
                                    ],
                                ],
                            ]);
                            ?>

                            <?php
                            foreach ($list as $k=> $arr) { ?>

                                <?php foreach ($arr as $key=> $arr1) { ?>
                                    <tr>
                                        <td class="col-sm-2">
                                            <label for="<?= $arr1['id']?>">
                                                <input readonly type="text" style="width: 200px;" class="form-control setting" field="key" id="<?= $arr1['id']?>"  value="<?= $arr1['key']?>">
                                                <?php
                                                    if($arr1['id']==18)
                                                    {
                                                        echo "<span style=\"color: red\">*不要填100</span>";
                                                    }else if($arr1['id']==19)
                                                    {
                                                        echo "<span style=\"color: red\">*不要填100</span>";
                                                    }
                                                    else if($arr1['id']==20)
                                                    {
                                                        echo "<span style=\"color: red\">*不要填100</span>";
                                                    }
                                                    else if($arr1['id']==7)
                                                    {
                                                        echo "<span style=\"color: red\">*不要填100</span>";
                                                    }
                                                    else if($arr1['id']==12)
                                                    {
                                                        echo "<span style=\"color: red\">*不要填100</span>";
                                                    }
                                                    else if($arr1['id']==13)
                                                    {
                                                        echo "<span style=\"color: red\">*不要填100</span>";
                                                    }
                                                    else if($arr1['id']==16)
                                                    {
                                                        echo "<span style=\"color: red\">*要以英文,符号分开</span>";
                                                    }
                                                    else if($arr1['id'] == 40)
                                                    {
                                                        echo "<span style=\"color: red\">* 1: 开启&nbsp;&nbsp; 0: 关闭</span>";
                                                    }
                                                ?>
                                            </label>
                                            <?php
                                            if($arr1['type']==2) {
                                                echo "<label style='font-size: 12px;color: red'>轮播图大小：<span style='font-size: 14px;'>350*350</span></label>";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if($arr1['id']==1) { ?>
                                                <label class="radio-inline">
                                                    <input type="radio" class="radio" <?=$arr1['val']?'checked':''?> name="key" id="<?= $arr1['id']?>" value="1"> 开
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" class="radio" <?=$arr1['val']?'':'checked'?> name="key" id="<?= $arr1['id']?>" value="0"> 关
                                                </label>
                                            <?php }else {?>
                                                <?php
                                                if($arr1['type']==3){ ?>
                                                    <a class="change_img" id="<?= $arr1['id']?>" style="width: 100px;height: 100px;"><img id="img<?= $arr1['id']?>" width="100" height="100" src="<?= $arr1['val']?:'/backend/web/images/default.jpg'?>"></a>
                                                    <?php
                                                        if($arr1['id']==9)
                                                        {

                                                            echo "<span style=\"color: red\">*建议上传640*1135</span>";
                                                        }
                                                    else if($arr1['id']==11)
                                                    {

                                                        echo "<span style=\"color: red\">*建议上传640*1135</span>";
                                                    }
                                                    else if($arr1['id']==17)
                                                    {
                                                        echo "<span style=\"color: red\">*建议上传640*127</span>";

                                                    }
                                                    else if($arr1['id']==21)
                                                    {
                                                        echo "<span style=\"color: red\">*建议上传644*312</span>";

                                                    }
                                                    else if($arr1['id']==26)
                                                    {
                                                        echo "<span style=\"color: red\">*建议上传108*108</span>";

                                                    }
                                                    else if($arr1['id']==31)
                                                    {
                                                        echo "<span style=\"color: red\">*建议上传640px*高度不限</span>";

                                                    }
                                                    else if($arr1['id']==34)
                                                    {
                                                        echo "<span style=\"color: red\">*建议上传640px*256px</span>";

                                                    }
                                                    else
                                                    {
                                                        echo "<span style=\"color: red\">*建议上传305*305</span>";
                                                    }
                                                    ?>
                                                <?php }elseif($arr1['type']==2) { ?>
                                                    <a class="change_img" id="<?= $arr1['id']?>" style="width: 100%;height: 100px;"><img id="img<?= $arr1['id']?>" height="100" src="<?= $arr1['val']?:'/backend/web/images/default.jpg'?>"></a>
                                                <?php }else { ?>
                                                    <input type="text" class="form-control setting" field="val" id="<?= $arr1['id']?>" placeholder="<?= $arr1['key']?>" value="<?= $arr1['val']?>">
                                                <?php }
                                                ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php  } } ?>
                        </table>
                    </div>
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


<div style="display: none">
    <input type="file" name="file" class="file" id="file" onchange="document.getElementById('textfield').value=this.value" />
    <span onclick="UploadFile()" class="mybtn">上传</span>
</div>
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
        var csrfToken = "<?=Yii::$app->request->csrfToken?>";
        if(xhr.readyState == 4)
        {
            if (xhr.status == 200 || xhr.status == 0)
            {
                var result = xhr.responseText;
                //var json = eval(  alert(result)
                $('#img'+img_id).attr('src',result)
                $.ajax({
                    type: "POST",
                    url: "<?= Url::toRoute('admin-setting/ajax-update')?>",
                    data: {"val": result, 'field':'val', 'id':img_id, '_csrf': csrfToken},
                    cache: false,
                    dataType: "json",
                    error: function (xmlHttpRequest, textStatus, errorThrown) {
                        alert("出错了，" + textStatus);
                    },
                    success: function (data) {
                        //window.location.reload();
                    }
                });
                //alert(result)
                /*var json = eval("(" + result + ")");
                 alert('图片链接:\n'+json.file);*/
            }
        }
    }
</script>



<script>
    var img_id = 0;
    $(function(){

        $('.change_img').bind('click',function(){
            $('#img_id').val($(this).attr('id'))
            img_id = $(this).attr('id')
            $('#file').click()
        })
        var file = document.getElementById("file");
        file.onchange=function(){
            $('.mybtn').click()
        }
    })
    var vals = '';
    $('.setting').focus(function(){
        vals = $(this).val();
    })
    $('.setting').blur(function(){
        var id = $(this).attr('id');
        var field = $(this).attr('field');
        var val = $(this).val();
        if(val==vals) {
            return 1
        }else{
            change(id,val,field)
        }
    })
    $(function(){
        $('.radio').bind("click", function(e){
            var a = $(this).attr('checked')
            var id = $(this).attr('id')
            var val = $(this).val()
            if(!a) {
                change(id,val,'val')
            }
            return
        })

    })
    function change(id,val,field) {
        var csrfToken = "<?=Yii::$app->request->csrfToken?>";
        $.ajax({
            type: "POST",
            url: "<?= Url::toRoute('admin-setting/ajax-update')?>",
            data: {"id": id ,'val':val, 'field':field, '_csrf': csrfToken},
            cache: false,
            dataType: "json",
            error: function (xmlHttpRequest, textStatus, errorThrown) {
                alert("出错了，" + textStatus);
            },
            success: function (data) {
                window.location.reload();
            }
        });
    }

</script>
<?php $this->endBlock(); ?>
