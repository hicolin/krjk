<?php
/**
 * User: Colin
 * Date: 2019/4/17
 * Time: 21:29
 */

namespace backend\controllers;

use backend\models\AdminLoanReport;
use Yii;
use yii\data\Pagination;

class AdminLoanReportController extends BaseController
{
    public function actionIndex()
    {
        $query = AdminLoanReport::find()->joinWith('member');
        $search = Yii::$app->request->get('search');
        $query = $this->condition($query, $search);
        $pageSize = (int)abs(\common\controllers\PublicController::getSysInfo(36));
        $pagination = new Pagination([
           'totalCount' => $query->count(),
           'pageSize' => $pageSize,
        ]);
        $models = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('id desc')
            ->all();
        $data = compact('search', 'pagination', 'models');
        return $this->render('index', $data);
    }

    public function condition($query, $search)
    {
        return $query;
    }

    public function actionDel()
    {

    }

    public function actionBatchDel()
    {

    }

    public function actionAudit()
    {

    }

}