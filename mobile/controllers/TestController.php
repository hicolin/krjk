<?php
/**
 * User: Colin
 * Date: 2019/4/14
 * Time: 20:29
 */

namespace mobile\controllers;
use common\controllers\PublicController;
use Yii;
use yii\helpers\VarDumper;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
//        Yii::$app->session->set('user_id', 6566);

    }

    // 调试函数
    public function dd()
    {
        $params = func_get_args();
        foreach ($params as $v){
            VarDumper::dump($v,10,true);
        }
        exit(1);
    }
}