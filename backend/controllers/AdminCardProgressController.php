<?php

namespace backend\controllers;

use Yii;
use backend\models\AdminCardProgress;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use common\controllers\PublicController;
/**
 * AdminCardProgressController implements the CRUD actions for AdminCardProgress model.
 */
class AdminCardProgressController extends Controller
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
     * Lists all AdminCardProgress models.
     * @return mixed
     */
    public function actionIndex()
    {
        $type=['1'=>'在线查询','2'=>'其他渠道'];
        $query = AdminCardProgress::find()->Orderby('create_time desc');
        $querys = Yii::$app->request->get('query');
        if (count($querys) > 0) {
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
                'type'  => $type,
                'query' =>$querys,
            ]);
    }

    /**
     * Displays a single AdminCardProgress model.
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
     * Creates a new AdminCardProgress model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $type=['1'=>'在线查询','2'=>'其他渠道'];

        $model = new AdminCardProgress();
        if ($model->load(Yii::$app->request->post())) {
                $model->name = PublicController::filter($model->name);
               
                if($model->save()){
                    return $this->redirect(['index']);
                }else {

                return $this->render('create', [
                    'model' => $model,
                    'type'  => $type,
                ]);
            }
        }
                return $this->render('create', [
                    'model' => $model,
                    'type'  => $type,
                ]);

    }

    /**
     * Updates an existing AdminCardProgress model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

        $type=['1'=>'在线查询','2'=>'其他渠道'];
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
                $model->name = PublicController::filter($model->name);
                
            if($model->save()){
             return $this->redirect(['index']);       
            }else {
            return $this->render('update', [
                'model' => $model,
                'type'  => $type,
            ]);
        }  
     }
            return $this->render('update', [
            'model' => $model,
            'type'  => $type,
            ]);
    }

    /**
     * Deletes an existing AdminCardProgress model.
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
            $c = AdminCardProgress::deleteAll(['in', 'id', $ids]);
            echo json_encode(array('errno' => 0, 'data' => $c, 'msg' => json_encode($ids)));
        } else {
            echo json_encode(array('errno' => 2, 'msg' => ''));
        }
    }

    /**
     * Finds the AdminCardProgress model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminCardProgress the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminCardProgress::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


        public function actionStatus(){
        

      /*  $model = $this->findModel($id);
        $model->permission = $model->permission == 1 ? $model->permission = 0 : $model->permission = 1;
        $model->save(false);
        $this->redirect(['index']);*/


            $id = Yii::$app->request->post('id');

            $model = AdminCardProgress::findOne($id);
            $model->grade = Yii::$app->request->post('grade');

            if ($model->save(false)) {

                return 100;
            } else {
                return 300;
            }


    }
}
