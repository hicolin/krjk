<?php

namespace backend\controllers;
use Yii;
use backend\models\AdminAnnounce;
use common\controllers\PublicController;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\web\UploadedFile;

/**
 * AdminAnnounce implements the CRUD actions for AdminAnnounce model.
 */
class AdminAnnounceController extends Controller
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
     * Lists all AdminAnnounce models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = AdminAnnounce::find();
        $querys = Yii::$app->request->get('query');

        if (count($querys) > 0) {
            $title = $querys['title'];
            if ($title) {
                $query = $query->andWhere(['like', 'title', $title]);
            }
        }
        $pageSize = (int)abs(PublicController::getSysInfo(36));
        $pagination = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => $pageSize,
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page']
        );
        header("content-type:text/html;charset=utf-8");
        $model = $query
                ->orderBy('create_time desc')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();
        return $this->render('index', [
            'model' => $model,
            'query' => $querys,
            'pages' => $pagination,
        ]);

    }

    /**
     * Displays a single AdminAnnounce model.
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
     * Creates a new AdminAnnounce model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminAnnounce();
        if ($model->load(Yii::$app->request->post())) {
            $model->title = PublicController::filter($model->title);
            $model->detail = PublicController::filter($model->detail, 2);
            $model->create_time = time();
            if ($model->save()) {
                return $this->redirect(['index']);
            } else {
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
     * Updates an existing AdminAnnounce model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->title = PublicController::filter($model->title);
            $model->detail = PublicController::filter($model->detail, 2);
            $model->create_time = time();
            if ($model->save()) {
                return $this->redirect(['index']);
            } else {
                return $this->redirect('update', [
                    'model' => $model,
                ]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AdminAnnounce model.
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
            $c = AdminAnnounce::deleteAll(['in', 'art_id', $ids]);
            echo json_encode(array('errno' => 0, 'data' => $c, 'msg' => json_encode($ids)));
        } else {
            echo json_encode(array('errno' => 2, 'msg' => ''));
        }
    }
    /**
     * Finds the AdminAnnounce model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminAnnounce the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminAnnounce::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
