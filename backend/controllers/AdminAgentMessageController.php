<?php

namespace backend\controllers;

use Yii;
use backend\models\AdminAgentMessage;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use backend\models\AdminMember;
use backend\models\AdminDaiProduct;
use common\helps\ExportExcelController;
use common\controllers\PublicController;

/**
 * AdminAgentMessageController implements the CRUD actions for AdminAgentMessage model.
 */
class AdminAgentMessageController extends Controller
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
     * Lists all AdminAgentMessage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = AdminAgentMessage::find()->joinwith('member')->joinwith('product')->orderby('admin_agent_message.create_time desc');
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
            ->limit($pagination->limit)
            ->all();
        return $this->render('index', [
            'model' => $products,
            'pages' => $pagination,
            'query' => $querys,
        ]);
    }

    // 搜索条件
    public static function condition($query,$querys)
    {
        if (count($querys) > 0) {
            $nickname = $querys['user_id'];
            $product = $querys['p_id'];
            $b_time = $querys['b_time'];
            $e_time = $querys['e_time'];
            if ($nickname) {
                $query = $query->andwhere(['like', 'nickname', $nickname]);
            }
            if ($product) {
                $query = $query->andwhere(['like', 'title', $product]);
            }
            if ($querys['type']==1 || $querys['type']==2) {
                $query = $query->andwhere(['admin_agent_message.type'=>$querys['type']]);
            }
            if ($b_time) {
                $query = $query->andWhere(['>=', 'admin_agent_message.create_time', $b_time]);
            }
            if ($e_time) {
                $query = $query->andWhere(['<=', 'admin_agent_message.create_time', $e_time]);
            }
        }
        return $query;
    }

    /**
     * Displays a single AdminAgentMessage model.
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
     * Creates a new AdminAgentMessage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminAgentMessage();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AdminAgentMessage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $nickname = AdminMember::find()->where(['id' => $model->user_id])->one();
        $product = AdminDaiProduct::find()->where(['id' => $model->pid])->one();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'nickname' => $nickname,
                'product' => $product,
            ]);
        }
    }

    /**
     * Deletes an existing AdminAgentMessage model.
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
            $c = AdminAgentMessage::deleteAll(['in', 'id', $ids]);
            echo json_encode(array('errno' => 0, 'data' => $c, 'msg' => json_encode($ids)));
        } else {
            echo json_encode(array('errno' => 2, 'msg' => ''));
        }
    }

    /**
     * Finds the AdminAgentMessage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminAgentMessage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminAgentMessage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 导出数据
     */
    public function actionExport()
    {
        $excel = new ExportExcelController();
        $model = AdminAgentMessage::find()->joinWith('member')->joinWith('product');
        $query = Yii::$app->request->get('query');
        $model = self::condition($model,$query);
        $model = $model->asArray()->all();
        $data[] = ['序号','微信昵称','手机号','产品名称','申请时间'];
        foreach ($model as $k=> $arr) {
            $data[$k+1]['id'] = $arr['id'];
            $data[$k+1]['user_id'] = $arr['member']['nickname'];
            $data[$k+1]['tel'] = $arr['member']['tel'];
            $data[$k+1]['p_id'] = $arr['product']['title'];
            $data[$k+1]['create_time'] = date('Y-m-d',$arr['create_time']);
        }
        $filename = '代理信息'.date('Ymd',time());
        $excel->download($data, $filename);
    }

    public function actionGetData($id)
    {
        $arr = [];
        $model = AdminAgentMessage::findOne($id);
        if($model) {
            $arr['state'] = 100;
            $arr['code'] = $model->promo_code;
            $arr['url'] = $model->promo_url;
        }else{
            $arr['state'] = 200;
        }
        return json_encode($arr);
    }

    /**
     * @return int
     */
    public function actionAllotPromo()
    {
        $id = Yii::$app->request->post('id');
        $code = Yii::$app->request->post('code');
        $url = Yii::$app->request->post('url');
        $model = AdminAgentMessage::findOne($id);
        $model->promo_code = $code;
        $model->promo_url = $url;
        $model->status = 1;
        $member = AdminMember::findOne($model->user_id);
        $product = AdminDaiProduct::findOne($model->p_id);
        if($model->save()) {
            /***************推送模板消息*******************/
            if($member->is_open) {
                $url = Yii::$app->request->getHostInfo().'/index.php?r=member/join-agent&id='.$model->p_id.'&type='.$product->type;
                //$url = Yii::$app->urlManager->createAbsoluteUrl(['member/customer-list']);
                $temp = '_CJi9PsldNaNmqEgW44tHkHXrwtZeTPcW955kqJgYnU';
                $data['first'] = ['value'=>'您好，您申请加入成功了！','color'=>'#173177'];
                $data['keyword1'] = ['value'=>$product->title,'color'=>'#173177'];
                $data['keyword2'] = ['value'=>date('Y-m-d H:i',$model->create_time),'color'=>'#173177'];
                $data['remark'] = ['value'=>'您可以在产品里面查看','color'=>'#173177'];
                PublicController::sendTempMsg($temp,$member->openid,$data,$url);
            }
            /***************推送模板消息*******************/
            return 100;
        }else {
            return 200;
        }
    }
}
