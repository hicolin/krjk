<?php
use yii\helpers\Url;
use common\controllers\PublicController;
$order_id = Yii::$app->session->get('order_id');

?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
<style type="text/css">
    #msg {
    width: 75%;
    height: 100px;
    border: 1px solid #ccc;
    padding: 6px;
    vertical-align: top;
}
select {
    border-radius: 12px;
    width: 105px;
}
.data_main li {
    padding: 0.3rem;
    border-bottom: 1px solid #eee;
    line-height: 2.5rem;
}
</style>
 <style type="text/css">
    .person_foot{
        display: none;
    }


</style>

<!--data top-->
<div class="data_top">
    <span><i class="iconfont icon-tijianxuzhi"></i>一键入驻</span>
    <i class="iconfont icon-xiaoyuhao hiddendiv fr more"></i>
    <i class="iconfont icon-xia fr more"></i>
</div>

<div class="data_top_con">
  
</div>
<!--data top end-->

<!--nav-->
<div class="data_nav" style="display: none;">
    <a href="">
        <div class="data_nav_left fl">
            <img src="<?= Url::base() ?>/mobile/web/images/tx3.png"/>
        </div>
        <div class="data_nav_right fl">
            <h1>卡农圈  会员资格</h1>
            <h2>
                ￥<i class="fr">x 1</i>
            </h2>

        </div>
        <div class="clear"></div>
    </a>
</div>
<!--nav end-->

    <!-- <input type="hidden" id="order_id" value="<?=$order_id?>"> -->
<!--main--> 
<div class="data_main">
    <ul>
         <li>
            <i class="iconfont icon-shouji"></i>
            <input type="text" name="tel" value="" class="data_tel" placeholder="请填写您的手机号码">
            <input type="button" value="获取验证码" class="data_btn fr send_code"  onclick="sms(this)">
        </li>
        <li>
            <i class="iconfont icon-yanzhengma"></i>
            <input type="text" name="code" class="data_yzm" placeholder="请填写验证码">
        </li>
        <li>
            <i class="iconfont icon-rengezhongxin"></i>
            <input type="text" name="name" class="data_name" value="" placeholder="请填写您的真实姓名">
        </li>
        <li>
            <i class="iconfont icon-rengezhongxin"></i>
            <select name="sex" class="data_sex" style="width: 100px; border-radius: 12px;">
                <option value="-1">请选择性别</option>
                <option value="0">男</option>
                <option value="1">女</option>
            </select>
        </li>
        <li>
            <i class="iconfont icon-shouji"></i>
                <select name="province"  id="province">
                <option value="">选择省</option>
                    <?php foreach($regions as $list){?>
                      <option value="<?=$list->region_id?>"><?=$list->region_name?></option>    
                    <?php }?>                     
                </select>
                <select name="city" id="city"> 
                    <option value="">选择市</option>
                </select>
                <select name="area"  id="area">
                    <option value="">选择区</option>
                </select>
        </li>
        <li><span class="mid">详细地址:<input type="text" name="address" style="width: 50%;" id="address"></span></li>
        <li>
           <i class="iconfont icon-shouji"></i>
            <textarea id="msg" name="context" class="" placeholder="请填写您的留言" ></textarea>
        </li>
    </ul>
    <?php if (!strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')) { ?>
        <div class="data_foot">
        <div class="data_foot_left fl">
            <label for="agree">
            <p style="font-size: 14px;"  >
                <style>
                    #agree {vertical-align: middle;}
                </style>
            </p>
            </label>
        </div>
        <div class="data_foot_right fr" style="margin-bottom: 5.7em;" >
            <button onclick="data_check()" type="button" id="sub_btn" disabled style="background: #ccc;color: #fff">确认提交</button>
        </div>
        <div class="clear"></div>
    </div>
    <?php }else{ ?>
        <div class="data_foot">
        <div class="data_foot_left fl">
            <label for="agree">
            <p style="font-size: 14px;" >
                <!-- <input id="agree" type="checkbox">我已阅读并同意<br/> -->
                <style>
                    #agree {vertical-align: middle;}
                </style>
            </p>
            </label>
        </div>

        <div class="data_foot_right fr" style="margin-bottom: 8em;">
            <button onclick="data_check()" type="button" id="sub_btn" disabled style="background: #ccc;color: #fff">确认提交</button>
        </div>
        <div class="clear"></div>
    </div>
    <?php }   ?>
</div>
      
