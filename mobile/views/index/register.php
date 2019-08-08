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
    <link rel="stylesheet" type="text/css" href="http://at.alicdn.com/t/font_395792_7kp2lx6jdag1ra4i.css">
    <style>
        .person_foot{
            display: none;
        }
        .iconfont{
            font-family:"iconfont" !important;
            font-size:16px;
            font-style:normal;
            -webkit-font-smoothing: antialiased;
            -webkit-text-stroke-width: 0.2px;
            -moz-osx-font-smoothing: grayscale;
        }
        .zcc_right .send_code{width: 80px;;height: 28px;border: 1px solid #12cf74;background: #fff;color: #12cf74;border-radius: 4px;}
        body{background-color: #ffffff }
    </style>

<body class="kh_body">
<div class="content_w">
    <div class="head_fixed">
        <p class="head_fixed_p"><a href="javascript:history.go(-1)" class="back"><i class="iconfont icon-xiaoyuhao"></i></a>注册 </p>
    </div>
    <div class="kh_con">
       <div class="zc_con">
           <form method="post" action="<?=Url::toRoute('index/registeradd')?>" onsubmit="return false">
               <ul>
                   <li>
                       <div class="zcc_left fl">
                           <span>手机号</span>
                       </div>
                       <div class="zcc_mid fl" style="width: 50%">
                           <input type="text" name="tel" class="zc_tel" placeholder="请输入手机号"/>
                       </div>
                       <div class="zcc_right fl">
                           <input type="button" class="send_code" onclick="settime(this)" value='获取验证码'>
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
                           <span>昵称</span>
                       </div>
                       <div class="zcc_mid fl">
                           <input type="text" name="nickname" class="nickname" placeholder="请输入昵称"/>
                       </div>
                       <div class="clear"></div>
                   </li>

                   <li>
                       <div class="zcc_left fl">
                           <span>登陆密码</span>
                       </div>
                       <div class="zcc_mid fl">
                           <input type="password" name="password" class="zc_psd1" placeholder="请输入密码"/>
                       </div>
                       <div class="clear"></div>
                   </li>
                    <input type="hidden"  class="txt" >
                   <li>
                       <div class="zcc_left fl">
                           <span>确认密码</span>
                       </div>
                       <div class="zcc_mid fl">
                           <input type="password"  name="password2" class="zc_psd2" placeholder="请输入密码"/>
                       </div>
                       <div class="clear"></div>

                   </li>
                   <?php  if(isset($_GET['invitation'])){ ?>
                     <li style="position: absolute;top:-999em">
                         <div class="zcc_left fl" >
                             <span>邀请码</span>
                         </div>
                         <div class="zcc_mid fl">
                             <input type="text"  name="yqm" value="<?=isset($_GET['invitation']) ? $_GET['invitation'] : ''; ?>" class="zc_yqm" placeholder="请输入邀请码" readonly/>
                         </div>
                         <div class="clear"></div>
                     </li>
                  <?php } ?>
               </ul>
               <div class="zc_btn">
                   <button type="submit" id="sub_btn">确认</button>
               </div>

           </form>
       </div>
    </div>
</div>
<script>
  $(".send_code").click(function(){
      var tel=$(".zc_tel").val();
      $.ajax({
        type:'GET',
        url:'<?=Url::toRoute('index/tsms')?>',
        data:{'tel':tel},
        async:false,
        dataType:'json',
        success:function(data){
          if(data.telone!=0){
              $('.txt').val(data.telone);
          }
          if(data.msg==-200){
            layer.open({
            content: '验证码发送成功',
            skin: 'msg',
            time: 15000
            });
          }
        }
    })
    });

    var countdown=60;
    function settime(obj) {
        var str=$(".zc_tel").val();
        var reg=/^1(3|4|5|7|8)\d{9}$/;
        if($('.txt').val() > 0){
          layer.open({
                  content: '此号码已经注册'
                  ,skin: 'msg'
                  ,time: 2000
              });
         return  false;
        }else if(!reg.test(str)){
            layer.open({
                content: '请输入正确的手机号'
                ,skin: 'msg'
                ,time: 1500
            });
            return false;
        }else if (countdown == 0){
            obj.removeAttribute("disabled");
            $(".send_code").val("重新获取");
            countdown = 60;
            return;
        } else {
            obj.setAttribute("disabled", true);
            $(".send_code").val("重新发送(" + countdown + ")");
            countdown--;
        }
        setTimeout(function() {
                    settime(obj) }
                ,1000)
    }

    // 提交
  $('#sub_btn').click(function () {
      var tel = $('input[name="tel"]').val();
      var code = $('input[name="code"]').val();
      var nickname = $('input[name="nickname"]').val();
      var password = $('input[name="password"]').val();
      var password2 = $('input[name="password2"]').val();
      var yqm = $('input[name="yqm"]').val();
      if (!/^1(3|4|5|7|8)\d{9}$/.test(tel)) {
          layer.msg('手机号码不正确', {icon: 2, time: 1500});
          return;
      }
      if (!code) {
          layer.msg('验证码不能为空', {icon: 2, time: 1500});
          return;
      }
      if (!nickname) {
          layer.msg('昵称不能为空', {icon: 2, time: 1500});
          return;
      }
      if (!password) {
          layer.msg('密码不能为空', {icon: 2, time: 1500});
          return;
      }
      if (password != password2) {
          layer.msg('两次密码不一致', {icon: 2, time: 1500});
          return;
      }
      var transferData = {tel: tel, code: code, nickname: nickname, password: password, password2: password2, yqm: yqm};
      layer.load(3);
      $.post('<?= Url::to(['index/registeradd'])?>', transferData, function (res) {
          layer.closeAll();
          if (res.status === 200) {
              layer.msg(res.msg, {icon: 1, time: 1500}, function () {
                  location.href = res.url;
              })
          } else {
              layer.msg(res.msg, {icon: 2, time: 2000})
          }
      }, 'json')

  });

</script>
</body>