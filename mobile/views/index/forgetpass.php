<?php
use yii\helpers\Url;
use common\controllers\PublicController;

?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
    <link rel="stylesheet" href="<?=Url::base()?>/mobile/web/css/css.css">
    <link rel="stylesheet" type="text/css" href="<?=Url::base()?>/mobile/web/css/basic2.css">
    <link rel="stylesheet" type="text/css" href="<?=Url::base()?>/mobile/web/css/style2.css">
    <link rel="stylesheet" type="text/css" href="<?=Url::base()?>/mobile/web/css/add2.css">
    <link rel="stylesheet" type="text/css" href="css/add.css">
    <link rel="stylesheet" type="text/css" href="http://at.alicdn.com/t/font_395792_7kp2lx6jdag1ra4i.css">

    <style>
        .iconfont{
            font-family:"iconfont" !important;
            font-size:16px;
            font-style:normal;
            -webkit-font-smoothing: antialiased;
            -webkit-text-stroke-width: 0.2px;
            -moz-osx-font-smoothing: grayscale;
        }
         body{background-color: #ffffff }
      .person_foot{

            display: none;
        }
    </style>

<body class="kh_body">
<div class="content_w">
    <div class="head_fixed">
        <p class="head_fixed_p"><a href="javascript:history.go(-1)" class="back"><i class="iconfont icon-xiaoyuhao"></i></a>找回密码 </p>
    </div>
    <div class="kh_con">
        <div class="zc_con">
            <form method="post" action="<?=Url::toRoute('index/findpassword')?>" onsubmit="return check()">
                <ul>
                    <li>
                        <div class="zcc_left fl">
                            <span>手机号</span>
                        </div>
                        <div class="zcc_mid fl" style="width: 45%">
                            <input type="text" name="tel" class="zc_tel" placeholder="请输入手机号"/>
                        </div>
                        <div class="zcc_right fl">
                            <a class="send_code" onclick="settime(this)">获取验证码</a>
                        </div>
                        <div class="clear"></div>
                    </li>
                    <li>
                        <div class="zcc_left fl">
                            <span>验证码</span>
                        </div>
                        <div class="zcc_mid fl">
                            <input type="text" name="code" class="zc_yzm" placeholder="请输入验证码"/>
                        </div>
                        <div class="clear"></div>
                    </li>
                    <li>
                        <div class="zcc_left fl">
                            <span>登陆密码</span>
                        </div>
                        <div class="zcc_mid fl">
                            <input type="password"  name="password" class="zc_psd1" placeholder="请输入密码"/>
                        </div>
                        <div class="clear"></div>
                    </li>
                    <li>
                        <div class="zcc_left fl">
                            <span>确认密码</span>
                        </div>
                        <div class="zcc_mid fl">
                            <input type="password" name="password2" class="zc_psd2" placeholder="请输入密码"/>
                        </div>
                        <div class="clear"></div>
                    </li>

                </ul>
                <div class="zc_btn">
                    <button type="submit">确认</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>

$(".send_code").click(function(){
        var tel=$(".zc_tel").val();
        var temp ='lxdkcs_04';//找回密码
        $.ajax({
          type:'GET',
          url:'<?=Url::toRoute('index/sms')?>',
          data:{'tel':tel,'temp':temp},
          async:false, 
          dataType:'json', 
          success:function(data){
            if(data.msg==-200){
                layer.open({
                content: '您的验证码发送成功',
                skin: 'msg',
                time: 15000
                });
              } 
          }
      })
  })
    var countdown=60;
    function settime(obj) {
        var str=$(".zc_tel").val();
        var reg=/^1(3|4|5|7|8)\d{9}$/;
        if(!reg.test(str)){
            layer.open({
                content: '请输入正确的手机号'
                ,skin: 'msg'
                ,time: 1500
            });
            return false;
        }else if (countdown == 0){
            obj.removeAttribute("disabled");
            obj.innerHTML="重新获取";
            countdown = 60;
            return;
        } else {
            obj.setAttribute("disabled", true);
            obj.innerHTML="重新发送(" + countdown + ")";
            countdown--;
        }
        setTimeout(function() {
                    settime(obj) }
                ,1000)
    }

    function zc_yzm(){
        var str=$(".zc_yzm").val();
        var reg=/^[\w]{2,20}$/g;
        if(!reg.test(str)){
            layer.open({
                content: '请输入验证码'
                ,skin: 'msg'
                ,time: 1500
            });
            return false;
        }
        else{return true;}
    }

    function zc_psd1(){
        var str=$(".zc_psd1").val();
        var reg=/^[\w]{2,20}$/g;
        if(!reg.test(str)){
            layer.open({
                content: '请输入6-10位数字+字母的密码'
                ,skin: 'msg'
                ,time: 1500
            });
            return false;
        }
        else{return true;}
    }

    function zc_psd2(){
        var str=$(".zc_psd2").val();
        var reg=/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,10}$/;
        if(!reg.test(str)){
            layer.open({
                content: '请输入6-10位数字+字母的密码'
                ,skin: 'msg'
                ,time:1500
            });
            return false;
        }
        else{return true;}
    }

    function bijiao(){
      var str1=$(".zc_psd1").val();
      var str2=$(".zc_psd2").val();
      if(str1==''&&str2==''){
          layer.open({
                content: '请输入6-10位数字+字母的密码'
                ,skin: 'msg'
                ,time: 1500
          });
            return false;

      }
      if(str1!=str2){
          layer.open({
                content: '两次密码不一致'
                ,skin: 'msg'
                ,time: 1500
          });
            return false;
      }else{

          return true;
      }
     
    }
    function check() {
        if(zc_yzm()&&zc_psd1()&&zc_psd2()&&bijiao()){
            return true;
        }else{
            return false;
        }
    }



</script>
</body>
<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>