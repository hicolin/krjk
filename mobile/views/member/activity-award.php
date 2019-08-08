<?php
use yii\helpers\Url;
?>
<?php $this->beginBlock('header'); ?>
<style>
    .m-content{padding: 0 .4rem;background-color: #fff}
    .item-list{display: flex;justify-content: space-between;height: 1.8rem;line-height: 1.8rem;border-bottom: 1px solid #eee}
    .item-list:nth-child(1){font-weight: bold}
    .item-list span{display: inline-block;width: 33.3%;text-align: center}
    .load-tip {
        text-align: center;
        color: #999;
        padding: 15px 0;
    }
    .empty{font-size: 5rem;padding: 2rem 0;text-align: center;display: block;color: #ccc}
    .empty-desc{text-align: center;padding-bottom: 2rem;}
</style>
<?php $this->endBlock(); ?>

<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>
<div class="m-content">
    <div class="data-list">
        <div class="item-list">
            <span>时间</span>
            <span>金额/元</span>
            <span>备注</span>
        </div>
        <?php if(empty($awards)):?>
        <div>
            <i class="fa fa-file-o empty"></i>
            <p class="empty-desc">暂无数据~</p>
        </div>
        <?php endif;?>
        <?php foreach ($awards as $list):?>
            <div class="item-list">
                <span><?= date('Y-m-d H:i',$list['create_time'])?></span>
                <span><?= $list['money']?></span>
                <span><?= $list['remark']?></span>
            </div>
        <?php endforeach;?>
    </div>
    <div class="load-tip" style="display: none;"><i class="fa fa-spinner fa-spin"></i> 加载中...</div>
</div>
<?php $this->beginBlock('footer'); ?>
<script>
    // 加载更多
    var total = <?= $total?>;
    var pageSize = <?= $pageSize?>;
    var page = 1;
    var isLoading = false;
    var scroll = new auiScroll({
        listen:false,
        distance:100
    },function (res) {
        if(res.isToBottom && total>pageSize && !isLoading){
            isLoading = true;
            $('.load-tip').show();
            page++;
            $.get('<?= Url::to(['member/activity-award'])?>',{page:page},function (res) {
                if(res.status === 200){
                    var content = '';
                    res.data.forEach(function (val) {
                        content += '<div class="item-list">';
                            content += '<span>'+val.create_time+'</span>';
                            content += '<span>'+val.money+'</span>';
                            content += '<span>'+val.remark+'</span>';
                        content += '</div>';
                    });
                    $('.data-list').append(content);
                    $('.load-tip').hide();
                    isLoading = false;
                }else{
                    $('.load-tip').show().html('─ 我是有底线的 ─');
                }
            },'json')
        }
    })
</script>
<?php $this->endBlock(); ?>
