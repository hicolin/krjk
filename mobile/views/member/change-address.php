<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
<link rel="stylesheet" type="text/css" href="<?= Url::base() ?>/mobile/web/css/LArea.css">
<!--main-->
<div class="tel_main">
    <form method="post" onsubmit="return apply_check()">
        <div class="tel_main_con" >
            <ul>
                <li>
                    <input name="province" id="demo1" style="width:100%" type="text" readonly="" placeholder="省市县"  value="<?=$user_info->province?>">
                    <input id="value1" type="hidden" value="20,234,504">
                </li>
                <li>
                    <input name="address" value="<?=$user_info->address?>" class="revise_addr" type="text" placeholder="请输入详细地址">
                </li>
            </ul>
        </div>
        <div class="tel_main_bot">
            <button>确定</button>
        </div>
    </form>
</div>
<!--main end-->
<script src="<?= Url::base() ?>/mobile/web/js/LAreaData1.js" ></script>
<script src="<?= Url::base() ?>/mobile/web/js/LAreaData2.js"></script>
<script src="<?= Url::base() ?>/mobile/web/js/LArea.js"></script>
<script>
    //适应不同屏幕
    /*window.onload=function(){
        document.documentElement.style.fontSize= document.documentElement.clientWidth/16+'px' ;
    };*/
    var area1 = new LArea();
    area1.init({
        'trigger': '#demo1',
        'valueTo': '#value1',
        'keys': {
            id: 'id',
            name: 'name'
        },
        'type': 1,
        'data': LAreaData
    });
    area1.value=[28,0,8];
    var area2 = new LArea();
    area2.init({
        'trigger': '#demo2',
        'valueTo': '#value2',
        'keys': {
            id: 'value',
            name: 'text'
        },
        'type': 2,
        'data': [provs_data, citys_data, dists_data]
    });

    //验证
    function demo1(){
        var str=$("#demo1").val();
        var reg=/^[\u4e00-\u9fa5,\ ,]{2,}$/;
        if(!reg.test(str)){
            layer.tips('省市县不能为空', "#demo1", {tips:3,time:2000});
            return false;
        }
        else{return true;}
    }

    function revise_addr(){
        var str=$(".revise_addr").val();
        var reg=/^[\u4e00-\u9fa5]{2,}$/;
        if(!reg.test(str)){
            layer.tips('详细地址不能为空', ".revise_addr", {tips:3,time:2000});
            return false;
        }
        else{return true;}
    }

    function apply_check() {
        if(demo1()&&revise_addr()){
            return true;
        }else{
            return false;
        }
    }
</script>
<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
