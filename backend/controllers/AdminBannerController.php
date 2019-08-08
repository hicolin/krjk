<?php

namespace backend\controllers;

use Yii;
use backend\models\AdminBanner;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\web\UploadedFile;
use common\controllers\PublicController;

/**
 * AdminBannerController implements the CRUD actions for AdminBanner model.
 */
class AdminBannerController extends Controller
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
     * Lists all AdminBanner models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = AdminBanner::find()->Orderby('create_time desc');
        $querys = Yii::$app->request->get('query');
        if (count($querys) > 0) {
            $condition = "";
            $parame = array();
            foreach ($querys as $key => $value) {
                $value = trim($value);
                if (empty($value) == false) {
                    $parame[":{$key}"] = $value;
                    if (empty($condition) == true) {
                        $condition = " {$key}=:{$key} ";
                    } else {
                        $condition = $condition . " AND {$key}=:{$key} ";
                    }
                }
            }
            if (count($parame) > 0) {
                $query = $query->where($condition, $parame);
            }
        }
        $pagination = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => '10',
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page']
        );
        $products = $query->offset($pagination->offset)
            ->orderBy('id desc')
            ->limit($pagination->limit)
            ->all();
        return $this->render('index', [
            'model' => $products,
            'pages' => $pagination,
        ]);
    }

    /**
     * Displays a single AdminBanner model.
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
     * Creates a new AdminBanner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminBanner();

        if ($model->load(Yii::$app->request->post())){
            $model->title = PublicController::filter($model->title);
            $model->link = PublicController::filter($model->link);
                if ($model->save()) {
                   return $this->redirect(['index']);
                }else {
                return $this->render('create', [
                    'model' => $model,
                ]);
             } 
        }
                return $this->render('create', [
                    'model' => $model,
                ]);
    }

    /**
     * Updates an existing AdminBanner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->title = PublicController::filter($model->title);
            $model->link = PublicController::filter($model->link);            
            if ($model->save()) {
                return $this->redirect(['index']);
            }else {

            return $this->render('update', [
                'model' => $model,
            ]);
        }
      }

            return $this->render('update', [
            'model' => $model,
            ]);
    }

    /**
     * Deletes an existing AdminBanner model.
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
            $c = AdminBanner::deleteAll(['in', 'id', $ids]);
            echo json_encode(array('errno' => 0, 'data' => $c, 'msg' => json_encode($ids)));
        } else {
            echo json_encode(array('errno' => 2, 'msg' => ''));
        }
    }

    /**
     * Finds the AdminBanner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminBanner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminBanner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
