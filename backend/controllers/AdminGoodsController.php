<?php

namespace backend\controllers;

use Yii;
use backend\models\AdminGoods;
use backend\models\AdminGrade;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use backend\controllers\PublicController;
use common\controllers\PublicController as Pulic;

/**
 * AdminGoodsController implements the CRUD actions for AdminGoods model.
 */
class AdminGoodsController extends Controller
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
     * Lists all AdminGoods models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = AdminGoods::find()->Orderby('create_time desc');
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
            ->limit($pagination->limit)
            ->all();
        return $this->render('index', [
            'model' => $products,
            'pages' => $pagination,
        ]);
    }

    /**
     * Displays a single AdminGoods model.
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
     * Creates a new AdminGoods model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        header("content-type:text/html;charset=utf-8");
        $model = new AdminGoods();
        $grade = AdminGrade::find()->all();
        if($model->load(Yii::$app->request->post())){
            $model->detail=PublicController::filter($model->detail,$type=2);
            $model->title=PublicController::filter($model->title,$type=2);
            if( $_FILES['AdminGoods'] ){
                $model->pic = Pulic::file($_FILES['AdminGoods'],'pic');
            } 
            if(empty($model->detail)){
                Yii::$app->session->setFlash('info','1');
            }

            if($model->save()){
                return $this->redirect(['index']);
            }else {
                return $this->render('create', [
                    'model' => $model,
                    'grade' => $grade,
                ]);
            }
        }else {
            return $this->render('create', [
                'model' => $model,
                'grade' => $grade,
            ]);
        }
    }

    public function actionGetprice()
    {
        $id = Yii::$app->request->get('id');
        $grade = AdminGrade::find()->where(['id'=>$id])->one();
        $price = $grade->price;
        return $price; 
    }
    /**
     * Updates an existing AdminGoods model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        // print_r($model);exit;
        $grade = AdminGrade::find()->all();
        $pic = $model->pic;
        $bei_pic = $model->bei_pic;
        if($model->load(Yii::$app->request->post())) {
            if(isset( $_FILES['AdminGoods']['name']['pic'] ) && $_FILES['AdminGoods']['name']['pic'] != '' ) { 
                    $model->pic = Pulic::file($_FILES['AdminGoods'],'pic'); 
            }else{
                    $model->pic = $pic;
            }
            if(isset( $_FILES['AdminGoods']['name']['bei_pic'] ) && $_FILES['AdminGoods']['name']['bei_pic'] != '' ) { 
                    $model->bei_pic = Pulic::file($_FILES['AdminGoods'],'bei_pic'); 
            }else{
                    $model->bei_pic = $bei_pic;
            }           
            if ($model->save()) {

                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'grade' => $grade,
                ]);
            }

        }else{

                return $this->render('update', [
                    'model' => $model,
                    'grade' => $grade,
                ]);
        }

    }

    /**
     * Deletes an existing AdminGoods model.
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
            $c = AdminGoods::deleteAll(['in', 'id', $ids]);
            echo json_encode(array('errno' => 0, 'data' => $c, 'msg' => json_encode($ids)));
        } else {
            echo json_encode(array('errno' => 2, 'msg' => ''));
        }
    }


    /**
     * Finds the AdminGoods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminGoods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminGoods::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
