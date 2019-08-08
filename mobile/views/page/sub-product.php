<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>

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
<div class="apply_foot">
    <form method="post" onsubmit="return apply_check()">
        <div class="apply_foot_left fl" >
            <ul>
                <li>
                    <i class="iconfont icon-rengezhongxin"></i>
                    <input name="name" type="text" class="apply_name" placeholder="请输入姓名">
                </li>
                <li>
                    <i class="iconfont icon-shouji"></i>
                    <input name="tel" type="text" class="apply_tel" placeholder="请输入您的手机号">
                </li>
                <input type="hidden" name="uid" value="<?=$uid?>">
                <input type="hidden" name="pid" value="<?=$model->id?>">
            </ul>
        </div>
        <div class="apply_foot_right fr">
            <button>立即<br/> 申请</button>
        </div>
        <div class="clear"></div>
    </form>
</div>
<!--foot end-->
<script>
    //    适应不同屏幕
    /*window.onload=function(){
        document.documentElement.style.fontSize= document.documentElement.clientWidth/16+'px' ;
    };*/
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

    function apply_check() {
        if(apply_name()&&apply_tel()){
            return true;
        }else{
            return false;
        }
    }

</script>

<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
