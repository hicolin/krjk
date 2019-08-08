<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>

<!--main-->
<div class="jindu_main">
    <div class="jm_head">
        <i class="iconfont icon-chaxun"></i>
        <span>软件工具</span>
    </div>

    <div class="jm_con">
        <ul>
            <?php
            foreach ($model as $list) { ?>
                <li>
                    <a href="javascript:;" onclick="see('<?=$list->permission?>','<?=$list->url?>','<?=$list->grade?>','<?= $user_info['grade'] ?>')">
                        <img src="<?=$list->pic?>"/>
                        <span><?=$list->name?></span>
                    </a>
                </li>
            <?php }
            ?>
            <div class="clear"></div>
        </ul>
    </div>
</div>

    <div class="nkm_search">

        <form method="get" action="">
            <input type="hidden" name="r" value="member/withdraw-money">
            <input type="text" class="search_con fl" value="<?=$keywords?>" name="keywords" placeholder="请输入关键词查询">
            <input type="hidden" name="cat_ids" value="<?=$cat_id?>">
            <button type="submit" class="fl"><i class="iconfont icon-search1"></i></button>
            <div class="clear"></div>
        </form>
    </div>
<div class="nkm_con">
        <div class="nkm_con_head">
            <ul>
                <?php foreach ($category as $key => $value): ?>
                    <li class="<?=$cat_id&&($cat_id==$value->id)?'curr':'11'?>">
                        <a href="<?=Url::toRoute(['member/withdraw-money','cat_ids'=>$value->id])?>"><?=$value->name?></a>
                    </li>
                <?php endforeach ?>
                <div class="clear"></div>
            </ul>
        </div>
    <div class="jindu_main mt10">
        <div class="jm_head">
            <!-- <i class="iconfont icon-changjianwenti"></i> -->
            <!-- <span>提额攻略</span> -->
        </div>
        <?php
        if($info) { ?>
            <div class="nkm_con_list ">
                <ul>
                    <?php
                    foreach ($info as $arr) {
                        $url = Yii::$app->urlManager->createAbsoluteUrl(['list/detail','id'=>$arr->art_id]);
                        ?>
                        <li>
                            <a onclick="see('<?=$arr->permission?>','<?=$url?>','<?= $arr-> grade ?>','<?= $user_info['grade'] ?>')">
                                <img src="<?=$arr->img?:Url::base().'/mobile/web/images/kouzi.png'?>" class="fl"/>
                                <p><?=PublicController::substr($arr->title, 40)?></p>
                                <span><?=date('Y-m-d',$arr->create_time)?>   </span>

                                <div class="clear"></div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        <?php }else{ ?>
            <div class="jm_con">
                <img src="<?= Url::base() ?>/mobile/web/images/nosear.png" class="nothing"/><br/>
                <em>没有内容</em>
            </div>
        <?php }
        ?>
    </div>
         <div class="jiazai_more" style="display:none;"><a href="javascript:;">加载更多</a></div>
        <div class="jiazai_nomore"><a href="javascript:;">没有更多了哦</a></div>
</div>

<!--main end-->
<script>
    function see(permission,url,grade,usergrade){
        if(grade > usergrade)
        {
            layer.confirm('没有权限查看？您的会员资格不够！点击确定即可升级会员', {
                btn: ['确定'] //按钮
            }, function () {
                window.location.href = "<?=Url::toRoute('list/buy-agent')?>";
            }, function (e) {
                layer.close(e);
                return false;
            });
            return false;
        }else{
             window.location.href=url;
        }
        // if(agent==1) {
        //     window.location.href=url;
        //     return false;
        // }else if(permission==0) {
        //     window.location.href=url;
        //     return false;
        // }else{
        //     layer.confirm('没有权限查看？您未开通会员资格！点击确定即可开通会员', {
        //         btn: ['确定'] //按钮
        //     }, function () {
        //         window.location.href = "<?=Url::toRoute('list/buy-agent')?>";
        //     }, function (e) {
        //         layer.close(e);
        //         return false;
        //     });
        // }

    }
</script>
<script>
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
    function Load(){
        var cat_ids = getQueryVariable("cat_ids");
        if(cat_ids==''){
            var cat_ids = 4;
        }else{
            var cat_ids = cat_ids;
        }
        show = false;
        setTimeout(function () {
            page++;
            $.ajax({
                url: "<?= Url::toRoute(['member/load-mores'])?>",
                type: 'get',
                data: {'page': page, 'cat_ids': cat_ids},
                dataType: 'text',
                success: function (data) {
                    // alert(data)
                    if (data) {
                        show = true;
                        $(".nkm_con_list ul").append(data);
                    } else {
                        show = false;
                        $(".jiazai_more").hide().next().show();
                    }
                }
            });
        }, 1000);
    }

</script>

<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
