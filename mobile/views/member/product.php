<?php
use yii\helpers\Url;
use common\controllers\PublicController;

?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>


<!--menu-->
<div class="menu">
    <ul>
        <li>
            <a href="">
                <img src="<?= Url::base() ?>/mobile/web/images/it1.png"/>
                <span>全部产品</span>
            </a>
        </li>
        <li>
            <a onclick="customer('<?= $user_info->agent ?>')">
                <img src="<?= Url::base() ?>/mobile/web/images/it2.png"/>
                <span>客户信息</span>
            </a>
        </li>
        <li>
            <a href="<?= Url::toRoute('member/rank-list') ?>">
                <img src="<?= Url::base() ?>/mobile/web/images/it3.png"/>
                <span>排行榜</span>
            </a>
        </li>
        <li>
            <a href="<?= Url::toRoute('member/account') ?>">
                <img src="<?= Url::base() ?>/mobile/web/images/it4.png"/>
                <span>我要提现</span>
            </a>
        </li>
        <div class="clear"></div>
    </ul>
</div>
<!--menu end-->
<?php  if(Yii::$app->session['user_id']){ ?> 
    <div class="pro_banner">
        <a onclick="share('<?= $user_info->agent ?>')">
            <img src="<?= Url::base() ?>/mobile/web/images/pro_list_banner.png" style="width: 100%;" />
        </a>
    </div>
<?php }else{ ?>

        <div class="pro_banner">
        <a onclick="join1()">
            <img src="<?= Url::base() ?>/mobile/web/images/pro_list_banner.png" style="width:100%;" />
        </a>
    </div>

<?php } ?>
<!--main-->
<div class="index_main">
    <div class="index_main_top">
        <ul>
            <li>
                <a href="<?= Url::toRoute(['member/product', 'type' => 1]) ?>">口子</a>
            </li>
            <li>
                <a href="<?= Url::toRoute(['member/product', 'type' => 2]) ?>">信用卡</a>
            </li>
            <div class="clear"></div>
        </ul>
    </div>

    <div class="index_main_con">
        <ul>
            <?php foreach ($dai_product as $value): ?>
                <li>
                    <img src="<?= $value->logo ?>" class="fl"/>
                    <p class="imcl1">
                        <span><?= PublicController::substr($value->title, 6) ?></span>
                        <i><?= PublicController::substr($value->fy_info, 5) ?></i>
                    </p>
                    <p class="imcl2">
                        <em><?= PublicController::substr($value->title_info, 10) ?></em>
                        <em>已加入次数：<?=count($value['agent'])?></em>
                    </p>
                    <a class="agent.html" onclick="join('<?= $user_info->agent ?>','<?= $value->id ?>')">立即加入</a>
                    <div class="clear"></div>
                </li>
            <?php endforeach ?>
        </ul>
        <div class="jiazai_more" style="display:none;"><a href="javascript:;">加载更多</a></div>
        <div class="jiazai_nomore"><a href="javascript:;">没有更多了哦</a></div>

    </div>

</div>
<!--main end-->
<script>
    function join(id, pid) {
        if (id == 0) {
            layer.confirm('没有权限查看？您未开通会员资格！点击确定即可开通会员', {
                btn: ['确定'] //按钮
            }, function () {
                window.location.href = "<?=Url::toRoute('list/buy-agent')?>";
            }, function (e) {
                layer.close(e);
                return false;
            });
        } else {
            var type = "<?=Yii::$app->request->get('type')?>";
            window.location.href = "index.php?r=member/join-agent&id=" + pid + "&type="+type;
        }
    }

    function customer(id) {
        if (id == 0) {
            layer.confirm('没有权限查看？您未开通会员资格！点击确定即可开通会员', {
                btn: ['确定'] //按钮
            }, function () {
                window.location.href = "<?=Url::toRoute('list/buy-agent')?>";
            }, function (e) {
                layer.close(e);
                return false;
            });
        } else {

            window.location.href = "<?=Url::toRoute('member/customer-list')?>";
        }

    }
    
    function share(id){

        if (id == 0) {
            layer.confirm('您没有一键代理权限？您未开通会员资格！点击确定即可开通会员', {
                btn: ['确定'] //按钮
            }, function () {
                window.location.href = "<?=Url::toRoute('list/buy-agent')?>";
            }, function (e) {
                layer.close(e);
                return false;
            });
        } else {

            window.location.href = "<?=Url::toRoute('share/keyagent')?>";
        }        
    }

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
    var page = 1;
    var show = true;
    $(window).scroll(function () {
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        if (scrollTop + windowHeight >= scrollHeight && show) {
            $(".jiazai_more").show();
            Load();
        }
    });

    function Load() {
        var type = getQueryVariable("type");
        show = false;
        setTimeout(function () {
            page++;
            $.ajax({
                url: "<?= Url::toRoute(['member/load-more'])?>",
                type: 'get',
                data: {'page': page, 'type': type},
                dataType: 'text',
                success: function (data) {
                    if (data) {
                        show = true;
                        $(".index_main_con ul").append(data);
                    } else {
                        show = false;
                        $(".jiazai_more").hide().next().show();
                    }
                }
            });
        }, 1000);
    }
</script>
<script>
    $(".index_main_top ul li a").each(function () {
        if ($(this)[0].href == String(window.location) && $(this).attr('href') != "") {
            $(this).parent('li').addClass("curr");
        }
    });
</script>
<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
