<?php
use common\controllers\PublicController;
use yii\helpers\Url;
?>
<?php $this->beginBlock('header');?>
<style>
    body {
        background: #F3F3F3;
        margin-bottom: 0 !important;
    }
    .scrollDiv li {
        overflow: hidden;
    }
    .scrollDiv li img {
        width: 20px;
        vertical-align: middle;
        margin-top: -3px;
        margin-right: 3px;
    }
    .scrollDiv li i {
        color: #e35805;
    }

    /*贷款超市*/
    .market_nav {
        line-height: 1.5rem;
    }
    .market_nav h1 {
        font-size: 0.6rem;
        color: #999;
        text-align: center;
    }
    .market_nav h1 i {
        color: #333333;
    }
    .market_banner img {
        width: 100%;
    }
    .market_list {
        background: #FFF
    }
    .index_main_con img.hot {
        width: 1.4rem;
        position: absolute;
        right: 1.6rem;
        top: 0;
    }
    .index_main_con li a {
        position: relative;
    }
    .search_box_con button {
        width: 33%;
        height: 28px;
        border: none;
        background: #33aaff;
        color: #fff;
        font-size: 13px;
    }
    .search_box_bot span {
        font-size: 12px;
        color: #777;
    }
    .search_box_bot a {
        font-size: 12px;
        margin-left: 4px;
        color: #777;
    }

    /*条件筛选*/
    .condition_choose {
    }
    .condition_choose li {
        font-size: 14px;
    }
    .condition_choose_one h1 {
        font-size: 14px;
        color: #333;
        padding-left: 10px;
        line-height: 30px;
    }
    .cco_list li {
        width: 20%;
        float: left;
        line-height: 15px;
        border-right: 1px solid #eee;
        text-align: center;
        font-size: 12px;
    }
    .cco_list li.on {
        color: #33aaff;
    }
    .cco_list ul li {
        font-size: 16px;
    }
    .condition_choose_two h1 {
        font-size: 14px;
        color: #333;
        padding-left: 10px;
        line-height: 30px;
    }
    .cct_list li {
        width: 20%;
        float: left;
        line-height: 15px;
        border-right: 1px solid #eee;
        text-align: center;
        font-size: 12px;
    }
    .cct_list li select {
        color: #666;
        height: 15px;
        border: none;
        font-size: 12px;
    }
    .banner{height: 128px}
    .banner img{width: 100%;height: 100%}

    .product-container h2{padding:0 5px;border-left: 4px solid #1E9FFF;font-size: 16px; width: calc(100% - 1rem);margin:1rem auto 0.6rem auto;}
    .index_main_con{border-top:1px solid #eee; }
</style>
<?php $this->endBlock();?>
<div class="content_w" style="background: #FFF;">
<div class="banner">
    <img src="<?= Url::base().PublicController::getSysInfo(34)?>" alt="">
</div>
    <hr style="border: 1px solid #eee">
</div>
<div style="background: #fff;padding-top: 1px;">
    <div class="new_kou_main">
        <div class="market_list">
            <div class="product-container">
                <?php foreach ($data as $key=>$category):?>
                    <h2><?= $key?></h2>
                    <div class="index_main_con">
                        <ul>
                            <?php foreach ($category as $value): ?>
                                <li>
                                    <a onClick="join('<?= $value['id'] ?>','<?=$value['type']?>')">
                                        <img class="lazyload" data-original="<?= $value['logo'] ?>"/>
                                        <h1><?= PublicController::substr($value['title'], 6) ?></h1>
                                        <p><em>额度:<em style="color: #33aaff"><?= $value['range']?></em></em><br> <em>月利率:<em style="color: #33aaff;"><?=$value['interest']?>%</em></em></p>
                                        <?php if($value['is_hot']==1){ ?>
                                            <img src="<?= Url::base() ?>/mobile/web/images/hot.png" class="hot"/>
                                        <?php }   ?>
                                    </a>
                                </li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</div>

<div style="height: 4rem;"></div>
<!--main end-->
<?php $this->beginBlock('footer');?>
<script>
    function join(pid,type){
        var user_id = '<?=$user_id?>';
        var sign =101;
        window.location.href = "<?= Url::to(['other/sub-product'])?>" + "?pid=" +pid+ "&type="+type+"&uid="+user_id+"&sign="+sign;
    }
</script>
<?php $this->endBlock();?>



