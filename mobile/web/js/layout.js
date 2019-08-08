// +----------------------------------------------------------------------
// | desc: global js
// +----------------------------------------------------------------------
// | Created By 2016-10-14
// +----------------------------------------------------------------------
// | Author: zhangliang 
// +----------------------------------------------------------------------

/**
 *desc:初始化加载
 *@param void;
 *@return void;
 */
$(function(){
  //重置rem,1rem=100px
  wap.changeSize();
});

/**
 *desc:构造函数 
 *@param void;
 *@return void;
 */
function Wap(){};

 /**
 *desc:窗口大小改变执行
 *@param void;
 *@return void;
 */
 Wap.prototype.changeSize=function(){
   //重置rem,1rem=100px
   wap.mobileAnswer();
   $(window).resize(function(){
      wap.mobileAnswer();
   });
 };

 /**
 *desc:重置rem,1rem=100px
 *@param void;
 *@return void;
 */
 Wap.prototype.mobileAnswer=function(){
	 var deviceWidth = document.documentElement.clientWidth;
     if(deviceWidth > 640){
         deviceWidth = 640;
     }
     $("html").css("font-size",deviceWidth / 6.4 + 'px');
 };



 Wap.prototype.radis=function(obj,val_obj){
   $(obj).parent().find("em").removeClass("on");
   $(obj).addClass("on");
   $(val_obj).val( $(obj).attr("data"));
}

 Wap.prototype.toggledd=function(obj){
    $(".block7 dd").hide();
    $(obj).parent().find("dd").slideToggle();
 };

 Wap.prototype.xutiantoggle=function(obj){
    $(".xuandian").toggle();
    $(obj).toggleClass("on");
 };



 /**
 *desc:保存数据
 *@param void;
 *@return void;
 */
 Wap.prototype.subimt=function(){
   var $name=$.trim($("#name").val());
   var $nian = $("#nian").val();
   var $email = $("#email").val();
   if($name.length<1){
      alert("姓名不能为空，请先填写姓名");
	  return false;
   }
   if($nian.replace(new RegExp(/[ 　]/g),"").length <1){
      alert("请选择出生日期");
	  return false;
   }
   if($email.replace(new RegExp(/[ 　]/g),"").length <1){
      alert("请填写邮箱，用于接收起名结果");
	  return false;
   }
   var address = $("#area").text();
   if(address!=""){
   //更新地址
   $("#sm_address").val(address);
   }
   $("#qm_form").submit();
 };



/**
 *desc:构造函数实例化
 */
var wap = new Wap();