<?php

namespace backend\controllers;

use Yii;
use backend\models\AdminCommemts;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use backend\models\AdminMember;
use common\controllers\PublicController;
use backend\models\AdminGrade;
/**
 * AdminCommemtsController implements the CRUD actions for AdminCommemts model.
 */
class AdminCommemtsController extends BaseController
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
     * Lists all AdminCommemts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $grade = AdminGrade::find()->all();
        $query = AdminCommemts::find()->joinwith('member')->joinwith('grade')->Orderby('create_time desc');
        $querys = Yii::$app->request->get('query');
        if (count($querys) > 0) {
            $user_id = $querys['user_id'];
            $content = $querys['content'];
            $status=$querys['status'];
            $recommend=$querys['recommend'];
            $grades = $querys['grade'];
            if ($grades>0) {
                $query = $query->andWhere(['=', 'admin_grade.id', $grades]);
            }             
            if ($user_id) {
                
                $query = $query->andWhere(['like','nickname',$user_id]);
            }
            if ($content) {
                $query = $query->andWhere(['like', 'content', $content]);
            }
            if($status){
                $query=$query->andWhere(['status'=>$status]);
            }
            if($recommend){
                $query=$query->andWhere(['recommend'=>$recommend]);
            }

        }
        $pageSize = (int)abs(PublicController::getSysInfo(36));
        $pagination = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => $pageSize,
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page']
        );
        $products = $query->offset($pagination->offset)->with('member')
            ->orderBy('id desc')
            ->limit($pagination->limit)
            ->all();
        return $this->render('index', [
            'model' => $products,
            'pages' => $pagination,
            'query'=>$querys,
            'grade' =>$grade,
        ]);
    }

    /**
     * Displays a single AdminCommemts model.
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
     * Creates a new AdminCommemts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminCommemts();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AdminCommemts model.
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
     * Deletes an existing AdminCommemts model.
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
            $c = AdminCommemts::deleteAll(['in', 'id', $ids]);
            echo json_encode(array('errno' => 0, 'data' => $c, 'msg' => json_encode($ids)));
        } else {
            echo json_encode(array('errno' => 2, 'msg' => ''));
        }
    }

    /*改变留言显示状态*/
    public function actionStatus($id)
    {
        $model = $this->findModel($id);
        $status = Yii::$app->request->get('status');

        $model->status = $status;
        $model->save(false);
        $this->redirect(['index']);
    }

    /*改变推荐状态*/
    /**
     * @param $id
     * @throws NotFoundHttpException
     */
    public function actionRecommend($id)
    {
        $model = $this->findModel($id);

        $model->recommend = $model->recommend == 1 ? $model->recommend = 2 : $model->recommend = 1;
        $model->save(false);
        $this->redirect(['index']);
    }

    /**
     * Finds the AdminCommemts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminCommemts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminCommemts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
