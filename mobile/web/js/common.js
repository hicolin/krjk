
/**
 * 图片懒加载
 */
$(function () {
    $("img.lazyload").lazyload();
})

/**
 * 添加H5+ API
 */
function addPlusReady() {
    if(window.plus){
        plusReady()
    }else{
        document.addEventListener('plusready',plusReady,false)
    }
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
 * 判断是否为iPhone
 * @returns {boolean}
 */
function isIphone() {
    var u = navigator.userAgent;
    if(u.indexOf('iPhone') > -1){
        return true;
    }
    return false;
}

/**
 * 复制文本内容
 * @el 类名(copy-text)
 */
var clipboard = new ClipboardJS('.copy-text');
clipboard.on('success', function(e) {
    layer.msg('复制成功',{icon:1,shift:6,skin:'layui-layer-bai',time:1000});
});
clipboard.on('error', function(e) {
    console.log(e);
});

/**
 * 图片下载
 * @param src
 */
function downloadImage(src) {
    if(isIphone()){
        layer.msg('暂不支持iPhone手机，请长按图片保存或截屏',{icon:7,time:4000})
    }else{
        var $a = $("<a></a>").attr("href", src).attr("download", "poster.png");
        $a[0].click();
    }
}





