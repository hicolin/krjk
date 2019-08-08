<?php

namespace backend\controllers;
use Yii;
use backend\models\AdminRegiones;
use backend\models\AdminKey;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
/**
 * AdminKeyController implements the CRUD actions for AdminKey model.
 */
class AdminKeyController extends Controller
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
     * Lists all AdminKey models.
     * @return mixed
     */
    public function actionIndex()
    {
    $query = AdminKey::find()->alias("a")->select("a.*,(SELECT region_name FROM admin_regions as b WHERE a.province=b.region_id ) as province_name,(SELECT region_name FROM admin_regions as b WHERE a.city=b.region_id ) as city_name,(SELECT region_name FROM admin_regions as b WHERE a.area=b.region_id ) as area_name");
        $querys = Yii::$app->request->get('query');
        $name = $querys['name'];
        $mobile = $querys['mobile'];
        if (empty($name)) {
            $name = '';
        }
        if (empty($mobile)) {
            $mobile ='';
        }
    
    $pagination = new Pagination([
    'totalCount' =>$query->count(),
    'pageSize' => '10',
    'pageParam'=>'page',
    'pageSizeParam'=>'per-page']
    );
    $products = $query->offset($pagination->offset)
    ->limit($pagination->limit)
    ->andwhere(['like','name',$name])
    ->andwhere(['like','mobile',$mobile])
    ->asArray()
    ->all();
    return $this->render('index', [
    'model' => $products,
    'pages'=>$pagination,
    'query'=>$querys,
    ]);
    }

    /**
     * Displays a single AdminKey model.
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
     * Creates a new AdminKey model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminKey();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AdminKey model.
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
     * Deletes an existing AdminKey model.
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
    $c=AdminKey::deleteAll(['in', 'id', $ids]);
    echo json_encode(array('errno'=>0, 'data'=>$c, 'msg'=>json_encode($ids)));
    }
    else{
    echo json_encode(array('errno'=>2, 'msg'=>''));
    }
    }
    /**
     * Finds the AdminKey model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminKey the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminKey::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
