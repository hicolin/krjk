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
    public $layout = "lte_main";

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
        if (isset($search['tel']) && $search['tel']) {
            $query->andWhere(['like', 'admin_member.tel', $search['tel']]);
        }
        if (isset($search['name']) && $search['name']) {
            $query->andWhere(['like', 'admin_loan_report.name', $search['name']]);
        }
        if (isset($search['product']) && $search['product']) {
            $query->andWhere(['like', 'product', $search['product']]);
        }
        if (isset($search['status']) && $search['status']) {
            $query->andWhere(['=', 'status', $search['status']]);
        }
        if (isset($search['b_time']) && $search['b_time']) {
            $query->andWhere(['>', 'admin_loan_report.create_time', $search['b_time']]);
        }
        if (isset($search['e_time']) && $search['e_time']) {
            $query->andWhere(['<', 'admin_loan_report.create_time', $search['e_time']]);
        }
        return $query;
    }

    public function actionDel()
    {
        $id = (int)Yii::$app->request->post('id');
        $model = AdminLoanReport::findOne($id);
        if (!$model->delete()){
            return $this->json(100, '删除失败');
        }
        return $this->json(200, '删除成功');
    }

    public function actionBatchDel()
    {
        $ids = Yii::$app->request->post('ids');
        $res = AdminLoanReport::deleteAll(['in', 'id', $ids]);
        if (!$res) {
            return $this->json(100, '批量删除失败');
        }
        return $this->json(200, '批量删除成功');
    }

    public function actionAudit()
    {
        $post = Yii::$app->request->post();
        $model = AdminLoanReport::findOne($post['id']);
        $model->status = $post['status'];
        if (!$model->save(false)) {
            return $this->json(100, '操作失败');
        }
        return $this->json(200, '操作成功');
    }

}