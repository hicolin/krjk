<?php

namespace backend\controllers;

use Yii;
use backend\models\AdminBankCard;
use backend\models\AdminBankCardSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use common\controllers\PublicController;
/**
 * AdminBankController implements the CRUD actions for AdminBankCard model.
 */
class AdminBankController extends Controller
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
     * Lists all AdminBankCard models.
     * @return mixed
     */
    public function actionIndex()
    {
       $query = AdminBankCard::find()->Orderby('create_time desc');
       $querys = Yii::$app->request->get('query');
       if(count($querys) > 0){
       $condition = "";
       $parame = array();
       foreach($querys as $key=>$value){
       $value = trim($value);
       if(empty($value) == false){
       $parame[":{$key}"]=$value;
       if(empty($condition) == true){
       $condition = " {$key}=:{$key} ";
       }
       else{
       $condition = $condition . " AND {$key}=:{$key} ";
       }
       }
       }
       if(count($parame) > 0){
       $query = $query->where($condition, $parame);
       }
       }
       $pagination = new Pagination([
       'totalCount' =>$query->count(),
       'pageSize' => '10',
       'pageParam'=>'page',
       'pageSizeParam'=>'per-page']
       );
       $model = $query->offset($pagination->offset)
       ->limit($pagination->limit)
       ->all();
       return $this->render('index', [
       'model' => $model,
       'pages'=>$pagination,
       ]);

    }

    /**
     * Displays a single AdminBankCard model.
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
     * Creates a new AdminBankCard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminBankCard();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AdminBankCard model.
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
     * Deletes an existing AdminBankCard model.
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
    if(count($ids) > 0){
    $c=AdminBankCard::deleteAll(['in', 'id', $ids]);
    echo json_encode(array('errno'=>0, 'data'=>$c, 'msg'=>json_encode($ids)));
    }
    else{
    echo json_encode(array('errno'=>2, 'msg'=>''));
    }
    }
    /**
     * Finds the AdminBankCard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminBankCard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminBankCard::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
