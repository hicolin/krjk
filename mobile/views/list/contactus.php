<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>

<style type="text/css">
    .header{height: 1.5rem;line-height: 1.5rem;background: #f8f8f8;width: 100%;position: relative;text-align: center;}
.header a.back2{font-size: 0.6rem;color: #33aaff;display: block;position: absolute;left: 2%;top: 0;}
.header a.back2 i{font-size: 0.6rem;color: #33aaff;}
.header span{font-size: 0.6rem;color: #888888;}
.header img{width: 1rem;position: absolute;right: 2%;top: 0.25rem;}
.menu{width: 100%;background: #fff;}
.menu li{width: 25%;padding: 0.8rem 0;text-align: center;float: left;}
.menu li a{display: block;}
.menu li img{display: block;width: 0.8rem;margin: 0.2rem auto;height: 1.0rem;}
.menu li span{font-size: 0.6rem;color: #333;}


.index_main{margin-top: 0.5rem;background: #fff;}
.index_main_top{height: 2rem;border-bottom: 1px solid #eee;}
.index_main_top li{width: 50%;text-align: center;float: left;height: 100%;line-height: 2rem;}
.index_main_top li a{height: 100%;display: inline-block;font-size: 0.6rem;color: #333;padding: 0 1rem;}
.index_main_top li.curr a{font-size: 0.7rem;color: #33aaff;border-bottom: 2px solid #33aaff;}


.index_main_con{}
.index_main_con li{padding: 0.7rem 0.4rem;border-bottom: 1px solid #eee;position: relative;}
.index_main_con li img{width: 2rem;margin-right: 0.3rem;}
.index_main_con li p.imcl1{height: 1.1rem;margin-top: 0.1rem;}
.index_main_con li p span{font-size: 0.7rem;}
.index_main_con li p i{font-size: 0.5rem;border: 1px solid #ff6000;color: #ff6000;padding: 0.1rem 0.2rem;border-radius: 0.1rem;margin-left: 0.2rem;}
.index_main_con li p em{font-size: 0.6rem;margin-right: 0.2rem;color: #999;}
.index_main_con li p strong{font-size: 0.6rem;margin-right: 0.2rem;color: #ff4807;}
.index_main_con li a{font-size: 0.6rem;color: #fff;background: #ff6000;position: absolute;right: 0.4rem;top: 0.7rem;padding: 0.2rem 0.4rem;}
.index_main_con li h2{font-size: 0.5rem;color: #999;}
.index_main_con li h2 i{font-size: 0.5rem;color: #33aaff;}

.jiazai_more,.jiazai_nomore{ float:left; width:100%; text-align:center; height:1.5rem; line-height:1.5rem;}
.jiazai_more a{ display:inline-block; color:#666;font-size: 0.6rem;}
.jiazai_nomore{ display:none;}
.jiazai_nomore a{ display:inline-block; color:#666;font-size: 0.6rem;}
@charset "utf-8";

/* 全局样式 */
*{ outline:medium; box-sizing:border-box;margin:0px auto;}
html{height:100%; font-size: 10px;}
body{font-family:"Microsoft Yahei","Verdana, Geneva, sans-serif";line-height:1; margin:0; padding:0; color:#333; width:100%;font-size: 62.5%;background: #f3f3f3;}
div,form{margin:0 auto; padding:0;}
ul,ol,ol li,ul li,li,dl,dt,dd,form,img,p,form{margin:0; padding:0; border:none; list-style-type:none; vertical-align:top;}
input,select,textarea { margin:0; padding: 0; outline:0; color:#333; font-family:Microsoft Yahei;}
ul,ol,ol li,ul li,li{ list-style: none;}
input{ border-radius:0;}
input[type="button"] { border: 0; cursor: pointer; text-align: center;}
input[type="button"], input[type="submit"], input[type="reset"] {-webkit-appearance: none;}
textarea { -webkit-appearance: none;}
em,i,u,ins,strong{ font-style:normal; text-decoration:none;}
div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, font, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption,tbody, tfoot, thead, tr, th, td {margin:0;padding:0;border:0;outline:0;vertical-align:baseline; word-wrap:break-word; word-break:break-all;}
h1,h2,h3,h4,h5,h6 { font-style: normal; font-weight: normal; font-variant: normal; }
a { color: #333; text-decoration: none; cursor:pointer;transition:all 0.2s linear 0s;}
a:visited { text-decoration: none; }
a:hover { color: #0CBD70; text-decoration: none;}
a:active { color:#666;}

/*移动端调用软键盘搜索键的input样式*/
input[type="search"]{-webkit-appearance:none;appearance:none;}
input::-webkit-search-cancel-button {display: none;}


.fl{ float:left;}
.fr{ float:right;}
.pr{ position:relative;}
.pa{ position:absolute;}
.pf{ position:fixed;}
.tl{ text-align:left;}
.tc{ text-align:center;}
.tr{ text-align:right;}
.f12{ font-size:12px;}
.f14{ font-size:14px;}
.f16{ font-size:16px;}
.f18{ font-size:18px;}
.f20{ font-size:20px;}
.f24{ font-size:24px;}
.f30{ font-size:30px;}
.c-green{color:#0CBD70;}
.w100{ width:100%; float: left;}
.w100-b{ width:100%; float: left; background: #fff;}
.w1160{ width:1160px; min-width: 1160px; margin:0 auto;}
.w1200{ width:1200px; min-width: 1200px; margin:0 auto;}
.w280 { width:280px;}
.w860 { width:860px;}
.w900 { width:900px;}
.w600 { width:600px; margin:0 auto;}
.margin { margin:0 auto;}
.ml5{ margin-left: 5px;}
.ml10{ margin-left: 10px;}
.ml15{ margin-left: 15px;}
.ml20{ margin-left:20px;}
.ml30{ margin-left:30px;}
.mr5{margin-right: 5px;}
.mr10{margin-right: 10px;}
.mr15{margin-right: 15px;}
.mr20{margin-right: 20px;}
.mr30{margin-right: 30px;}
.mt5{ margin-top: 5px; }
.mt10{ margin-top: 10px; }
.mt15{ margin-top: 15px; }
.mt20{ margin-top: 20px; }
.mt30{ margin-top: 30px; }
.mt40{ margin-top: 40px; }
.mb5{margin-bottom: 5px;}
.mb10{ margin-bottom: 10px; }
.mb15{ margin-bottom: 15px; }
.mb20{ margin-bottom: 20px; }
.mb30{ margin-bottom: 30px; }
.pt10{ padding-top: 10px;}
.pt15{ padding-top: 15px;}
.pt20{ padding-top: 20px;}
.pb10{ padding-bottom: 10px;}
.pb15{ padding-bottom: 15px;}
.pb20{ padding-bottom: 20px;}
.pl10{ padding-left: 10px;}
.pr10{ padding-right: 10px;}
.pa10{ padding:10px;}
.pa20{ padding:20px;}
.bg_eee{background: #eee;}
.bg_f2f2f2{background: #f2f2f2;}
.bg_white{background: #fff;}
.clear{ clear:both; height:0;}
.cursor{ cursor:pointer;}
.over { overflow:hidden;}
.border { border:1px solid #e2e2e2;}
.hiddendiv { display: none;}
.ellipsis{overflow:hidden;white-space: nowrap;text-overflow: ellipsis;}

/*移动端最大宽度*/
.content_w { margin: 0 auto; width:100%; max-width: 640px;}

/*设置placeholder颜色*/
:-moz-placeholder { color: #ccc;}
::-moz-placeholder { color: #ccc;}
input:-ms-input-placeholder,textarea:-ms-input-placeholder{color: #ccc;}
input::-webkit-input-placeholder,textarea::-webkit-input-placeholder{color: #ccc;}


/*foot*/
.foot{background: #f2f2f2;padding: 0.4rem 0;border-top: 1px solid #ccc;position: fixed;bottom: 0;left: 0;width: 100%;z-index: 100000;}
.foot li{width: 20%;float: left;text-align: center;}
.foot li a{display: block;}
.foot li i{display: block;color: #919191;height: 1.1rem;font-size: 0.9rem;}
.foot li span{color: #919191;font-size: 0.6rem;}
.foot li.curr i{color: #9a8a0f;}
.foot li.curr span{color: #9a8a0f;}


/*main8*/
.main8{margin-top: 0.4rem;background: #f2f2f2;padding: 0.6rem 0;padding-bottom: 3rem;}
.main8 p{text-align: center;}
.main8 p span{font-size: 0.6rem;color: #888;line-height: 0.8rem;}
.index_main {
    margin-top: 2.5rem;
    background: #fff;
}
@media only screen and (min-width: 320px){html {font-size: 20px; !important;}}
@media only screen and (min-width: 540px){html {font-size: 25px; !important;}}
</style>
<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
<div class="index_main">
    <div class="index_main_con" >
        <ul>
<!--                 <li>
                    <p class="imcl1"><i></i></p>
                     <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin={$vo.value}&site=qq&menu=yes">联系客服</a>
                    <div class="clear"></div>
                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=&site=qq&menu=yes">联系客服</a>
                </li> -->
            <?php foreach ($kefu2 as $key => $value) { ?>
                <li>
                <p class="imcl1"><i><?=$value?></i></p>
                 <!-- <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin={$vo.value}&site=qq&menu=yes">联系客服</a> -->
                <div class="clear"></div>
                 <!-- <a target="_blank" href="mqqwpa://im/chat?chat_type=wpa&uin=<?=$value?>&version=1&src_type=web&web_src=qq.com"> -->
                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?=$value?>&site=qq&menu=yes">
                   联系客服
                </a>
             </li>
            <?php } ?>
        </ul>
    </div>

</div>


</body>
</html>
