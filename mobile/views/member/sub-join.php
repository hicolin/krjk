<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<style type="text/css">
    .person_foot{
        display: none;
    }
    .person_foot3 {
        height: 2.5rem;
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        background: #fff;
        padding: 0.3rem 0;
        border-top: 1px solid #eee;
    }
    .person_foot3 li {
        width: 50%;

    }
    .person_foot3 li {
        float: left;
        width: 45%;
        text-align: center;
        position: relative;
        height: 100%;
    }
    .person_foot3 li a {
        display: block;
    }
    .person_foot3 li i {
        font-size: 1rem;
        color: #888;
        display: block;
        padding: 0.2rem 0;
    }
    .person_foot3 li span {
        font-size: 0.6rem;
        color: #888;
    }
    .share-footer{height: 3rem;position: fixed;bottom: 0;background-color: #fff;width: 100%;max-width:600px;display: flex;justify-content:space-between;padding: 0.3rem}
    .share-footer .share-item{width: 50%;text-align: center;margin-top: 0.2rem;line-height: 1rem}
    .share-item:nth-child(1){border-right: 1px solid #ccc}
    .fa{font-size: 1.2rem}
</style>
<?php $this->endBlock(); ?>

<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>

<div class="agent_ewm_main">
    <div class="aem_img">
        <img src="<?=$pic?>" style="background-size: contain;box-sizing: border-box;width: 100%">
        <div class="notice" style="margin:0.5rem 2rem 0; border: 1px solid #ccc; position: relative;">
            <span style="display: block; width: 4rem; height: 1rem; position: absolute; top: -0.5rem; left: 50%; margin-left: -2rem; line-height: 1rem; font-size: 0.6rem; background: #f3f3f3;color: #ccc;">使用说明</span>
            <p style="color: #666;font-size: 0.6rem; margin: 0.8rem 0 0.5rem;">长按二维码保存图片或发送给好友</p>
        </div>
    </div>
</div>

<div class="share-footer">
    <div class="share-item" id="web-save" onclick="downloadImage('<?= $pic?>')">
        <span><i class="fa fa-download"></i><br>保存海报</span>
    </div>
    <div class="share-item" id="h5-save" style="display: none">
        <span style="color:red"><i class="fa fa-download"></i><br>保存海报</span>
    </div>
    <div class="share-item copy-text" data-clipboard-text="<?= $promUrl?>" onclick="">
        <span><i class="fa fa-unlink"></i><br>复制链接</span>
    </div>
</div>

<?php $this->beginBlock('footer'); ?>
<script>
    let src = '<?= $pic?>';
    addPlusReady();
    function plusReady() {
        let $webSave =  $('#web-save');
        let $h5Save =  $('#h5-save');
        $webSave.hide();
        $h5Save.show();
        $h5Save.click(function () {
            saveGalleryPic(src);
        })
    }

    /**
     * h5+ 保存图片到相册
     * @param src
     */
    function saveGalleryPic(src) {
        var dtask = plus.downloader.createDownload( src, {}, function ( d, status ) {
            if ( status == 200 ) {
                plus.gallery.save( d.filename, function(){
                    plus.nativeUI.toast('保存成功');
                }, function(){
                    console.log('保存失败');
                });
            } else {
                plus.nativeUI.toast( "Download failed: " + status );
            }
        });
        dtask.start();
    }
</script>
<?php $this->endBlock(); ?>

