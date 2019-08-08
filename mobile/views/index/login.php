<?php
use yii\helpers\Url;
use common\controllers\PublicController;

?>
<?php $this->beginBlock('header'); ?>

<?php $this->endBlock(); ?>
<!--    <link rel="stylesheet" href="--><?//=Url::base()?><!--/mobile/web/css/css.css">-->
    <link rel="stylesheet" type="text/css" href="<?=Url::base()?>/mobile/web/css/basic2.css">
    <link rel="stylesheet" type="text/css" href="<?=Url::base()?>/mobile/web/css/style2.css">
    <link rel="stylesheet" type="text/css" href="<?=Url::base()?>/mobile/web/css/add2.css">
<!--    <link rel="stylesheet" type="text/css" href="css/add.css">-->

    <style>
         body{background-color: #ffffff }
        .person_foot{
            display: none;
        }
    </style>

<body class="kh_body">
<div class="content_w">
    <div class="head_fixed">
        <p class="head_fixed_p"><a href="javascript:history.go(-1)" class="back"><i class="iconfont icon-xiaoyuhao"></i></a>登录 </p>
    </div>
    <div class="kh_con">
        <div class="zc_con">
            <form method="post" action="" onsubmit="return false;">
                <ul>
                    <li>
                        <div class="zcc_left fl">
                            <span>账号</span>
                        </div>
                        <div class="zcc_mid fl" >
                            <input type="text" name="tel" class="zc_tel" placeholder="请输入手机号"/>
                        </div>
                        <div class="clear"></div>
                    </li>
                    <li>
                        <div class="zcc_left fl">
                            <span>登录密码</span>
                        </div>
                        <div class="zcc_mid fl">
                            <input type="password"  name="password" class="zc_psd" placeholder="请输入登录密码"/>
                        </div>
                        <div class="clear"></div>
                    </li>
                </ul>
                <div class="zc_btn">
                    <button type="submit" onclick="login()">确认</button>
                </div>

                <div class="zc_bot">
                    <span class="fl" style="padding-left: 10px;"><a href="<?= Url::toRoute('index/forgetpass') ?>">忘记密码？</a></span>
                    <a href="<?= Url::to(['index/register']) ?>"  class="fr" style="padding-right: 10px;">立即注册</a>
                    <div class="clear"></div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
<?php $this->beginBlock('footer');?>
    <script>
        function login() {
            var tel = $(".zc_tel").val();
            var pwd = $(".zc_psd").val();
            var preg = /1[3-9]\d{9}/;
            if (!preg.test(tel)) {
                layer.msg('手机号不正确', {icon: 2, time: 1500});
                return ;
            }
            if (!pwd) {
                layer.msg('密码不能为空', {icon: 2, time: 1500});
                return;
            }
            // layer.load(3);
            $.post('<?= Url::to(['index/loginadd'])?>', {tel:tel, password: pwd}, function (res) {
                // layer.closeAll();
                if (res.status === 200) {
                    layer.msg(res.msg, {icon: 1, time: 1500}, function () {
                        location.href = '<?= Url::to(['index/index'])?>';
                    })
                } else {
                    layer.msg(res.msg, {icon: 2, time: 1500})
                }
            }, 'json');
        }
    </script>
<?php $this->endBlock(); ?>