<?php
use yii\helpers\Url;
use common\controllers\PublicController;
?>
<?php $this->beginBlock('header'); ?>
<style type="text/css">
    .person_foot{
        display: none;
    }
    .share-footer{height: 3rem;position: fixed;bottom: 0;background-color: #fff;width: 100%;max-width:600px;display: flex;justify-content:space-between;padding: 0.3rem}
    .share-footer .share-item{width: 50%;text-align: center;margin-top: 0.2rem;line-height: 1rem}
    .share-item:nth-child(1){border-right: 1px solid #ccc}
    .fa{font-size: 1.2rem}
    .h5-share{width: 2rem;height: 2rem;border-radius: 50%;background-color: red;position: fixed;bottom: 4rem;right: 1rem;text-align: center;line-height: 2.5rem}
</style>
<?php $this->endBlock(); ?>

<?php $this->beginContent('@app/views/layouts/header.php')?>
<?php $this->endContent();?>

<div class="poster_main" id="main" style="margin-top: 2px;">
    <img src="<?=$pic?>" class="bg" id="bg" style="margin-bottom: 3rem"/>
</div>
<div class="share-footer">
    <div class="share-item" id="web-save" onclick="downloadImage('<?= $pic?>')">
        <span><i class="fa fa-download"></i><br>保存海报</span>
    </div>
    <div class="share-item" id="h5-save" style="display: none">
        <span style="color:red"><i class="fa fa-download"></i><br>保存海报</span>
    </div>
    <div class="share-item copy-text" data-clipboard-text="<?= $url?>" onclick="">
        <span><i class="fa fa-unlink"></i><br>复制链接</span>
    </div>
</div>

<div class="h5-share" style="display: none"><i class="fa fa-share" style="color:#fff"></i></div>

<?php $this->beginBlock('footer'); ?>
<script>
    let src = '<?= $pic?>';
    let url = '<?= $url?>';
    addPlusReady();
    function plusReady() {
        let $webSave =  $('#web-save');
        let $h5Save =  $('#h5-save');
        let $h5Share = $('.h5-share');
        $webSave.hide();
        $h5Save.show();
        $h5Share.show();
        $h5Save.click(function () {
            saveGalleryPic(src);
        });
        $h5Share.click(function () {
            share(plus);
        });
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

    /**
     * h5+ 系统分享
     */
    function share() {
        plus.share.sendWithSystem({content: '<?= PublicController::getSysInfo(14)?>',href:url}, function(){
            console.log('分享成功');
        }, function(e){
            console.log('分享失败：'+JSON.stringify(e));
        });
    }

</script>
<?php $this->endBlock();?>
