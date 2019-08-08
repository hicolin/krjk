<?php

namespace backend\controllers;

use Yii;
use backend\models\AdminBankCard;
use backend\models\AdminBankCardSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use common\controllers\PublicController;

/**
 * AdminBankCardController implements the CRUD actions for AdminBankCard model.
 */
class AdminBankCardController extends Controller
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
    public function actionIndex( $type = 1 )
    {
        $view = $type==1?'card-create':'bank-create';
        $query = AdminBankCard::find()->andWhere(['type'=>$type])->Orderby('create_time desc');
        $querys = Yii::$app->request->get('query');
       if (count($querys) > 0) {
            $title= $querys['title'];
            $permission= $querys['permission'];

            if ($title) {
                $query = $query->andWhere(['like', 'title', $title]);
            }

            if($permission!=2){
                $query =$query->andWhere(['permission'=>$permission]);
            }            
        }
        $pagination = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => '10',
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page']
        );
        $model = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index', [
            'model' => $model,
            'pages' => $pagination,
            'view' => $view,
            'type' => $type,
            'query'=>$querys,
        ]);
    }

    /**
     * 信用卡贷
     */
    public function actionCardLoan()
    {
        return $this->actionIndex();
    }

    /**
     * 银行快贷
     */
    public function actionBankLoan()
    {
        return $this->actionIndex(2);
    }

    /**
     * 信用卡贷,添加
     */
    public function actionCardCreate()
    {
        return $this->actionCreate();
    }

    /**
     * 银行快贷，添加
     */
    public function actionBankCreate()
    {
        return $this->actionCreate(2);
    }

    /**
     * 信用卡贷,修改
     */
    public function actionCardUpdate($id)
    {
        return $this->actionUpdate($id);
    }

    /**
     * 银行快贷，修改
     */
    public function actionBankUpdate($id)
    {
        return $this->actionUpdate($id);
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
    public function actionCreate( $type = 1 )
    {
        $view = $type==1?'card-loan':'bank-loan';
        $model = new AdminBankCard();
        $request = Yii::$app->request;
        if($model->load(Yii::$app->request->post())) {
            $model->title = PublicController::filter($model->title);
            $model->beizhu = PublicController::filter($model->beizhu);
            $model->hk_way = PublicController::filter($model->hk_way);
            $model->range = PublicController::filter($model->range);
            $model->time_limit = PublicController::filter($model->time_limit);
            $model->condition = PublicController::filter($request->post('condition'),2);
            $model->attention = PublicController::filter($request->post('attention'),2);
            $model->create_time = time();
            $model->type = $request->post('type');
            $view = $request->post('type')==1?'card-loan':'bank-loan';
            if($model->save(false)) {
                return $this->redirect([$view]);
            }else {
                return $this->render('create', [
                    'model' => $model,
                    'view' => $view,
                    'type' => $type,
                ]);
            }
        }else {
            return $this->render('create', [
                'model' => $model,
                'view' => $view,
                'type' => $type,
            ]);
        }
    }

    /**
     * Updates an existing AdminBankCard model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate( $id='' )
    {
        $model = $this->findModel($id);
        $view = $model->type==1?'card-loan':'bank-loan';
        $request = Yii::$app->request;
        if($model->load(Yii::$app->request->post())) {
            $model->title = PublicController::filter($model->title);
            $model->beizhu = PublicController::filter($model->beizhu);
            $model->hk_way = PublicController::filter($model->hk_way);
            $model->range = PublicController::filter($model->range);
            $model->time_limit = PublicController::filter($model->time_limit);
            $model->condition = PublicController::filter($request->post('condition'),2);
            $model->attention = PublicController::filter($request->post('attention'),2);
            $view = $request->post('type')==1?'card-loan':'bank-loan';
            if($model->save()) {
                return $this->redirect([$view]);
            }else{
                return $this->render('update', [
                    'model' => $model,
                    'view' => $view,
                    'type' => $model->type,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'view' => $view,
                'type' => $model->type,
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
        if (count($ids) > 0) {
            $c = AdminBankCard::deleteAll(['in', 'id', $ids]);
            echo json_encode(array('errno' => 0, 'data' => $c, 'msg' => json_encode($ids)));
        } else {
            echo json_encode(array('errno' => 2, 'msg' => ''));
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

     public function actionStatus($id){


    $model = $this->findModel($id);
    $view = $model->type==1?'card-loan':'bank-loan';
    $model->permission = $model->permission == 1 ? $model->permission = 0 : $model->permission = 1;
    $model->save(false);
    $this->redirect([$view]);

    }
}
