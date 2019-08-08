<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
<style>
    .invite_main{background: #fff;}
    .invite_main_nav{}
    .invite_main_nav li{width: 25%;float: left;text-align: center;}
    .invite_main_nav li a{font-size: 0.6rem;padding: 0 0.2rem;line-height: 1.5rem;display: inline-block;}
    .invite_main_nav li.curr a{color: #33aaff;border-bottom: 1px solid #33aaff;}

    .invite_main_con{width: 100%;}
    .invite_main_con table{width: 100%;text-align: center;border-collapse: collapse; min-height: 1.8rem; line-height: 1.8rem;}
    .invite_main_con table tr{}
    .invite_main_con table tr td{background: #fff;color: #666;font-size: 0.6rem;border-bottom: 1px solid #f0f0f0;overflow:hidden;white-space: nowrap;text-overflow: ellipsis;}
    .invite_main_con table tr:first-child{height: 2rem;line-height: 2rem;}
    .invite_main_con table tr:first-child td{background: #f2f2f2;font-size: 0.6rem;color: #333;}
    .invite_main_con table tr td{font-size: 0.6rem;color: #888;}
    .person_foot{display: none}
</style>
<div class="invite_main">
    <div class="invite_main_con">
        <table>
            <tr>
                <td>代理产品</td>
                <td>用户/级</td>
                <td>发放工资/￥</td>
                <td>发放时间</td>
            </tr>
            <?php
            foreach ($model as $list) { ?>
                <tr>
                    <td><?=$list->p_name?></td>
                    <td><?= \mobile\controllers\Service::dealTrader($list->jy_user_info) ?></td>
                    <td><?= $list->commission_money ?></td>
                    <td><?=date('Y-m-d',$list->created_time)?></td>
                </tr>
            <?php }
            ?>
        </table>
        <div class="jiazai_more" style="display: none"><a href="javascript:;">加载更多</a></div>
        <div class="jiazai_nomore"><a href="javascript:;">没有更多了哦</a></div>
    </div>

</div>
<!--main end-->
<script>
    /*加载更多*/
    var page=1;
    var show = true;
    $(window).scroll(function(){
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        if(scrollTop + windowHeight >= scrollHeight && show){
            $(".jiazai_more").show();
            Load();
        }
    });
    function Load() {
        show = false;
        //var cat_id = getQueryVariable("cat_id");
        setTimeout(function () {
            page++;
            $.ajax({
                url  : "<?= Url::toRoute(['member/dai-commission'])?>",
                type : 'post',
                data : {'page':page},
                dataType:'text',
                success:function(data){
                    if(data){
                        show = true;
                        $(".invite_main_con table").append(data);
                    }else{
                        show = false;
                        $(".jiazai_more").hide().next().show();
                    }
                }
            });
        },1000);
    }

    $(".invite_main_nav ul li a").each(function(){
        if ($(this)[0].href == String(window.location) && $(this).attr('href')!="") {
            $(this).parent('li').addClass("curr");
        }
    });
</script>
<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
