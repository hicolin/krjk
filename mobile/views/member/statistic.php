<?php
use yii\helpers\Url;
?>
<?php $this->beginBlock('header'); ?>
<link rel="stylesheet" href="<?= Url::base()?>/mobile/web/plugins/LCalendar/LCalendar.css">
<style>
    .person_foot{display: none}
    .m-content{background-color: #fff;margin-top: 0.1rem;padding: 0.5rem}
    .report-group{text-align: center}
    .report-title{background-color: #eee;width: 80%;border-radius: 0.1rem;text-align: center;font-size: 0.8rem;height: 1.4rem;  line-height:1.4rem;margin: auto}
    .report-list{margin: 1rem}
    .report-list .report-item{display: flex;justify-content: space-between;line-height:1.6rem;border-bottom: 1px solid #eee }
    .report-item span:nth-child(2){color:#aaa}
</style>
<?php $this->endBlock(); ?>

<?php $this->beginContent('@app/views/layouts/header.php') ?>
<?php $this->endContent(); ?>

<div class="m-content">
    <div class="report-group">
        <div class="report-title">全部</div>
        <div class="report-list">
            <div class="report-item">
                <span>团队成员</span>
                <span><?= $allMembers ? : 0?> 人</span>
            </div>
            <div class="report-item">
                <span>订单数量</span>
                <span><?= $allOrders ? : 0?> 单</span>
            </div>
            <div class="report-item">
                <span>CPA订单</span>
                <span><?= $allCpaOrders ? : 0?> 单</span>
            </div>
            <div class="report-item">
                <span>CPS订单</span>
                <span><?= $allCpsOrders ? : 0?> 单</span>
            </div>
            <div class="report-item">
                <span>收入金额</span>
                <span><?= $allAward ? : 0?> 元</span>
            </div>
        </div>
    </div>
    <div class="report-group">
        <div class="report-title">本月</div>
        <div class="report-list">
            <div class="report-item">
                <span>团队成员</span>
                <span><?= $monthMembers ? : 0?> 人</span>
            </div>
            <div class="report-item">
                <span>订单数量</span>
                <span><?= $monthOrders ? : 0?> 单</span>
            </div>
            <div class="report-item">
                <span>CPA订单</span>
                <span><?= $monthCpaOrders ? : 0?> 单</span>
            </div>
            <div class="report-item">
                <span>CPS订单</span>
                <span><?= $monthCpsOrders ? : 0?> 单</span>
            </div>
            <div class="report-item">
                <span>收入金额</span>
                <span><?= $monthAward ? : 0?> 元</span>
            </div>
        </div>
    </div>
    <div class="report-group">
        <div class="report-title">本周</div>
        <div class="report-list">
            <div class="report-item">
                <span>团队成员</span>
                <span><?= $weekMembers ? : 0?> 人</span>
            </div>
            <div class="report-item">
                <span>订单数量</span>
                <span><?= $weekOrders ? : 0?> 单</span>
            </div>
            <div class="report-item">
                <span>CPA订单</span>
                <span><?= $weekCpaOrders ? : 0?> 单</span>
            </div>
            <div class="report-item">
                <span>CPS订单</span>
                <span><?= $weekCpsOrders ? : 0?> 单</span>
            </div>
            <div class="report-item">
                <span>收入金额</span>
                <span><?= $weekAward ? : 0?> 元</span>
            </div>
        </div>
    </div>
    <div class="report-group">
        <div class="report-title">
           按月查询： <input type="text" value="" id="pick_date" size="8" style="border: 1px solid #ccc;text-align: center">
            <i class="iconfont icon-caret-bottom" style="color: #999"></i>
        </div>
        <div class="report-list">
            <div class="report-item">
                <span>团队成员</span>
                <span><span class="pick_date_member">--</span> 人</span>
            </div>
            <div class="report-item">
                <span>订单数量</span>
                <span><span class="pick_date_order">--</span> 单</span>
            </div>
            <div class="report-item">
                <span>CPA订单</span>
                <span><span class="pick_date_cpa_order">--</span> 单</span>
            </div>
            <div class="report-item">
                <span>CPS数量</span>
                <span><span class="pick_date_cps_order">--</span> 单</span>
            </div>
            <div class="report-item">
                <span>收入金额</span>
                <span><span class="pick_date_award">--</span> 元</span>
            </div>
        </div>
    </div>
</div>

<?php $this->beginBlock('footer'); ?>
<script src="<?= Url::base()?>/mobile/web/plugins/LCalendar/LCalendar.js"></script>
<script>
    var calendar = new LCalendar();
    calendar.init({
        'trigger': '#pick_date', //标签id
        'type': 'ym', //date 调出日期选择 datetime 调出日期时间选择 time 调出时间选择 ym 调出年月选择,
        'minDate': (new Date().getFullYear()-3) + '-' + 1 + '-' + 1, //最小日期
        'maxDate': (new Date().getFullYear()+3) + '-' + 12 + '-' + 31 //最大日期
    });

    $('.icon-caret-bottom').click(function () {
        $('#pick_date').click();
    });

    // LCalendar回调
    function pickDate() {
        var pickDate =  $('#pick_date').val();
        $.post('<?= Url::to(['member/statistic'])?>', {pickDate: pickDate}, function (res) {
            if (res.status == 200) {
                $('.pick_date_member').html(res.url.members);
                $('.pick_date_order').html(res.url.orders);
                $('.pick_date_cpa_order').html(res.url.cpaOrders);
                $('.pick_date_cps_order').html(res.url.cpsOrders);
                $('.pick_date_award').html(res.url.award ? res.url.award : 0);
            }
        }, 'json')
    }

    // 轮询检测
    //var pickDateOld = $('#pick_date').val();
    //setInterval(function () {
    //    var pickDate =  $('#pick_date').val();
    //    if (pickDate && pickDate != pickDateOld) {
    //        pickDateOld = pickDate;
    //        $.post('<?//= Url::to(['member/statistic'])?>//', {pickDate: pickDate}, function (res) {
    //            if (res.status == 200) {
    //                $('.pick_date_member').html(res.url.members);
    //                $('.pick_date_order').html(res.url.orders);
    //                $('.pick_date_commission').html(res.url.commission ? res.url.commission : 0);
    //            }
    //        }, 'json')
    //    }
    //}, 500);

</script>
<?php $this->endBlock(); ?>
