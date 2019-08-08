<?php

namespace backend\controllers;

use Yii;
use backend\models\AdminDaiList;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;

/**
 * AdminDaiListController implements the CRUD actions for AdminDaiList model.
 */
class AdminDaiListController extends Controller
{
    /**
     * @inheritdoc
     */
    public $layout = "lte_main";

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
     * Lists all AdminDaiList models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = AdminDaiList::find()->Orderby('created_time desc');
        $querys = Yii::$app->request->get('query');
        if (count($querys) > 0) {

        }
        $pagination = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => '10',
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page']
        );
        $products = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index', [
            'model' => $products,
            'pages' => $pagination,
        ]);
    }

    /**
     * Displays a single AdminDaiList model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);

    }

    /**
     * Creates a new AdminDaiList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminDaiList();
        if($model->load(Yii::$app->request->post())) {
            $model->money = floatval($model->money);
            $model->created_time = time();
            if($model->save()) {
                return $this->redirect(['index']);
            }else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AdminDaiList model.
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
            ]);
        }
    }

    /**
     * Deletes an existing AdminDaiList model.
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
            $c = AdminDaiList::deleteAll(['in', 'id', $ids]);
            echo json_encode(array('errno' => 0, 'data' => $c, 'msg' => json_encode($ids)));
        } else {
            echo json_encode(array('errno' => 2, 'msg' => ''));
        }
    }

    /**
     * Finds the AdminDaiList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminDaiList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminDaiList::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     *更改状态
     */
    public function actionChange()
    {
        $id = intval(Yii::$app->request->get('id'));
        $model = AdminDaiList::findOne($id);
        $status = abs($model->status-1);
        $model->status = $status;
        if($model->save()) {
            return 100;
        }else {
            return 200;
        }
    }

    /**
     *导入excel
     */
    public function actionLoad() {
        $excelFile = Yii::$app->request->get('file');
        $phpexcel = new PHPExcel;
        $excelReader = PHPExcel_IOFactory::createReader('Excel5');
        $phpexcel = $excelReader->load($excelFile)->getSheet(0);//载入文件并获取第一个sheet

        $total_line = $phpexcel->getHighestRow();
        $total_column = $phpexcel->getHighestColumn();

        for ($row = 2; $row <= $total_line; $row++) {
            $data = array();
            for ($column = 'A'; $column <= $total_column; $column++) {
                $data[] = trim($phpexcel->getCell($column.$row) -> getValue());
            }
        }
        echo '<pre>';
        print_r($data);exit;
    }
}
