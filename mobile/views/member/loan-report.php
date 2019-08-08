
<?php
use yii\helpers\Url;
?>
<?php $this->beginBlock('header'); ?>
<link rel="stylesheet" href="<?= Url::base()?>/mobile/web/plugins/LCalendar/LCalendar.css">
<style>
    .person_foot{display: none}
    .wrap, .container{height: 100%;}
    .img_item img{width: 5rem;height: 5rem}
</style>
<?php $this->endBlock(); ?>

<?= $this->render('@app/views/layouts/header') ?>

<div class="lr_main" style="padding-top: .5rem">
    <div class="lr_inputItem">
        <p>客户姓名：</p>
        <input type="text" name="name" class="nameInput" placeholder="请正确填写客户姓名"/>
    </div>

    <div class="lr_inputItem">
        <p>客户电话：</p>
        <input type="tel" name="tel" class="telInput" placeholder="请正确填写客户电话"/>
    </div>

    <div class="lr_inputItem">
        <p>产品名称：</p>
        <input type="text" name="product" class="proInput" placeholder="请填写申请的产品名称"/>
    </div>

    <div class="lr_inputItem">
        <p>代理手机号：</p>
        <input type="tel" name="agent_tel" class="dlTelInput" placeholder="请填写代理手机号"/>
    </div>

    <div class="lr_inputItem">
        <p>申请时间：</p>
        <input type="text" value="" name="apply_time" id="pick_date" size="8"  placeholder="请填写申请时间">
        <input type="hidden" value="" name="loan_pic">
        <input type="hidden" value="" name="user_center_pic">
    </div>

    <div class="img_item">
        <p>产品下款页面截图：</p>
        <div>
            <img class="loan_pic" src="<?= Url::base() ?>/mobile/web/images/add_img.png" alt="">
        </div>
    </div>

    <div class="img_item">
        <p>产品个人中心截图：</p>
        <div>
            <img class="user_center_pic" src="<?= Url::base() ?>/mobile/web/images/add_img.png" alt="">
        </div>
    </div>
    <input type="button" class="btn_commitLR" value="提交"/>
</div>

<form id="uploadForm" enctype="multipart/form-data" style="display: none">
    <input type="file" name="file" id="file">
</form>

<?php $this->beginBlock('footer'); ?>
<script src="<?= Url::base()?>/mobile/web/plugins/LCalendar/LCalendar.js"></script>
<script>
    var calendar = new LCalendar();
    calendar.init({
        'trigger': '#pick_date', //标签id
        'type': 'date', //date 调出日期选择 datetime 调出日期时间选择 time 调出时间选择 ym 调出年月选择,
        'minDate': (new Date().getFullYear()-3) + '-' + 1 + '-' + 1, //最小日期
        'maxDate': (new Date().getFullYear()+3) + '-' + 12 + '-' + 31 //最大日期
    });

    $('.img_item img').click(function () {
        var className = $(this).attr('class');
        var fileObj = $('#file');
        var that = $(this);
        fileObj.unbind().click();
        fileObj.change(function(){
            layer.load(3);
            $.ajax({
                url: '<?= Url::to(['member/upload-loan-img']) ?>',
                type: 'POST',
                cache: false,
                data: new FormData($('#uploadForm')[0]),
                dataType:'JSON',
                processData: false,
                contentType: false
            }).done(function(res) {
                layer.closeAll();
                if (res.status != 200) {
                    layer.msg(res.msg, {icon: 2, time: 1500}); return;
                }
                if (className === 'loan_pic') {
                    $('input[name="loan_pic"]').val(res.path);
                } else {
                    $('input[name="user_center_pic"]').val(res.path);
                }
                that.attr('src', res.path);
            });
        });
    })

    $('.btn_commitLR').click(function () {
        var name = $('input[name="name"]').val();
        var tel = $('input[name="tel"]').val();
        var product = $('input[name="product"]').val();
        var agent_tel = $('input[name="agent_tel"]').val();
        var apply_time = $('input[name="apply_time"]').val();
        var loan_pic = $('input[name="loan_pic"]').val();
        var user_center_pic = $('input[name="user_center_pic"]').val();
        var telRule = /^1[3-9]\d{9}/;
        if (!name) {
            layer.msg('客户姓名不能为空');return;
        }
        if (!telRule.test(tel)) {
            layer.msg('客户电话不正确');return;
        }
        if (!product) {
            layer.msg('产品名称不能为空');return;
        }
        if (!telRule.test(agent_tel)) {
            layer.msg('代理电话不正确');return;
        }
        if (!apply_time) {
            layer.msg('申请时间不能为空');return;
        }
        if (!loan_pic) {
            layer.msg('请上传下款页面截图');return;
        }
        if (!user_center_pic) {
            layer.msg('请上传下款个人中心截图');return;
        }
        var data = {name: name, tel: tel, product: product, agent_tel: agent_tel, apply_time: apply_time,
            loan_pic: loan_pic, user_center_pic: user_center_pic};
        layer.load(3);
        $.post('<?= Url::to(['member/loan-report'])?>', data, function (res) {
            layer.closeAll();
            if (res.status === 200) {
                layer.msg(res.msg, function () {
                    location.href = '<?= Url::to(['/']) ?>';
                })
            } else {
                layer.msg(res.msg)
            }
        }, 'json')
    })
</script>
<?php $this->endBlock(); ?>
