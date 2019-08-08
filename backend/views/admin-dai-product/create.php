<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminDaiProduct;

$modelLabel = new \backend\models\AdminDaiProduct()
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
                        <div class="form-group">
                            <label for="image"
                                   class="col-sm-2 control-label">小图标</label>
                            <div class="col-sm-8">
                                <img class="change_img" id="thumb" width="80" height="90" src="/backend/web/images/default.jpg">
                                <?php echo $form->field($model, 'logo')->hiddenInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("logo"), "id" => 'logo']) ?>
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
                            <label for="style" class="col-sm-2 control-label">类型</label>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <select name="AdminDaiProduct[style]"  id="style" style="height:30px; width:200px;border: 1px solid #ccc;">
                                        <?php $style = AdminDaiProduct::$style ?>
                                        <option value="1"  ><?= $style[1] ?></option>
                                        <option value="2"  ><?= $style[2] ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="style" class="col-sm-2 control-label">分类</label>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <select name="AdminDaiProduct[cate_id]"  id="style" style="height:30px; width:200px;border: 1px solid #ccc;">
                                        <?php foreach ($categories as $val):?>
                                            <option value="<?= $val['id']?>" <?= $model->cate_id == $val['id'] ? 'selected':''?>><?= $val['name']?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fy_info"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("fy_info") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'fy_info')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("fy_info"), "id" => 'fy_info']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>

                        <div class="form-group">
                            <label for="fy_info"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("fy_type") ?></label>
                            <div class="col-sm-8">
                            <div class="form-group">
                                    <select name="AdminDaiProduct[fy_type]"  id="fy_type" style="height:30px; width:200px;border: 1px solid #ccc;">
                                        <option value="1" <?=$model->fy_type==1?'selected':''?>>cpa固定返佣（元）</option>
                                        <option value="2" <?=$model->fy_type==2?'selected':''?>>cps %百分比返佣(点）</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fy_info"
                                   class="col-sm-2 control-label">金额/点数</label>
                            <div class="col-sm-8">
                            <style type="text/css">
                                .fy2{
                                    margin-left: 15px;

                                }
                            </style>
                            <div class="form-group">
                                购买产品金牌会员返佣
                                <span id="fy_one">(元)</span>
                                
                                <input type="" class="fy" name="AdminDaiProduct[fy_one]" value="" style="width: 200px;margin-left: 12px;" id="fy_oneq">
                                <input type="button" name="" ><span style="color: red;">1、2、3级请用英文逗号隔开</span>

                            </div>
                        </div>
                        </div>
                        <script type="text/javascript">
                            $("#fy_type").change(function(){
                                var fy_type  = $("#fy_type").val();
                                   if(fy_type==1){
                                        $("#fy_one").text('(元)');
                                        $("#fy_two").text('(元)');
                                        $("#fy_three").text('(元)');
                                        $("#fy_oneq").val('');
                                        $("#fy_twoq").val('');
                                        $("#fy_threeq").val('');
                                   }else if(fy_type==2){
                                        $("#fy_one").text('%');
                                        $("#fy_two").text('%');
                                        $("#fy_three").text('%');
                                        $("#fy_oneq").val('');
                                        $("#fy_twoq").val('');
                                        $("#fy_threeq").val('');
                                   }
                            })
                        </script>
                        <div class="form-group">
                            <label for="fy_info"
                                   class="col-sm-2 control-label">结算方式</label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'js_method')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("js_method"), "id" => 'js_method']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="titile_info"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("title_info") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'title_info')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("title_info"), "id" => 'title_info']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="label_one"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("label_one") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'label_one')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("label_one"), "id" => 'label_one']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="label_two"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("label_two") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'label_two')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("label_two"), "id" => 'label_two']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>


                        <div class="form-group">
                            <label for="share_info"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("share_info") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'share_info')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("share_info"), "id" => 'share_info']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="rate"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("links") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'links')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("links"), "id" => 'links']) ?>
                                <p style="color: red">请谨慎填写跳转链接，若填写了跳转链接则此产品返佣模式为导入数据；若未填写则此产品返佣模式为推广码</p>
                            </div>
                        </div>
                        <div class="clear"></div>

                        <!--添加开始-->
                        <div class="form-group">
                            <label for="interest"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("grade") ?></label>
                            <div class="col-sm-8">

                                <select name="grade" id="grade">
                                    <option value="">
                                        请选择
                                    </option>

                                    <?php
                                    foreach($grade as $list)
                                    {

                                        ?>
                                        <option value="<?php echo $list['id'] ?>"><?php echo $list['grade_name'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>
                        <div class="clear"></div>
                        <!--添加结束-->

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
                                <?php echo $form->field($model, 'hk_way')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("hk_way"), "id" => 'hk_way']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="interest"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("range") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'range')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("range"), "id" => 'range']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="interest"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("time_limit") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'time_limit')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("time_limit"), "id" => 'time_limit']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>  
                        
                        <div class="form-group">
                            <label for="pic"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("pic") ?>
                                       <p style="color: red">*建议上传750*227</p>
                                   </label>
                            <div class="col-sm-8">
                                <?= $form->field($model, 'pic')->fileInput() ?>
                            </div>
                        </div>
                        <div class="clear"></div>

                        <div class="form-group">
                            <label for="join_pic"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("join_pic") ?>   <p style="color: red">*请务必上传推广背景图片</p>
                                   <p style="color: red">*建议上传640*790</p>
                               </label>
                            <div class="col-sm-8">
                                <?= $form->field($model, 'join_pic')->fileInput() ?>
                            </div>
                        </div>
                        <div class="clear"></div>

                        <div class="form-group">
                            <label for="goods_detail" class="col-sm-2 control-label" >结算详情</label>
                            <div class="col-sm-8">
                                <textarea name="detail" id="detail" style="float:left;width:100%; height:300px; ;border:0;"></textarea>
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
                            <label for="goods_detail" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("apply_detail") ?></label>
                            <div class="col-sm-8">
                                <textarea name="apply_detail" id="apply_detail" style="float:left;width:100%; height:300px; ;border:0;"></textarea>
                                <script type="text/javascript">
                                    UE.getEditor("apply_detail",{
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


    $('#put_up').click(function(){
            var title = $('#title').val();
            var fy_info = $('#fy_info').val();
            var title_info = $('#title_info').val();
            var hk_way = $('#hk_way').val();
            var range = $('#range').val();
            var time_limit = $('#time_limit').val();
            var links = $('#links').val();
            var join_pic = $('#admindaiproduct-join_pic').val();
            var grade = $('#grade').val();
            var fy_oneq =$("#fy_oneq").val();
            var fy_twoq =$("#fy_twoq").val();
            var fy_threeq =$("#fy_threeq").val();
            if(fy_oneq==''&&fy_twoq==''&&fy_threeq==''){
                layer.msg('金额/点数至少有一个不为空');
                return false;
            }            
        if(!grade){
            layer.msg('购买最低等级没有选择');
            return false;
        }
            if(!join_pic){
                layer.msg('推广背景图片不能为空');
                return false;
            }                         
            if(!title){
                layer.msg('请输入文章标题！');
                return false;
            }
            if(!fy_info){
                layer.msg('请输入返佣信息！');
                return false;
            }
            /*if(!links){
                layer.msg('请输入跳转链接！');
                return false;
            } */
            if(!title_info){
                layer.msg('请输入小标题！');
                return false;
            }

            if(!hk_way){
                layer.msg('请输入还款方式！');
                return false;
            }
            if(!range){
                layer.msg('请输入贷款范围！');
                return false;
            }
            if(!time_limit){
                layer.msg('请输入还款期限！');
                return false;
            }            

         $('#form').submit();
            
    })
</script>
<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
