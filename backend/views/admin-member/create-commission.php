<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminMember;
use backend\models\UploadForm;

$modelLabel = new \backend\models\AdminMember()
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
                            <a id="create_btn" href="<?= Url::toRoute([$this->context->id . '/commission','id'=>$id]) ?>"
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
                            <label for="name"
                                   class="col-sm-2 control-label">产品名称</label>
                            <div class="col-sm-8">
                                <input type="text" name="name" class="form-control" >
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="name"
                                   class="col-sm-2 control-label">交易金额</label>
                            <div class="col-sm-8">
                                <input type="number" name="money" class="form-control" >
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="name"
                                   class="col-sm-2 control-label">返佣金额</label>
                            <div class="col-sm-8">
                                <input type="number" name="commission_money" class="form-control" >
                            </div>
                        </div>
                        <div class="clear"></div>
                        <input type="hidden" name="user_id" value="<?=$id?>">
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
<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
