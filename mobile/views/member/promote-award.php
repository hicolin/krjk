<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>

<!--nav-->
<div class="tuiguang_nav">
    <div class="tn_head">
        <a href="">
            <i class="iconfont icon-zhanghu1"></i>131****5869
        </a>
    </div>
    <div class="tn_con">
        <span>累计赚取（元）</span><br/>
        <p class="mon_num">0.00</p>
    </div>
</div>
<!--nav end-->


<!--main-->
<div class="tuiguang_main">
    <ul>
        <li>
            <a href=""  style="border-right: 1px solid #eee;">
                <span>推广奖励（元）</span><br/>
                <em>0.00</em>
            </a>
        </li>
        <li>
            <a href=""  style="border-right: 1px solid #eee;">
                <span>二级贷奖励（元）</span><br/>
                <em>0.00</em>
            </a>
        </li>
        <div class="clear"></div>
    </ul>

</div>
<!--main end-->


<!--tui_bot-->
<div class="tui_bot">
    <div class="tuib_top">
        <ul>
            <li class="curr"><span>关注成功</span></li>
            <li><span>购买成功</span></li>
            <div class="clear"></div>
        </ul>
    </div>
    <div class="tuib_con">
        <div class="tuib_son">
            <ul>
                <li>
                    <div class="tuib_son_li fl">
                        <span>2017-10-08</span>
                    </div>
                    <div class="tuib_son_li fl">
                        <span>关耳郑</span>
                    </div>
                    <div class="clear"></div>
                </li>
                <li>
                    <div class="tuib_son_li fl">
                        <span>2017-10-08</span>
                    </div>
                    <div class="tuib_son_li fl">
                        <span>关耳郑</span>
                    </div>
                    <div class="clear"></div>
                </li>
                <li>
                    <div class="tuib_son_li fl">
                        <span>2017-10-08</span>
                    </div>
                    <div class="tuib_son_li fl">
                        <span>关耳郑</span>
                    </div>
                    <div class="clear"></div>
                </li>
                <li>
                    <div class="tuib_son_li fl">
                        <span>2017-10-08</span>
                    </div>
                    <div class="tuib_son_li fl">
                        <span>关耳郑</span>
                    </div>
                    <div class="clear"></div>
                </li>
                <li>
                    <div class="tuib_son_li fl">
                        <span>2017-10-08</span>
                    </div>
                    <div class="tuib_son_li fl">
                        <span>关耳郑</span>
                    </div>
                    <div class="clear"></div>
                </li>
            </ul>



        </div>

        <div class="tuib_son hiddendiv">
            <ul>
                <li>
                    <div class="tuib_son_li fl">
                        <span>2017-10-10</span>
                    </div>
                    <div class="tuib_son_li fl">
                        <span>关耳郑</span>
                    </div>
                    <div class="clear"></div>
                </li>
                <li>
                    <div class="tuib_son_li fl">
                        <span>2017-10-18</span>
                    </div>
                    <div class="tuib_son_li fl">
                        <span>关耳郑</span>
                    </div>
                    <div class="clear"></div>
                </li>
                <li>
                    <div class="tuib_son_li fl">
                        <span>2017-10-08</span>
                    </div>
                    <div class="tuib_son_li fl">
                        <span>关耳郑</span>
                    </div>
                    <div class="clear"></div>
                </li>
                <li>
                    <div class="tuib_son_li fl">
                        <span>2017-10-08</span>
                    </div>
                    <div class="tuib_son_li fl">
                        <span>关耳郑</span>
                    </div>
                    <div class="clear"></div>
                </li>
                <li>
                    <div class="tuib_son_li fl">
                        <span>2017-10-08</span>
                    </div>
                    <div class="tuib_son_li fl">
                        <span>关耳郑</span>
                    </div>
                    <div class="clear"></div>
                </li>
            </ul>



        </div>

    </div>

</div>
<!--tui_bot end-->




<script>
    //    适应不同屏幕
    /*window.onload=function(){
        document.documentElement.style.fontSize= document.documentElement.clientWidth/16+'px' ;
    };
*/


    //    切换
    $(function(){
        $(".tuib_top li").click(function(){
            $(this).addClass("curr").siblings().removeClass("curr");
            $('.tuib_son').hide().eq($(this).index()).show();
        })
    });

</script>

<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
