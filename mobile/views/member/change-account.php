<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
<link rel="stylesheet" type="text/css" href="<?= Url::base() ?>/mobile/web/css/LArea.css">
<!--main-->
<div class="tel_main">
    <div class="tel_main_con" >
        <ul>
            <li>
                收款账户：<input name="account_number" id="account_number" value="<?=$user_info->account_number?>" class="revise_addr" type="text" placeholder="请输入支付宝账号">
            </li>
            <li>
                账户姓名：<input name="account_name" id="account_name" value="<?=$user_info->account_name?>" class="revise_addr" type="text" placeholder="请输入账户姓名">
            </li>
            <li>
                支付宝收款码：<br>
                <img src="<?= $user_info->pay_code ? : 'holder.js/120x120?text=点击上传'?>" alt="" style="width: 120px;height: 120px" class="img-qrcode">
            </li>
        </ul>
    </div>
    <div style="padding: 10px;font-size: 12px;color: #666;line-height: 20px;">此账号为收款账号，默认填写<i style="font-weight: bold">支付宝账号,姓名和支付宝收款码</i>，请认真填写</div>
    <div class="tel_main_bot">
        <button type="button" onclick="apply_check()">确定</button>
    </div>
</div>

<!--图片上传-->
<form id="uploadForm" enctype="multipart/form-data" style="display: none">
    <input type="file" name="file" id="file">
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken?>">
</form>

<!--main end-->
<?php $this->beginBlock('footer'); ?>
<script src="<?= Url::base() ?>/mobile/web/plugins/holder.js"></script>
<script>
    // 图片上传
    $('.img-qrcode').click(function () {
        $('#file').click();
    });
    $('#file').change(function () {
        layer.load(2);
        $.ajax({
            url: '<?=Url::toRoute('member/upload-pay-code')?>',
            type: 'POST',
            cache: false,
            data: new FormData($('#uploadForm')[0]),
            dataType:'JSON',
            processData: false,
            contentType: false
        }).done(function(res) {
            layer.closeAll();
            if(res.status === 200){
                $('.img-qrcode').attr('src',res.path);
            }
        });
    });
    
    //验证
    function demo1(){
        var str=$("#account_number").val();
        var reg=/^[\w]{2,50}$/g;
        if(str == '') {
            layer.tips('收款账户不能为空', "#account_number", {tips: 3, time: 2000});
            $('#account_number').focus();
            return false;
        }
        else{return true;}
    }
    function revise_addr(){
        var str=$("#account_name").val();
        var reg=/^[\w]{2,10}$/g;
        if(str == ''){
            layer.tips('账户姓名不能为空', "#account_name", {tips:3,time:2000});
            $('#account_name').focus();
            return false;
        }
        else{return true;}
    }
    function checkImg() {
        var img = $('.img-qrcode').attr('src');
        if(img.indexOf('data:image') != -1){
            layer.msg('请上传收款码');
            return false;
        }
        return true;
    }

    function apply_check() {
        var account = $("#account_number").val();
        var name = $("#account_name").val();
        if(demo1() && revise_addr() && checkImg()){
            layer.load(2);
            $.ajax({
                type: 'post',
                url: '<?=Url::toRoute("member/change-account")?>',
                async: false,
                data: {'account_name':name, 'account_number': account},
                dateType: 'text',
                error: function (xmlHttpRequest, textStatus, errorThrown) {
                    layer.closeAll();
                    layer.msg('系统出错！',{icon:5})
                },
                success: function (msg) {
                    layer.closeAll();
                    if(msg == 101) {
                        layer.tips('收款账户不能为空', "#account_number", {tips: 3, time: 2000});
                        $('#account_number').focus();
                        return false;
                    }else if (msg == 102) {
                        layer.tips('账户姓名不能为空', "#account_name", {tips:3,time:2000});
                        $('#account_name').focus();
                        return false;
                    }else if (msg == 500) {
                        layer.msg('系统出错！',{icon:5});
                        return false;
                    }else if(msg == 200) {
                        layer.msg('修改成功',{icon:1,time:2000},function () {
                            window.location.reload();
                        });
                    }
                }
            });
        }else{
            return false;
        }
    }
</script>
<?php $this->endBlock(); ?>
