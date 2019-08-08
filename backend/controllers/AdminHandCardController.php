<?php

namespace backend\controllers;

use Yii;
use backend\models\AdminHandCard;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use common\controllers\PublicController;

/**
 * AdminHandCardController implements the CRUD actions for AdminHandCard model.
 */
class AdminHandCardController extends Controller
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
     * Lists all AdminHandCard models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = AdminHandCard::find()->Orderby('create_time desc');
        $querys = Yii::$app->request->get('query');
        if (count($querys) > 0) {
            $name= $querys['name'];
            $grade=$querys['grade'];
            if ($name) {
                $query = $query->andWhere(['like', 'name', $name]);
            }
            if($grade!=4){
                $query =$query->andWhere(['grade'=>$grade]);
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
            'query' => $querys,
        ]);
    }

    /**
     * Displays a single AdminHandCard model.
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
     * Creates a new AdminHandCard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminHandCard();

        if ($model->load(Yii::$app->request->post())) {
             $model->name = PublicController::filter($model->name);
             $model->remark = PublicController::filter($model->remark);
            if($model->save()){
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
     * Updates an existing AdminHandCard model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
          
            if ($model->load(Yii::$app->request->post())) {
                $model->name = PublicController::filter($model->name);
                $model->remark = PublicController::filter($model->remark);
                if($model->save()){
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
     * Deletes an existing AdminHandCard model.
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
            $c = AdminHandCard::deleteAll(['in', 'id', $ids]);
            echo json_encode(array('errno' => 0, 'data' => $c, 'msg' => json_encode($ids)));
        } else {
            echo json_encode(array('errno' => 2, 'msg' => ''));
        }
    }

    /**
     * Finds the AdminHandCard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminHandCard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminHandCard::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

     public function actionStatus(){


         $id = Yii::$app->request->post('id');

         $model = AdminHandCard::findOne($id);
         $model->grade = Yii::$app->request->post('grade');

         if ($model->save(false)) {

             return 100;
         } else {
             return 300;
         }

    }
}
