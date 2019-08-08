<?php

namespace backend\controllers;
use backend\models\AdminNotice;
use Yii;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * AdminNotice implements the CRUD actions for AdminNotice model.
 */
class AdminNoticeController extends Controller
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
     * Lists all AdminNotice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = AdminNotice::find()->joinWith('member');
        $querys = Yii::$app->request->get('query');
        if($querys['tel']){
            $query = $query->andWhere(['like','admin_member.tel',$querys['tel']]);
        }
        if($querys['title']){
            $query = $query->andWhere(['like','admin_notice.title',$querys['title']]);
        }
        $pageSize = (int)abs(\common\controllers\PublicController::getSysInfo(36));
        $pagination = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => $pageSize,
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page']
        );
        header("content-type:text/html;charset=utf-8");
        $model = $query
            ->orderBy('admin_notice.create_time desc')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index', [
            'model' => $model,
            'query' => $querys,
            'pages' => $pagination,
        ]);
    }

    // 删除
    public function actionDel($id)
    {
        $res = $this->findModel($id)->delete();
        if($res){
            return json_encode(['status'=>200,'msg'=>'删除成功']);
        }
        return json_encode(['status'=>100,'msg'=>'删除失败']);
    }

    // 批量删除
    public function actionDelrecord()
    {
        if(Yii::$app->request->isAjax){
            $ids = Yii::$app->request->post('ids');
            if(!$ids){
                throw new NotFoundHttpException('参数不合法');
            }
            $ids = explode(',',rtrim($ids,','));
            $res = AdminNotice::deleteAll(['in','id',$ids]);
            if($res){
                return json_encode(['status'=>200,'msg'=>'删除成功']);
            }else{
                return json_encode(['status'=>100,'msg'=>'删除失败']);
            }
        }
    }

    /**
     * Finds the AdminNotice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminNotice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminNotice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
