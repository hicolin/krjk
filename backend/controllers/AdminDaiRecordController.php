<?php

namespace backend\controllers;

use Yii;
use backend\models\AdminDaiRecord;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use common\controllers\PublicController;
use common\helps\ExportExcelController;

/**
 * AdminDaiRecordController implements the CRUD actions for AdminDaiRecord model.
 */
class AdminDaiRecordController extends BaseController
{
    /**
     * @inheritdoc
     */
    public $layout = "lte_main";
    public $phone_system = ['1' => 'Android', '2' => 'IOS', '3' => '其他'];

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all AdminDaiRecord models.
     * @return mixed
     */
    public function actionIndex()
    {
        $sign = Yii::$app->request->get('sign');
        if ($sign != '' && $sign == 101) {
            $view = 'rz';
            $query = AdminDaiRecord::find()->andwhere('admin_dai_record.sign=' . $sign)->joinWith('members')->joinWith('product');
        } else {
            $query = AdminDaiRecord::find()->joinWith('members')->joinWith('product')->where(['admin_dai_record.sign' => NULL]);
            $view = 'index';
        }
        $querys = Yii::$app->request->get('query');
        $query = self::condition($query, $querys);
        $pageSize = (int)abs(PublicController::getSysInfo(36));
        $pagination = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => $pageSize,
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page']
        );
        $products = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('created_time desc')
            ->all();
        return $this->render($view, [
            'model' => $products,
            'pages' => $pagination,
            'phone_system' => $this->phone_system,
            'query' => $querys,
        ]);
    }

    // 搜索条件
    public static function condition($query, $querys)
    {
        if (count($querys) > 0) {
            if ($querys['phone']) {
                $query = $query->andWhere(['like', 'admin_member.tel', $querys['phone']]);
            }
            if ($querys['name']) {
                $query = $query->andWhere(['like', 'name', $querys['name']]);
            }
            if ($querys['product']) {
                $query = $query->andWhere(['like', 'title', $querys['product']]);
            }
            if ($querys['tel']) {
                $query = $query->andWhere(['like', 'admin_dai_record.tel', $querys['tel']]);
            }
            if ($querys['match_num']) {
                if ($querys['match_num'] == 3) {
                    $querys['match_num'] = 0;  // 特殊处理
                }
                $query = $query->andWhere(['=', 'admin_dai_record.match_num', $querys['match_num']]);
            }
            if ($querys['b_time']) {
                $query = $query->andWhere(['>=', 'admin_dai_record.created_time', $querys['b_time']]);
            }
            if ($querys['e_time']) {
                $query = $query->andWhere(['<=', 'admin_dai_record.created_time', $querys['e_time']]);
            }
        }
        return $query;
    }

    /**
     * Displays a single AdminDaiRecord model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = AdminDaiRecord::find()->joinWith('member')->where(['admin_dai_record.id' => $id])->one();
        return $this->render('view', [
            'model' => $model,
            'phone_system' => $this->phone_system,
        ]);

    }

    /**
     * Creates a new AdminDaiRecord model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminDaiRecord();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AdminDaiRecord model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'phone_system' => $this->phone_system,
            ]);
        }
    }

    public function actionAudit()
    {
        $id = (int)Yii::$app->request->get('id');
        $model = AdminDaiRecord::findOne($id);
        if (!$model) {
            return $this->json(100, '参数错误');
        }
        if ($model->match_num == 1) {
            return $this->json(100, '匹配成功的状态，不可修改');
        }
        $model->match_num = 2;
        if (!$model->save(false)) {
            return $this->json(100, '审核失败');
        }
        return $this->json(200, '审核成功');
    }

    public function actionBatchAudit()
    {
        $ids = Yii::$app->request->get('ids');
        if (empty($ids)) {
            return $this->json(100, '请选择批量审核的记录');
        }
        $err = 0;
        foreach ($ids as $id) {
            $model = AdminDaiRecord::findOne($id);
            if ($model->match_num == 1){
                continue;
            }
            $model->match_num = 2;
            if (!$model->save(false)) {
                $err++;
            };
        }
        if ($err > 0) {
            return $this->json(100, '批量审核失败');
        }
        return $this->json(200, '批量审核成功');
    }

    /**
     * Deletes an existing AdminDaiRecord model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDelrecord(array $ids)
    {
        if (count($ids) > 0) {
            $c = AdminDaiRecord::deleteAll(['in', 'id', $ids]);
            echo json_encode(array('errno' => 0, 'data' => $c, 'msg' => json_encode($ids)));
        } else {
            echo json_encode(array('errno' => 2, 'msg' => ''));
        }
    }

    /**
     * Finds the AdminDaiRecord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminDaiRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminDaiRecord::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 导出数据
     */
    public function actionExport()
    {
        $phone_system = ['1' => '安卓', '2' => 'IOS', '3' => '其他'];
        $sign = Yii::$app->request->get('sign');
        $excel = new ExportExcelController();
        if ($sign == 101) {
            $model = AdminDaiRecord::find()->andwhere('admin_dai_record.sign='.$sign)->joinWith('members')->joinWith('product');
            $filename = '融资客申请列表' . date('Ymd', time());
        } else {
            $model = AdminDaiRecord::find()->joinWith('members')->joinWith('product')->where(['admin_dai_record.sign' => NULL]);
            $filename = '代理申请列表' . date('Ymd', time());
        }
        $query = Yii::$app->request->get('query');
        $model = self::condition($model, $query);
        $model = $model->asArray()->all();
        $data[] = ['序号', '微信昵称', '姓名', '身份证', '推荐人', '推荐人手机号', '产品名称', '手机号码', 'ip地址', '设备系统', '申请时间'];
        foreach ($model as $k => $arr) {
            $data[$k + 1]['id'] = $arr['id'];
            $data[$k + 1]['user_id'] = $arr['member']['nickname'];
            $data[$k + 1]['name'] = $arr['name'];
            $data[$k + 1]['id_card'] = $arr['id_card'];
            if ($sign == 101) {
                $data[$k + 1]['tid'] = '';
                $data[$k + 1]['t_tel'] = '';
            } else {
                $data[$k + 1]['tid'] = $arr['members']['nickname'];
                $data[$k + 1]['t_tel'] = $arr['members']['tel'];
            }
            $data[$k + 1]['pid'] = $arr['product']['title'];
            $data[$k + 1]['tel'] = $arr['tel'];
            $data[$k + 1]['ip'] = $arr['ip'];
            $data[$k + 1]['phone_system'] = $phone_system[$arr['phone_system']];
            $data[$k + 1]['created_time'] = date('Y-m-d H:i:s', $arr['created_time']);
        }
        $excel->download($data, $filename);
    }
}
