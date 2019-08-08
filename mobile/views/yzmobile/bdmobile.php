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
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/mobile/layer.js"></script>
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
        .uevs{display:none;}
    </style>

<body class="kh_body">
<div class="content_w">
    <div class="head_fixed">
        <p class="head_fixed_p"><a href="javascript:history.go(-1)" class="back"><i class="iconfont icon-xiaoyuhao"></i></a>绑定手机号</p>
    </div>
    <div class="kh_con">
       <div class="zc_con">
           <form method="post" action="<?=Url::toRoute(['yzmobile/bdmobileadd'])?>" onsubmit="return check()">
               <ul>
                   <li>
                       <div class="zcc_left fl">
                           <span>手机号</span>
                       </div>
                       <div class="zcc_mid fl" style="width: 50%">
                           <input type="number" name="tel" class="zc_tel" placeholder="请输入手机号"/>
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

                    <li class='uevs'>
                       <div class="zcc_left fl">
                           <span>登陆密码</span>
                       </div>
                       <div class="zcc_mid fl">
                           <input type="password" name="password" class="zc_psd1" placeholder="请输入密码"/>
                       </div>
                       <div class="clear"></div>
                   </li>
                   <li class='uevs'>
                       <div class="zcc_left fl">
                           <span>确认密码</span>
                       </div>
                       <div class="zcc_mid fl">
                           <input type="password"  name="password2" class="zc_psd2" placeholder="请输入密码"/>
                       </div>
                       <div class="clear"></div>
                   </li>
                   <input type="hidden"  class="txt" >
               </ul>
               <div class="zc_btn">
                   <button type="submit">确认</button>
               </div>
           </form>
       </div>
    </div>
</div>

  <script type="text/javascript">
    var msg ='友情提示！为了保证您的数据与手机端数据互通,请您先绑定手机号！谢谢！';
    layer.alert(msg, {icon: 1});     
  </script> 

  <script>
    var isU = 1 ;   
  $(".send_code").click(function(){  
          var tel=$(".zc_tel").val();
          $.ajax({
            type:'GET',
            url:'<?=Url::toRoute('index/mix-wsms')?>',
            data:{'tel':tel},
            dataType:'json',
            async:false, 
            success:function(data){ 
                if(data.status==1){
                    layer.msg('已发送注意查收');
                    $('.txt').val(data.info);
                    if(data.isU == 0){
                        isU = 0 ; 
                        $(".uevs").show();
                    }else{
                        isU = 1 ; 
                        $(".uevs").hide();
                    }
                }else{
                    layer.msg('发送失败');
                }
            } ,
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                // 状态码
                alert('状态码:'+ XMLHttpRequest.status); 
                // 状态
                //alert(XMLHttpRequest.readyState);
                // 错误信息   
                //alert(textStatus);
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

    function mobile(){
        var str=$(".zc_tel").val();
        var reg=/^[\w]{2,20}$/g;
        if(!reg.test(str)){
            layer.open({
                content: '请输入手机号'
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
                content: '请输入密码'
                ,skin: 'msg'
                ,time: 1500
            });
            return false;
        }
        else{return true;}
    }

  function zc_psd2(){
        var str=$(".zc_psd2").val();
        var reg=/^[\w]{2,20}$/g;
        if(!reg.test(str)){
            layer.open({
                content: '请确认密码'
                ,skin: 'msg'
                ,time: 1500
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
                content: '请输入密码'
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
        if(mobile()&&zc_yzm()){ 
            if(isU == 0 ){ 
                if( zc_psd1() && zc_psd2() && bijiao() ){
                     return true;
                }else{
                    return false; 
                }  
            } 
        }else{
            return false;
        }
    }

    $(function(){
        $('.kh4_checkbox label').click(function(){
            if($(this).hasClass("checked")){
                $(this).removeClass("checked")
            }else{
                $(this).addClass("checked")
            }
        });
    });

</script>
</body>
<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>