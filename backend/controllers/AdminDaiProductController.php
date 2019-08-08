<?php

namespace backend\controllers;

use backend\models\AdminDaiProduct;
use backend\models\AdminGrade;
use backend\models\AdminProductCategory;
use backend\models\AdminSetting;
use common\controllers\PublicController;
use Yii;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * AdminDaiProductController implements the CRUD actions for AdminDaiProduct model.
 */
class AdminDaiProductController extends BaseController
{
    /**
     * @inheritdoc
     */
    public $layout = "lte_main";
    public $enableCsrfValidation = false ;
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
     * Lists all AdminDaiProduct models.
     * @return mixed
     * 代呗列表
     */
    public function actionIndex()
    {

        $query = AdminDaiProduct::find()->joinWith('category')
            ->where(['type'=>1])->orderby('listorder asc');
        $querys = Yii::$app->request->get('query');
        $query = self::condition($query,$querys);
        $pageSize = (int)abs(PublicController::getSysInfo(36));
        $pagination = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => $pageSize,
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page']
        );
        $products = $query->offset($pagination->offset)
            ->andWhere(['type'=>1])
            ->limit($pagination->limit)
            ->all();
        $categories = AdminProductCategory::find()->asArray()->all();
        return $this->render('index', [
            'model' => $products,
            'pages' => $pagination,
            'query'=>$querys,
            'categories'=>$categories,
        ]);
    }

    // 查询条件
    public static function condition($query,$querys)
    {
        if (count($querys) > 0) {
            if($querys['title']){
                $query = $query->andWhere(['like','title',$querys['title']]);
            }
            if($querys['style']){
                $query = $query->andWhere(['style'=>$querys['style']]);
            }
            if($querys['cate_id']){
                $query = $query->andWhere(['cate_id'=>$querys['cate_id']]);
            }
        }
        return $query;
    }

    //是否隐藏
    public function actionStatus($id,$type){
        $model = $this->findModel($id);
        $model->is_open = $model->is_open == 1 ? $model->is_open = 0 : $model->is_open = 1;
        $view = $type==1?'index':'credit-card';
        $model->save(false);
        return $this->redirect(Yii::$app->request->referrer);
    }


    //前台热门图标显示隐藏
    public function actionStatus2($id,$type){
        
        $model = $this->findModel($id);
        $model->is_hot = $model->is_hot == 1 ? $model->is_hot = 0 : $model->is_hot = 1;
        $view = $type==1?'index':'credit-card';
        $model->save(false);
        $this->redirect(Yii::$app->request->referrer);

    }
    //一键banner显示隐藏
    public function actionBanner($id,$type){
        
        $model = $this->findModel($id);
        $model->is_tuibanner = $model->is_tuibanner == 1 ? $model->is_tuibanner = 0 : $model->is_tuibanner = 1;
        $view = $type==1?'index':'credit-card';
        $model->save(false);
        $this->redirect(Yii::$app->request->referrer);

    }

    // 口子排序
    public function actionChangeorder(){
        $listorder =  Yii::$app->request->post('listorder');
        $id=Yii::$app->request->post('id');
        $listorders = AdminDaiProduct::findOne($id);
        $listorders->listorder = $listorder;
        $listorders->save(false);
        exit;
    }
    //信用卡排序
    public function actionChangeorders(){
        $listorder =  Yii::$app->request->post('listorder');
        $id=Yii::$app->request->post('id');
        $listorders = AdminDaiProduct::findOne($id);
        $listorders->listorder = $listorder;
        $listorders->save(false);
        exit;
    }
    /**
     *信用卡列表
     */
    public function actionCreditCard()
    {  
        $query = AdminDaiProduct::find()->where(['type'=>2])->Orderby('listorder asc');
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
            ->andWhere(['type'=>2])
            ->limit($pagination->limit)
            ->all();
        return $this->render('card', [
            'model' => $products,
            'pages' => $pagination,
        ]);
    }

    /**
     * Displays a single AdminDaiProduct model.
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
     * Creates a new AdminDaiProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $grade = AdminGrade::find()->asArray()->all();
        $categories = AdminProductCategory::find()->asArray()->all();
        $model = new AdminDaiProduct();
        $request = Yii::$app->request;
        if($model->load(Yii::$app->request->post())) {
            $model->title = PublicController::filter($model->title);
            $model->style = intval($model->style);
            $model->cate_id = intval($model->cate_id);
            $model->fy_info = PublicController::filter($model->fy_info);
            $model->title_info = PublicController::filter($model->title_info);
            $model->share_info = PublicController::filter($model->share_info);
            $model->hk_way = PublicController::filter($model->hk_way);
            $model->range = PublicController::filter($model->range);
            $model->grade = PublicController::filter($request->post('grade'));
            $model->time_limit = PublicController::filter($model->time_limit);
            $model->detail = PublicController::filter($request->post('detail'),2);
            $model->apply_detail = PublicController::filter($request->post('apply_detail'),2);
            if( $_FILES['AdminDaiProduct'] ){
                $model->pic = PublicController::file($_FILES['AdminDaiProduct'],'pic');
                $model->join_pic = PublicController::file($_FILES['AdminDaiProduct'],'join_pic');
                $model->share_pic = PublicController::file($_FILES['AdminDaiProduct'],'share_pic');

            } 
            $model->create_time=time();
            if($model->save()) {
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
                'categories'=>$categories,
            ]);
        }
    }
    /**
     * Creates a new AdminDaiProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateCard()
    {
        $grade = AdminGrade::find()->asArray()->all();
        $model = new AdminDaiProduct();
        $request = Yii::$app->request;
        if($model->load(Yii::$app->request->post())) {

            $model->type = 2;
            $model->title = PublicController::filter($model->title);
            $model->fy_info = PublicController::filter($model->fy_info);
            $model->title_info = PublicController::filter($model->title_info);
            $model->share_info = PublicController::filter($model->share_info);
            $model->hk_way = PublicController::filter($model->hk_way);
            $model->grade = PublicController::filter($request->post('grade'));
            $model->range = PublicController::filter($model->range);
            $model->time_limit = PublicController::filter($model->time_limit);
            $model->detail = PublicController::filter($request->post('detail'),2);
            $model->apply_detail = PublicController::filter($request->post('apply_detail'),2);

            if($_FILES['AdminDaiProduct']){
                $model->pic = PublicController::file($_FILES['AdminDaiProduct'],'pic');
                $model->join_pic = PublicController::file($_FILES['AdminDaiProduct'],'join_pic');
                $model->sub_pic = PublicController::file($_FILES['AdminDaiProduct'],'sub_pic');
                $model->share_pic = PublicController::file($_FILES['AdminDaiProduct'],'share_pic');

            } 
           
            $model->create_time=time();
            if($model->save()) {

                return $this->redirect(['credit-card']);
            }else {
                return $this->render('create-card', [
                    'model' => $model,
                    'grade' => $grade,
                ]);
            }
        }else {
            return $this->render('create-card', [
                'model' => $model,
                'grade' => $grade,
            ]);
        }
    }


    /**
     * Updates an existing AdminDaiProduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        //会员等级
        $grade = AdminGrade::find()->asArray()->all();

        $model = $this->findModel($id);
        $categories = AdminProductCategory::find()->asArray()->all();
        $typecat1 = AdminSetting::find()->where(['id'=>16])->asArray()->all();
        $request = Yii::$app->request;
        $pic = $model->pic;
        $join_pic = $model->join_pic;
        if($model->load(Yii::$app->request->post())) {
            $model->title = PublicController::filter($model->title);
            $model->style = intval($model->style);
            $model->cate_id = intval($model->cate_id);
            $model->fy_info = PublicController::filter($model->fy_info);
            $model->title_info = PublicController::filter($model->title_info);
            $model->share_info = PublicController::filter($model->share_info);
            $model->detail = PublicController::filter($request->post('detail'),2);
            $model->range = PublicController::filter($request->post('range'));
            $model->grade = PublicController::filter($request->post('grade'));
       
            $model->apply_detail = PublicController::filter($request->post('apply_detail'),2);  
            if(isset( $_FILES['AdminDaiProduct']['name']['pic'] ) && $_FILES['AdminDaiProduct']['name']['pic'] != '' ) { 
                $model->pic = PublicController::file($_FILES['AdminDaiProduct'],'pic'); 
            }else{
                $model->pic = $pic;
            } 
            if(isset( $_FILES['AdminDaiProduct']['name']['join_pic']) && $_FILES['AdminDaiProduct']['name']['join_pic'] != '' )  {
                $model->join_pic = PublicController::file($_FILES['AdminDaiProduct'],'join_pic');  
            }else{
                $model->join_pic = $join_pic;
            }

            if(isset( $_FILES['AdminDaiProduct']['name']['share_pic']) && $_FILES['AdminDaiProduct']['name']['share_pic'] != '' )  {
                $model->share_pic = PublicController::file($_FILES['AdminDaiProduct'],'share_pic');  
            }else{
                $model->share_pic = '';
            }              
            if($model->save()) {
             //   return $this->redirect(['index']);
                $reffer = Yii::$app->request->post('reffer');
                return $this->redirect($reffer);
            }else {
                return $this->render('update', [
                    'model' => $model,
                    'grade' => $grade,
                    'typecat1' => $typecat1,
                ]);
            }
        }else { 
            return $this->render('update', [
                'model' => $model,
                'grade' => $grade,
                'typecat1' => $typecat1,
                'categories'=>$categories
            ]);
        }
    }

    public function actionUpdateCard($id)
    {
        $grade = AdminGrade::find()->asArray()->all();
        $model = $this->findModel($id);
        $request = Yii::$app->request;
        $pic = $model->pic;
        $join_pic = $model->join_pic;
        $sub_pic = $model->sub_pic;
        if($model->load(Yii::$app->request->post())) {
            $model->title = PublicController::filter($model->title);
            $model->fy_info = PublicController::filter($model->fy_info);
            $model->title_info = PublicController::filter($model->title_info);
            $model->share_info = PublicController::filter($model->share_info);
            $model->detail = PublicController::filter($request->post('detail'),2);
            $model->grade = PublicController::filter($request->post('grade'));
            $model->apply_detail = PublicController::filter($request->post('apply_detail'),2);
            if($_FILES['AdminDaiProduct']['name']['pic']) {
                $model->pic = PublicController::file($_FILES['AdminDaiProduct'],'pic');
            }else{ 
                $model->pic = $pic;
            }
            if($_FILES['AdminDaiProduct']['name']['join_pic']) {
                $model->join_pic = PublicController::file($_FILES['AdminDaiProduct'],'join_pic');
            }else{
                $model->join_pic = $join_pic;
            }
            if($_FILES['AdminDaiProduct']['name']['sub_pic']) {
                $model->sub_pic = PublicController::file($_FILES['AdminDaiProduct'],'sub_pic');
            }else{
                $model->sub_pic = $sub_pic;
            }
            if($_FILES['AdminDaiProduct']['name']['share_pic']) {
                $model->share_pic = PublicController::file($_FILES['AdminDaiProduct'],'share_pic');
            }else{
                $model->share_pic = $share_pic;
            }
            
            if($model->save()) {
               // return $this->redirect(['credit-card']);
                $reffer = Yii::$app->request->post('reffer');
                return $this->redirect($reffer);
            }else {
                return $this->render('update-card', [
                    'model' => $model,
                    'grade' => $grade,
                ]);
            }
        }else {
            return $this->render('update-card', [
                'model' => $model,
                'grade' => $grade,
            ]);
        }
    }

    /**
     * Deletes an existing AdminDaiProduct model.
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
            $c = AdminDaiProduct::deleteAll(['in', 'id', $ids]);
            echo json_encode(array('errno' => 0, 'data' => $c, 'msg' => json_encode($ids)));
        } else {
            echo json_encode(array('errno' => 2, 'msg' => ''));
        }
    }

    /**
     * Finds the AdminDaiProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminDaiProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminDaiProduct::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
