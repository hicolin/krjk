<?php
use common\controllers\PublicController;
use yii\helpers\Url;
?>
<?php $this->beginBlock('header'); ?>
<style>
    .person_foot{display: none;}
    .pay-ways select{width: 100%;border: none;padding: 10px;font-size: 16px;background-color: #fff}

    /*支付页面 start*/
    .small_title{background: #e4e4e4;font-size: 0.8rem;color: #666;padding: 0.6rem;}
    .payOrder_list{background: #fff;padding: 0 0.6rem;}
    .payOrder_list li{padding: 0.8rem 0;border-bottom: 1px solid #eee;display: flex;justify-content: space-between;}
    .payOrder_list li:nth-child(4) p{color: #ef8328;}
    .payOrder_list li h1{font-size: 0.76rem;color: #333;}
    .payOrder_list li p{font-size: 0.76rem;color: #999;}

    .payOrder_way{display: flex;align-items: center;justify-content: space-between;padding: 0.6rem;background: #fff;}
    .payOrder_way>div{display: flex;align-items: center;}
    .payOrder_way div img{width: 2.2rem;height: 2.2rem;margin-right: 0.4rem;}
    .payOrder_way div h1{font-size: 0.8rem;color: #111;margin-bottom: 0.4rem;}
    .payOrder_way div p{font-size: 0.68rem;color: #999;}
    .payOrder_btn_parent{padding: 0 0.6rem;margin-top: 3rem;}
    .payOrder_btn{width: 100%;height: 2rem;background: #33aaff;color: #fff;border: none;font-size: 0.8rem;line-height: 1rem;}

    .pay_zzc{position: fixed;width: 100%;height: 100%;top:0;left: 0;}
    .pay_zzc img{width: 100%;margin-top: -3rem;}
    /*支付页面 end  */
</style>
<?php $this->endBlock(); ?>


<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>

<div class="pay_main">
    <div class="small_title">订单详情</div>
    <ul class="payOrder_list">
        <li>
            <h1>商品名称</h1>
            <p>金牌会员</p>
        </li>
        <li>
            <h1>商家名称</h1>
            <p><?= PublicController::getSysInfo(25)?></p>
        </li>
        <li>
            <h1>商品价格</h1>
            <p style="color:#ef8328;">￥<?=$price?>元</p>
        </li>
    </ul>
    <div class="small_title">选择支付方式</div>
    <div class="pay-ways">
        <select name="pay-type" id="">
            <?php foreach ($pay_ways as $val):?>
                <option value="<?= $val['id']?>"><?= $val['pay_name']?></option>
            <?php endforeach;?>
        </select>
    </div>
    <div class="payOrder_btn_parent">
        <button onclick="data_check()" class="payOrder_btn">确认支付</button>
    </div>
</div>

<!--main end-->
<script>
    // 提交
    function data_check() {
        var _csrf = "<?= Yii::$app->request->csrfToken ?>";
        var type = $('select[name="pay-type"]').val();
        var base = "<?=Yii::$app->request->getHostInfo()?>";
        layer.load(2);
        $.post('<?= Url::to(['list/sub-data'])?>',{_csrf:_csrf,type:type},function (res) {
            layer.closeAll();
            if(res.status === 200){
                if(type == 1){  // 支付宝支付
                    if(isWeiXin()){
                        layer.msg('由于微信内不能使用支付宝支付，请选择微信支付');
                        return false;
                    }
                    url = res.url;
                }
                else if(type == 2){    // 微信支付
                    url = base + "/wxpay/example/jsapi.php";
                }
                else if(type == 3){    // 线下支付
                    url = base + "<?=Url::toRoute(['list/upimg'])?>&order_id" + res.order_id;
                }
                location.href = url;
            }else{
                layer.msg(res.msg);
            }
        },'json');
    }

    // 是否为微信
    function isWeiXin(){
        var ua = window.navigator.userAgent.toLowerCase();
        if(ua.match(/MicroMessenger/i) == 'micromessenger'){
            return true;
        }
        return false;
    }
</script>

<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
