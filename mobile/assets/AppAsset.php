<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace mobile\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'mobile/web/css/basic.css?v=20190629',
        'mobile/web/css/style.css?v=20190427',
        'mobile/web/css/font/iconfont.css',
        'mobile/web/css/layer.css',
        'mobile/web/css/join_vip.css',
        'mobile/web/css/vipbasic.css',
    ];
    public $js = [
        //'mobile/web/js/mobile/layerm.js',
        'mobile/web/js/jquery-1.8.3.min.js',
        'mobile/web/js/layer.js',
    ];
    public $depends = [
       /* 'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',*/
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD,   // 这是设置所有js放置的位置
    ];
}
