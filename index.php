<?php

// 获取UA信息
$ua = $_SERVER['HTTP_USER_AGENT'];
// 将恶意USER_AGENT存入数组
$now_ua = array('FeedDemon ', 'BOT/0.1 (BOT for JCE)', 'CrawlDaddy ', 'Java', 'Feedly', 'UniversalFeedParser',
    'ApacheBench', 'Swiftbot', 'ZmEu', 'Indy Library', 'oBot', 'jaunty', 'YandexBot', 'AhrefsBot', 'MJ12bot',
    'WinHttp', 'EasouSpider', 'HttpClient', 'Microsoft URL Control', 'YYSpider', 'jaunty', 'Python-urllib',
    'lightDeckReports Bot', 'python-requests', 'Trident');
// 禁止空USER_AGENT，dedecms等主流采集程序都是空USER_AGENT，部分sql注入工具也是空USER_AGENT
if (!$ua) {
    header("Content-type: text/html; charset=utf-8");
    die('请勿采集本站！');
} else {
    foreach ($now_ua as $value)
        // 判断是否是数组中存在的UA
        if (stristr($ua, $value)) {
            header("Content-type: text/html; charset=utf-8");
            die('请勿采集本站！');
        }
}

define("ROOT",__DIR__);

if ($_SERVER['REMOTE_ADDR'] == '114.102.151.5') {
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'dev');
    defined('YII_ENV_DEV') or define('YII_ENV_DEV', true);
} else {
    defined('YII_DEBUG') or define('YII_DEBUG', false);
    defined('YII_ENV') or define('YII_ENV', 'prod');
    defined('YII_ENV_DEV') or define('YII_ENV_DEV', false);
}

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/common/config/bootstrap.php');

require (__DIR__.'/extend/function.php');

error_reporting(E_ALL^E_NOTICE);

if(is_mobile()){
    require(__DIR__ . '/mobile/config/bootstrap.php');
}else{
    require(__DIR__ . '/frontend/config/bootstrap.php');
}

if(is_mobile()){
    $config = yii\helpers\ArrayHelper::merge(
        require(__DIR__ . '/common/config/main.php'),
        require(__DIR__ . '/mobile/config/main.php')
    );

}else{
    $config = yii\helpers\ArrayHelper::merge(
        require(__DIR__ . '/common/config/main.php'),
        require(__DIR__ . '/frontend/config/main.php')
    );
}

$application = new yii\web\Application($config);
$application->run();

function is_mobile()
{
    return true;
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $is_pc = (strpos($agent, 'windows nt')) ? true : false;
    $is_pc = true;
    $is_mac = (strpos($agent, 'mac os')) ? true : false;
    $is_iphone = (strpos($agent, 'iphone')) ? true : false;
    $is_android = (strpos($agent, 'android')) ? true : false;
    $is_ipad = (strpos($agent, 'ipad')) ? true : false;
    if($is_pc){
        return  true;
    }
    if($is_mac){
        return  true;
    }
    if($is_iphone){
        return  true;
    }
    if($is_android){
        return  true;
    }
    if($is_ipad){
        return  true;
    }
}

