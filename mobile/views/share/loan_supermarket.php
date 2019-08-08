<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header');?>
<style>
    body{background:#F3F3F3;margin-bottom: 0 !important;}
    .scrollDiv li{ overflow:hidden;}
    <!--  .scrollDiv li img{width: 20px;vertical-align: middle;margin-top: -3px;margin-right: 3px;}-->
    .scrollDiv li i{color: #e35805;}
    .market_nav{line-height: 1.5rem;}
    .market_nav h1{font-size: 0.6rem;color: #999;text-align: center;}
    .market_nav h1 i{color: #333333;}
    .market_banner img{width: 100%;}
    .market_list{ background:#FFF}
    .market_list>h1{line-height: 2rem;border-bottom: 1px solid #F3F3F3;font-size: 0.75rem;color: #333;padding: 0 2%;}
    .market_list h1 img{width: 0.75rem;vertical-align: middle;margin-top: -3px;margin-right: 3px;}
    .index_main_con img.hot{width: 1.48rem;position: absolute;right:1.6rem;  top:0;}
    .index_main_con li a {position: relative;}
    .person_foot{display: none;}

    .user-info{height: 150px;background-color: #00a0e9;color:#fff}
    .avatar{text-align: center}
    .avatar img{width: 60px;height: 60px;border-radius: 50%;margin-top: 30px}
    .user-info p{text-align: center;margin-top: 20px}
</style>
<?php $this->endBlock();?>
<body>
<div class="user-info">
    <div class="avatar">
        <img src="<?= $member->pic?>" alt="">
    </div>
    <p><b><?= $member->nickname?>的店铺</b></p>
</div>
<div class="new_kou_main">
    <div class="nkm_top">
        <div class="nkm_top_right fl">
            <div id="scrollDiv" class="scrollDiv">
                <ul>
                    <?php foreach ($account as $key => $value) { ?>
                        <li>
                            <div class="nkm_top_left fl">
                            <i class="iconfont icon-laba"></i>
                        </div>
                        <span>用户<?=substr_replace($value->tel, '****', 3, 4)?> 在<?=$value->p_name?> 成功借款<i><?=$value->money?></i></span>
                        </li>  
                    <?php } ?>
                </ul>
            </div>
            <script type="text/javascript">
                function AutoScroll(obj) {
                    $(obj).find("ul:first").animate({marginTop: "-1.5rem"}, 500, function () {
                        $(this).css({marginTop: "0rem"}).find("li:first").appendTo(this);
                    });
                }
                $(document).ready(function () {
                    setInterval('AutoScroll("#scrollDiv")', 3000);
                });
            </script>
        </div>
        <div class="clear"></div>
    </div>
    <div class="market_nav">
    </div>
    <div class="market_list" style="margin-top: 5px;padding-bottom: 20px">
        <h1><img src="<?= Url::base() ?>/mobile/web/images/mar_icon1.png"/>极速贷款</h1>
        <div class="index_main_con">
            <ul>
                <?php foreach ($dai_product as $value): ?>
                        <li>
                            <a class="agent.html"  onClick="join('<?= $value->id ?>','1')">
                                <img class="lazyload" data-original="<?= $value->logo ?>"/>
                                <h1><?= PublicController::substr($value->title, 6) ?></h1>
                                <p>
                                    <em>额度:<em style="color: #33aaff"><?= $value->range ?></em></em><br>
                                    <em>月利率:<em style="color: #33aaff;"><?=$value->interest?>%</em></em>
                                </p>
                                <?php if($value->is_hot==1){ ?>
                                    <img src="<?= Url::base() ?>/mobile/web/images/hot.png" class="hot"/>
                                <?php }   ?>
                            </a>
                        </li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
</div>
<!--main end-->
<div style="height: 100px"></div>
<?php $this->beginBlock('footer');?>
<script>
    function join(pid, type) {
        var user_id = '<?=$user_id?>';
        var sign = 1;
        var ajaxData = {'pid': pid, 'uid': user_id, 'sign': sign};
        $.ajax({
            url: '<?=Url::toRoute('share/agentmsg')?>',
            type: 'get',
            data: ajaxData,
            dataType: 'text',
            cache: false,
            async: false,
            success: function (data) {
                if (data == "100") {
                    window.location.href = "<?= Url::to(['other/sub-product'])?>" + '?pid=' + pid + "&type=" + type + "&uid=" + user_id;
                }
            },
        });
    }
</script>
<?php $this->endBlock();?>