<!--main end-->
<script>
      $(document).ready(function(){
        // alert(11)
            $('#sub_btn').css('background','#4199fb')
            $('#sub_btn').attr('disabled',false)

    })

    //适应不同屏幕
    /*window.onload=function(){
        document.documentElement.style.fontSize= document.documentElement.clientWidth/16+'px' ;
    };*/
    //一键入驻
    function sms(obj) {
        var str=$(".data_tel").val();
        var temp ='lxdkcs_02';
        var reg=/^1(3|4|5|7|8)\d{9}$/;
        if(!reg.test(str)){
            layer.tips('请输入正确的手机号码', ".data_tel", {tips:3,time:2000});
            return false;
        }else{  
            $.ajax({
                url : '<?=Url::toRoute(['index/sms'])?>',
                type : 'get',
                data : {'tel':str,'temp':temp},
                dataType:'text',
                success:function(data){
                    if(data.msg==-200) {
                        layer.tips('发送成功', ".data_yzm", {tips:3,time:2000});
                        set_code(obj);
                    }else{
                        layer.tips('发送失败', ".data_yzm", {tips:3,time:2000});
                    }
                }
            })
        }
    }
    // 验证
    var countdown=60;
    function set_code(obj) {
        if (countdown == 0){
            obj.removeAttribute("disabled");
            $(".send_code").val("重新获取");
            countdown = 60;
            return;
        } else {
            console.log(2)
            obj.setAttribute("disabled", true);
            $(".send_code").val("重新发送(" + countdown + ")");
            countdown--;
        }
        setTimeout(function() {
                set_code(obj) }
            ,1000)
    }

    function data_name(){
        var str=$(".data_name").val();
        var reg=/^[\u4E00-\u9FA5A-Za-z]{2,10}$/;
        if(!reg.test(str)){
            layer.tips('请输入姓名', ".data_name", {tips:3,time:2000});
            return false;
        }
        else{return true;}
    }
    function data_tel(){
        var str=$(".data_tel").val();
        var reg=/^1(3|4|5|7|8)\d{9}$/;
        if(!reg.test(str)){
            layer.tips('请输入正确的手机号码', ".data_tel", {tips:3,time:2000});
            return false;
        }
        else{return true;}
    }
    function data_yzm(){
        var str=$(".data_yzm").val();
        var reg=/^[\w]{2,20}$/g;
        if(!reg.test(str)){
            layer.tips('请输入验证码', ".data_yzm", {tips:3,time:2000});
            return false;
        }
        else{return true;}
    }
    function data_sex(){
        var str=$(".data_sex").val();
        if(str==-1){
            layer.tips('请输入选择性别', ".data_sex", {tips:3,time:2000});
            return false;
        }
        else{return true;}
    }
    function pro(){
        var province = $("#province").val();
        var city =$("#city").val();
        var area =$("#area").val();
        var address =$("#address").val();
        if(province==''||city==''||area==''){
            layer.msg('省、市、区,详细地址不能为空');
            return false;
        }else{
            return true;
        }

    }
    function isWeiXin() {
        var ua = window.navigator.userAgent.toLowerCase();
        console.log(ua);//mozilla/5.0 (iphone; cpu iphone os 9_1 like mac os x) applewebkit/601.1.46 (khtml, like gecko)version/9.0 mobile/13b143 safari/601.1
        if (ua.match(/MicroMessenger/i) == 'micromessenger') {
        return true;
        } else {
        return false;
        }
    }
         
    function data_check() {
        var csrfToken = "<?= Yii::$app->request->csrfToken ?>";
        if(data_tel()&&data_yzm()&&data_name()&&data_sex()&&pro()){
            var name = $('.data_name').val();
            var tel = $('.data_tel').val();
            var yzm = $('.data_yzm').val();
            var sex = $('.data_sex').val();
            var msg =$('#msg').val();
            var province = $("#province").val();
            var city =$("#city").val();
            var area =$("#area").val();
            var address =$("#address").val();
            var url ='<?=Url::toRoute(['index/index','type'=>1])?>';
            layer.load(2);  
            $.ajax({
                type: "POST",
                url: "<?=Url::toRoute(['index/sub-data'])?>",
                data: {"name": name,'tel':tel,'code':yzm,'_csrf':csrfToken,'sex':sex,'msg':msg,'province':province,'city':city,'area':area,'address':address},
                cache: false,
                dataType: "json",
                error: function (xmlHttpRequest, textStatus, errorThrown) {
                    alert("出错了，" + textStatus);
                },
                success: function (data) {
                    layer.closeAll();
                    if(data==100) {
                        window.location.href = url;
                        return false;
                    }else if(data==300) {
                        layer.tips('验证码不正确', ".data_yzm", {tips:3,time:2000});
                        return false;
                    }else if(data==200) {
                        layer.tips('不是当前手机号', ".data_tel", {tips:3,time:2000});
                        return false;
                    }
                }
            });
        }else{
            return false;
        }
    }

  $(function(){
      $('#province').change(function(){
          var id = $(this).val();
          $.ajax({
              type:"get",
              data:{'id':id},
              url:"<?=Url::toRoute('index/get-address')?>",
              success:function(msg){
                  obj = jQuery.parseJSON(msg);
                  $('#city').html('<option value="">市</option>');
                  $.each(obj,function(index,item){
                      $('#city').append('<option value="'+item.region_id+'">'+item.region_name+'</option>');
                  })
                  $('#area').html('<option value="">区</option>');

              }
          })
      });
      $("#city").change(function(){
         var id = $(this).val();
          $.ajax({
              type:"get",
              data:{'id':id},
              url:"<?=Url::toRoute('index/get-address')?>",
              success:function(msg){
                  obj = jQuery.parseJSON(msg);
                  $('#area').html('<option value="">区</option>');
                  $.each(obj,function(index,item){
                      $('#area').append('<option value="'+item.region_id+'">'+item.region_name+'</option>');
                  })
              }
          })
      });
  })


    $('#submit').click(function() {
           $.ajax({ 
                type:'post',
                data:{'code':yzm,'tel':tel},
                url:'<?=Url::toRoute('cardapply/only-code')?>',
                success:function(data){
                    if(data==100){ 
                         $("#form").submit();
                    }else if(data==200){
                        alert('验证码错误')
                        return false;
                    }
                }

            })
    });

</script>

<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
