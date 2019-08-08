<?php

namespace backend\controllers;

use backend\models\AdminCategory;
use Yii;
use backend\models\AdminArticle;
use backend\models\AdminArticleSearch;
use common\controllers\PublicController;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\web\UploadedFile;
use backend\models\AdminGrade;

/**
 * AdminArticleController implements the CRUD actions for AdminArticle model.
 */
class AdminArticleController extends Controller
{
    /**
     * @inheritdoc
     */
    public $layout = "lte_main";
    public $enableCsrfValidation=false;

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
     * Lists all AdminArticle models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = AdminArticle::find()->where('cat_id != 9');
        $querys = Yii::$app->request->get('query');

        if (count($querys) > 0) {
            $title = $querys['title'];
            $cat_id = $querys['cat_id'];
            $grade=$querys['grade'];
            $is_recom =$querys['is_recom'];
            if ($title) {
                $query = $query->andWhere(['like', 'title', $title]);
            }
            if ($cat_id) {
                $query = $query->andWhere(['cat_id' => $cat_id]);
            }
            if($grade!=4){
                $query =$query->andWhere(['grade'=>$grade]);
            }
            if($is_recom!=0){
                $query =$query->andWhere(['is_recom'=>$is_recom]);
            }
        }
        $pageSize = (int)abs(PublicController::getSysInfo(36));
        $pagination = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => $pageSize,
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page']
        );
        $art = new AdminCategory();
        $art_name = $art->find()->All();
        header("content-type:text/html;charset=utf-8");
        $model = $query->with('art_name')
            ->orderBy('create_time desc')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        
        return $this->render('index', [
            'model' => $model,
            'art_name' => $art_name,
            'query' => $querys,
            'pages' => $pagination,
        ]);

    }

    /**
     * Displays a single AdminArticle model.
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
     * Creates a new AdminArticle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $grade = AdminGrade::find()->asArray()->all();
        $model = new AdminArticle();
        $cat = new AdminCategory();
        $cat_list = $cat->find()->All();

        if ($model->load(Yii::$app->request->post())) {
            $model->title = PublicController::filter($model->title);
            $model->detail = PublicController::filter($model->detail, 2);
            $model->grade = Yii::$app->request->post('grade');
            $model->create_time = time();
            if ($model->save()) {
                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                    'cat_list' => $cat_list,
                    'model' => $model,
                    'grade' => $grade,
                ]);
            }
        }

        return $this->render('create', [
            'cat_list' => $cat_list,
            'model' => $model,
            'grade' => $grade,
        ]);
    }
    public function actionPage(){
        $query = AdminArticle::find()->where(['cat_id'=>9])->Orderby('create_time desc');
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
        return $this->render('page', [
            'model' => $products,
            'pages' => $pagination,
        ]);
    }

    public function actionCreatePage(){

            $model = new AdminArticle();
        if ($model->load(Yii::$app->request->post())) {
                $model->title = PublicController::filter($model->title);
                if($model->save()){
                    return $this->redirect(['page']);
                }else {
                return $this->render('create-page', [
                    'model' => $model,
                ]);
            }
        }
        return $this->render('create-page', [
            
            'model' => $model,
        ]);
    }

    public function actionUpdatePage($id){
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->title = PublicController::filter($model->title);
            if($model->save()){
                return $this->redirect(['page']);
            }else{
            return $this->render('update-page', [
                'model' => $model,
            ]);
            }
        }
        return $this->render('update-page', [
           
                'model' => $model,
        ]);
    }

    /**
     * Updates an existing AdminArticle model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        //会员等级
        $grade = AdminGrade::find()->asArray()->all();
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->title = PublicController::filter($model->title);
            $model->detail = PublicController::filter($model->detail, 2);
            $model->grade = Yii::$app->request->post('grade');
            if ($model->save()) {
                return $this->redirect(['index']);
            } else {
                return $this->redirect('update', [
                    'model' => $model,
                    'grade' => $grade,
                ]);
            }
        }
        $cat = new AdminCategory();
        $cat_list = $cat->find()->All();
        return $this->render('update', [
            'cat_list' => $cat_list,
            'model' => $model,
            'grade' => $grade,
        ]);
    }

    /**
     * Deletes an existing AdminArticle model.
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
            $c = AdminArticle::deleteAll(['in', 'art_id', $ids]);
            echo json_encode(array('errno' => 0, 'data' => $c, 'msg' => json_encode($ids)));
        } else {
            echo json_encode(array('errno' => 2, 'msg' => ''));
        }
    }

    /*改变代理商权限状态*/
    public function actionPermission()
    {
       /* $model = $this->findModel($id);
        $model->permission = $model->permission == 1 ? $model->permission = 0 : $model->permission = 1;
        $model->save(false);
        $this->redirect(['index']);*/
        
        $art_id = Yii::$app->request->post('art_id');
       
        $model = AdminArticle::findOne($art_id);
        $model->grade = Yii::$app->request->post('grade');

        if ($model->save(false)) {

            return 100;
        } else {
            return 300;
        }
    }


    /*改变推荐状态*/
    public function actionRecommend($id)
    {
        $model = $this->findModel($id);
        $model->is_recom = $model->is_recom == 1 ? $model->is_recom = 2 : $model->is_recom = 1;
        $model->save(false);
        $this->redirect(['index']);
    }

    /**
     * Finds the AdminArticle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminArticle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminArticle::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
