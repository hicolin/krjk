<?php
use yii\helpers\Url;
use common\controllers\PublicController;

?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
<!--menu-->
<div class="loan_nav">
    <div class="nkm_top_left fl">
        <i class="iconfont icon-qiandai1"></i>
    </div>
    <div class="nkm_top_right fl">
        <div id="scrollDiv" class="scrollDiv">
            <ul>
                <?php
                foreach ($dai_list as $list) { ?>
                    <li>
                        <span><?=substr_replace($list->tel,'****',3,4)?> 刚在<i><?=$list->p_name?></i>成功借款<i><?=$list->money?>元</i></span>
                    </li>
                <?php }
                ?>
            </ul>
        </div>
        <script type="text/javascript">   function AutoScroll(obj) {
                $(obj).find("ul:first").animate({marginTop: "-1.5rem"}, 500, function () {
                    $(this).css({marginTop: "0rem"}).find("li:first").appendTo(this);
                });
            }
            $(document).ready(function () {
                setInterval('AutoScroll("#scrollDiv")', 3000);
            });   </script>

    </div>
    <div class="clear"></div>
</div>

<!--menu end-->


<!--main-->
<div class="loan_main">

    <div class="loan_main_top">
        <ul>
            <li>
                <a href="<?= Url::toRoute(['list/bank-loan', 'type' => 3]) ?>">热门推荐</a>
            </li>
            <li>
                <a href="<?= Url::toRoute(['list/bank-loan', 'type' => 2]) ?>">银行快贷</a>
            </li>
            <li>
                <a href="<?= Url::toRoute(['list/bank-loan', 'type' => 1]) ?>">信用卡贷</a>
            </li>
            <div class="clear"></div>
        </ul>

    </div>

    <div class="index_main_con">
        <ul>

            <?php foreach ($creditcard as $value): ?>

                <a onclick="loan('<?= $value->id ?>')" id="load-<?=$value->id?>" link="<?=$value->links?>">
                <li>
                    <img src="<?= $value->logo ?>" class="fl"/>
                    <p class="imcl1"><span><?= PublicController::substr($value->title, 8) ?></span><strong class="fr"><?= $value->price ?>万元</strong>
                    </p>
                    <h2 class="imcl2"><em><?= $value->beizhu?></em><em class="fr">通过率：<i><?= $value->rate ?>%</i></em>
                    </h2>
                    <div class="clear"></div>
                </li>
                </a>
            <?php endforeach ?>


        </ul>
        <div class="jiazai_more" style="display: none"><a href="javascript:;">加载更多</a></div>
        <div class="jiazai_nomore"><a href="javascript:;">没有更多了哦</a></div>

    </div>

</div>
<!--main end-->


<script>
    //    适应不同屏幕
   /* window.onload = function () {
        document.documentElement.style.fontSize = document.documentElement.clientWidth / 16 + 'px';
    };*/

    // js获取GET参数
    function getQueryVariable(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split("=");
            if (pair[0] == variable) {
                return pair[1];
            }
        }
        return (false);
    }

    /*加载更多*/
    var page = 0;
    $(window).scroll(function () {
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        if ((scrollTop + windowHeight >= scrollHeight && $(".jiazai_more").css("display") == "block") || !page) {
            $(".jiazai_more").show();
            Load();
        }
    });

    function Load() {
       
        var type = getQueryVariable("type");
        setTimeout(function () {
            page++;
            $.ajax({
                url: "<?= Url::toRoute(['list/bank-more'])?>",
                type: 'get',
                data: {'page': page, 'type': type},
                dataType: 'text',
                success: function (data) {
                    if (data) {
                        $(".index_main_con ul").append(data);
                    } else {
                        $(".jiazai_more").hide().next().show();
                    }
                }
            });
        }, 1000);
    }


    function loan(id){
         var links=$('#load-'+id).attr('link');
        
       $.ajax({
            type:"GET",
            url :"<?=Url::toRoute('list/load-permission')?>",
            data:{"id":id},
            success:function(data){
                if(data==100){
                      window.location.href=""+links+"";
                }else if(data==200){
                    layer.confirm('您还未登录', {
                        btn: ['确定'] //按钮
                    }, function(){
                         window.location.href="#";
                    }, function(e){
                        layer.close(e);
                        return false;
                    });
                }
                else if(data==300){  
                       window.location.href=""+links+"";
                } else if(data==400){
                    layer.confirm('没有权限查看？您未开通会员资格！点击确定即可开通会员', {
                        btn: ['确定'] //按钮
                    }, function(){
                       window.location.href="<?=Url::toRoute('list/buy-agent')?>";
                    }, function(e){
                        layer.close(e);
                        return false;
                    });

                }                 
                
            
            }


       })



    }
</script>
<script>
    $(".loan_main_top ul li a").each(function () {
        if ($(this)[0].href == String(window.location) && $(this).attr('href') != "") {
            $(this).parent('li').addClass("curr");
        }
    });
</script>


<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
