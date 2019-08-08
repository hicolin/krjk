<?php
namespace mobile\controllers;

use backend\models\AdminArticle;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\Controller;

/**
 * 该控制器不需要登陆权限
 * Site controller
 */
class SiteController extends Controller
{
    // 文章详情
    public function actionArticleDetail($id)
    {
        $this->view->title = '文章详情';
        $model = AdminArticle::findOne($id);
        return $this->render('article-detail',[
            'model'=>$model,
        ]);
    }


}
