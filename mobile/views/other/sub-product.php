<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<style>
    .apply_foot .close{position: absolute;top: 0;right: 5px;font-size: 25px;color:#333;}
    .apply-now{width: 100%;border-radius: 3px;background-color: rgb(51, 170, 255);border:none;padding: 10px;color:#fff;position: fixed;left: 0;bottom: 0;font-size: 16px}
    .tip-red{color:red;margin-top: 0.1rem}
    .agreeBaoXian{display: flex;padding: 0.4rem 0;}
    .agreeBaoXian p{font-size: 0.6rem;color: #999;line-height: 0.8rem;}
    .bxIconBox{margin-right: 0.2rem;padding-top: 0.1rem;}
    .bxIconBox i{font-size: 0.64rem;}
    .bxIconBox i:nth-child(2){color: #4199fb};
</style>
<?php $this->endBlock(); ?>
<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>

<!--top-->
<div class="apply_top">

    <div class="apply_top_head">
        <img src="<?=$model->logo?>"/><br/>
        <span><?=$model->title?></span>
    </div>

    <div class="apply_top_li">
        <ul>
            <li>
                <a href="">
                    <span>最低日利率</span>
                    <i class="fr"> <?=$model->interest?>%</i>
                </a>
            </li>
            <li>
                <a href="">
                    <span>贷款范围</span>
                    <i class="fr"><?=$model->range?></i>
                </a>
            </li>
            <div class="clear"></div>
        </ul>
    </div>

    <div class="apply_top_li">
        <ul>
            <li>
                <a href="">
                    <span>还款方式</span>
                    <em class="fr"><?=$model->hk_way?></em>
                </a>
            </li>
            <li>
                <a href="">
                    <span>贷款期限</span>
                    <em class="fr"><?=$model->time_limit?></em>
                </a>
            </li>
            <div class="clear"></div>
        </ul>

    </div>

</div>
<!--top end-->

<style type="text/css">
    .person_foot{
        display: none;
    }
</style>
<!--nav-->
<div class="apply_nav">
    <div class="apply_nav_li" style="border-bottom: 1px solid #eee;">
        <div class="anl_left fl">
            <i class="iconfont icon-laba"></i>
        </div>
        <div class="anl_right fl">
            <div id="scrollDiv" class="scrollDiv">
                <ul>
                    <li>
                        <span>已放款<i><?=$apply_num?></i>人</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="apply_nav_li">
        <div class="anl_left fl">
            <i class="iconfont icon-qiandai1" style="color: #4199fb;"></i>
        </div>
        <div class="anl_right fl">
            <div id="scrollDiv2" class="scrollDiv">
                <ul>
                    <li>
                        <span>刘**先生已放款12000元 <em>1分钟前</em></span>
                    </li>
                    <li>
                        <span>张**先生已放款5000元 <em>5分钟前</em></span>
                    </li>
                    <li>
                        <span>王**先生已放款3000元 <em>3分钟前</em></span>
                    </li>
                    <li>
                        <span>朱**先生已放款1000元 <em>2分钟前</em></span>
                    </li>
                </ul>
            </div>
            <script type="text/javascript">   function AutoScroll(obj){   $(obj).find("ul:first").animate({   marginTop:"-1.5rem"   },500,function(){   $(this).css({marginTop:"0rem"}).find("li:first").appendTo(this);   });   }   $(document).ready(function(){   setInterval('AutoScroll("#scrollDiv2")',4000);   });   </script>
        </div>
        <div class="clear"></div>
    </div>
</div>
<!--nav end-->

<!--main-->
<div class="apply_main" style="padding-bottom: 90px;">
    <?=$model->apply_detail?>
</div>
<!--main end-->

<!--foot -->
<div class="apply_foot_zzc" style="display: none;">
    <div class="apply_foot">
        <div class="shop_name"><?= PublicController::getSysInfo(14) ?></div>
        <form method="post" onsubmit="return apply_check()">
            <div class="close">×</div>
            <div class="apply_foot_left" >
                <ul>
                    <li>
                        <i class="iconfont icon-rengezhongxin"></i>
                        <input name="name" type="text" class="apply_name" placeholder="请输入姓名">
                    </li>
                    <li>
                        <i class="iconfont icon-shouji"></i>
                        <input name="tel" type="text" class="apply_tel" placeholder="请输入您的手机号">
                    </li>
                    <li class="idCardLi" style="display: none;">
                        <i class="iconfont icon-haibao"></i>
                        <input name="id_card" type="text" class="apply_id_card" placeholder="请输入您的身份证号码">
                    </li>

                    <div class="agreeBaoXian">
                        <div class="bxIconBox">
                            <i class="iconfont icon-yuan"></i>
                            <i class="iconfont icon-xuanzhong1" style="display: none;"></i>
                        </div>
                        <p>
                            领取100万意外险。本人同意保险公司后续致电，确认投保状态及相关，查看<a href="<?= Url::to(['site/article-detail', 'id' => 248]) ?>">《领取说明及保障条款》</a>
                        </p>
                    </div>

                    <p class="tip-red">* 注：请务必填写真实信息，否则会影响申请的下款。</p>
                    <input type="hidden" name="uid" value="<?=$uid?>">
                    <input type="hidden" name="pid" value="<?=$model->id?>">
                    <input type="hidden" name="sign"  value="<?=isset($sign)&&!empty($sign)?$sign:''?>">
                </ul>
            </div>
            <div class="apply_foot_right">
                <button>立即申请</button>
            </div>
            <div class="clear"></div>
        </form>
    </div>
</div>

<button class="apply-now">立即申请</button>
<!--foot end-->
<script>
    var api_switch = '<?= PublicController::getSysInfo(40) ?>';
    var isChooseIdCard = parseInt(api_switch);
    $(function () {
       if(api_switch == 1){
           $(".bxIconBox i:nth-child(2)").show().siblings().hide();
           $(".idCardLi").show();
       }else{
           $(".bxIconBox i:nth-child(1)").show().siblings().hide();
           $(".idCardLi").hide();
       }
    });

    //申请部分身份证部分的显示隐藏
    $(".bxIconBox i").click(function () {
        $(this).hide().siblings().show();
        var _index = $(this).index();
       if(_index == 0){
           $(".idCardLi").fadeIn();
           isChooseIdCard = 1;
       }else{
           $(".idCardLi").fadeOut();
           isChooseIdCard = 0;
       }
    });

    //验证
    function apply_name(){
        var str=$(".apply_name").val();
        var reg=/^[\u4E00-\u9FA5A-Za-z]{2,10}$/;
        if(!reg.test(str)){
            layer.tips('请输入正确姓名', ".apply_name", {tips:3,time:2000});
            return false;
        }
        else{return true;}
    }

    function apply_tel(){
        var str=$(".apply_tel").val();
        var reg=/^1(3|4|5|7|8)\d{9}$/;
        if(!reg.test(str)){
            layer.tips('请输入正确的手机号', ".apply_tel", {tips:3,time:2000});
            return false;
        }
        else{return true;}
    }

    function apply_id_card() {
        var id_card = $('.apply_id_card').val();
        var reg = /^\d{18}|\d{17}x$/i;
        if (!reg.test(id_card)) {
            layer.tips('请输入正确的身份证号码', '.apply_id_card', {tips: 3, time: 2000});
            return false;
        }
        return true;
    }

    function apply_check() {
        // todo 检查协议是否开启,开启则进行验证
        if(isChooseIdCard == 1){//选中身份证验证
            if(apply_name()&&apply_tel()&&apply_id_card()){
                layer.msg('正在提交，请稍后。。。', {time:2500,icon: 16,shade: 0.4});
                return true;
            }else{
                return false;
            }
        }else{//未选中身份证验证
            if(apply_name()&&apply_tel()){
                layer.msg('正在提交，请稍后。。。', {time:2500,icon: 16,shade: 0.4});
                return true;
            }else{
                return false;
            }
        }
    }

</script>

<?php $this->beginBlock('footer'); ?>
<script>
    // 显隐切换
    $('.apply-now').click(function () {
        $(this).hide();
        $('.apply_foot_zzc').show();
    });
    $('.close').click(function () {
        $('.apply_foot_zzc').hide();
        $('.apply-now').show();
    });
</script>
<?php $this->endBlock(); ?>
