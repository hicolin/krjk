<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<style>
    .notice-cate{display: flex;justify-content: space-around;;font-size: 16px;border-bottom: 1px dotted #ccc;text-align: center;height: 2rem;}
    .notice-cate a{display: inline-block;width: 50%;height: 2rem;line-height: 2rem}
    .notice-active{color:rgb(71, 178, 255);border-bottom: 1px solid rgb(95, 183, 255)}
    .list-red-dot{display: inline-block;width: 10px;height: 10px;background-color: red;float: right;border-radius: 50%}
</style>
<?php $this->endBlock(); ?>

<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>

<!--main-->
<div class="jindu_main mt10" style="margin-bottom: 50px">
    <div class="notice-cate">
        <a href="<?= Url::toRoute(['index/announce']) ?>"><span>公告</span></a>
        <a href="javascript:viewNotice()" class="notice-active"><span>私信</span></a>
    </div>
    <?php if(empty($notices)):?>
    <div class="jm_con">
        <img src="<?= Url::base() ?>/mobile/web/images/nosear.png" class="nothing"/><br/>
        <em>没有内容</em>
    </div>
    <?php endif;?>
    <div class="nkm_con_list ">
        <ul>
            <?php foreach ($notices as $notice):?>
            <li>
                <a href="<?= Url::to(['member/notice-detail','id'=>$notice['id']])?>">
                    <p><?= $notice['title']?>
                        <?php if($notice['is_read'] == 1):?>
                            <i class="list-red-dot"></i>
                        <?php endif;?>
                    </p>
                    <span style="float: right"><?= date('Y-m-d H:i',$notice['create_time'])?></span>
                    <div class="clear"></div>
                </a>
            </li>
            <?php endforeach;?>
        </ul>
    </div>
    <div class="load-tip" style="background-color: #eee"></div>
</div>

<!--main end-->

<?php $this->beginBlock('footer'); ?>
<script src="<?= Url::base()?>/mobile/web/aui/script/aui-scroll.js"></script>
<script>
    // 查看私信
    function viewNotice() {
        var user_id = '<?= Yii::$app->session['user_id']?>';
        if(!user_id){
            layer.msg('请登录后查看');
            return false;
        }
        location.href = '<?= Url::toRoute(['member/notice'])?>';
    }

    // 加载更多
    var page = 1;
    var isLoading = false;
    var scroll = new auiScroll({
        listen:true,
        distance:100
    },function (res) {
        if(res.isToBottom){
            if(!isLoading){
                isLoading = true;
                $('.load-tip').show().html('加载中 <i class="fa fa-spinner fa-spin"></i>');
                page++;
                loadMore(page);
            }
        }
    });

    // 请求数据
    function loadMore(page) {
        var _csrf = '<?= Yii::$app->request->csrfToken?>';
        $.post('<?= Url::to(['member/load-notice'])?>',{_csrf:_csrf,page:page},function (res) {
            if(res.status === 200){
                console.log(res.msg);
                var content = '';
                res.msg.forEach(function (val,index) {
                    content += '<li>';
                    content += '<a href="'+val.url+'">';
                    content += '<p>'+val.title;
                    if(val.is_read == 1){
                        content += '<i class="list-red-dot"></i>';
                    }
                    content += '</p>';
                    content += '<span style="float:right">'+val.create_time+'</span>';
                    content += '<div class="clear"></div>';
                    content += '</a>';
                    content += '</li>';
                });
                $('.nkm_con_list ul').append(content);
                $('.load-tip').hide();
                isLoading = false;
            }else{
                $('.load-tip').show().html('─ 我是有底线的 ─');
            }
        },'json')
    }
</script>
<?php $this->endBlock(); ?>
