<?php

namespace backend\controllers;

use backend\models\AdminMember;
use backend\models\AdminWithdraw;
use common\controllers\PublicController;
use common\helps\ExportExcelController;
use Yii;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use mobile\controllers\BaseController as mBaseController;

/**
 * AdminWithdrawController implements the CRUD actions for AdminWithdraw model.
 */
class AdminWithdrawController extends \backend\controllers\BaseController
{
    /**
     * @inheritdoc
     */
    public $layout = "lte_main";
    public $status = [1 => '申请中', 2 => '成功', 3 => '失败'];

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
     * Lists all AdminWithdraw models.
     * @return mixed
     */
    public function actionIndex()
    {
        $uid = Yii::$app->user->identity->id;
        $query = AdminWithdraw::find()->joinWith('member')->orderBy('admin_withdraw.created_time DESC');
        $querys = Yii::$app->request->get('query');
        $query = self::condition($query, $querys);
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
            'status' => $this->status,
            'uid' =>$uid,
        ]);
    }

    public static function condition($query, $querys)
    {
        if (count($querys) > 0) {
            if ($querys['nickname']) {
                $query = $query->andWhere(['like', 'nickname', $querys['nickname']]);
            }
            if ($querys['status']>0){
                $query = $query->andWhere(['like', 'status', $querys['status']]);
            }
            if ($querys['tel']) {
                $query = $query->andWhere(['like', 'tel', $querys['tel']]);
            }
            if ($querys['b_time']) {
                $query = $query->andWhere(['>=', 'admin_withdraw.created_time', $querys['b_time']]);
            }
            if ($querys['e_time']) {
                $query = $query->andWhere(['<=', 'admin_withdraw.created_time', $querys['e_time']]);
            }
        }
        return $query;
    }

    /*
        是否打款
    */
    public function actionMake($id){
        $model = $this->findModel($id);
        $model->make = $model->make == 1 ? $model->make = 2 : $model->make = 1;
        $member = AdminMember::find()->where(['id'=>$model->user_id])->one();
        if($model->num==0){
            $model->num =1;
            $title = '提现成功';
            $content = "提现金额：{$model->money}元；处理时间：".date('Y-m-d H:i:s',$model->created_time);
            mBaseController::writeNotice($member->id,$title,$content);

            $url = Yii::$app->urlManager->createAbsoluteUrl(['member/withdraw-record']);
            $temp = Yii::$app->params['wx']['sms']['withdraw_result'];
            $data['first'] = ['value'=>'您有一笔提现申请处理结果如下','color'=>'#173177'];
            $data['keyword1'] = ['value'=>$model->money.'/元','color'=>'#173177'];
            $data['keyword2'] = ['value'=>'提现成功','color'=>'#173177'];
            $data['keyword3'] = ['value'=>date('Y-m-d H:i:s',$model->created_time),'color'=>'#173177'];
            $data['keyword4'] = ['value'=>'成功','color'=>'#173177'];
            $data['remark'] = ['value'=>'如有疑问，请及时联系客服','color'=>'#173177'];
            PublicController::sendTempMsg($temp,$member->openid,$data,$url);
        }
        $view ='index';
        $model->save(false);
        $this->redirect([$view]);
    }
    /**
     * Displays a single AdminWithdraw model.
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
     * Creates a new AdminWithdraw model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminWithdraw();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AdminWithdraw model.
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
     * Deletes an existing AdminWithdraw model.
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
            $c = AdminWithdraw::deleteAll(['in', 'id', $ids]);
            echo json_encode(array('errno' => 0, 'data' => $c, 'msg' => json_encode($ids)));
        } else {
            echo json_encode(array('errno' => 2, 'msg' => ''));
        }
    }

    /**
     * Finds the AdminWithdraw model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminWithdraw the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminWithdraw::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     *审核
     */
    public function actionChange()
    {
        $id = intval(Yii::$app->request->post('id'));
        $status = intval(Yii::$app->request->post('status'));
        $model = AdminWithdraw::findOne($id);
        if($model->status != 1) {
            return 200;
        }else {
            $model->status = $status;
            $model->update_time = time();
            if($status == 2) {
                $this->sendWithdrawSuccessSms($model->user_id,$model->money);
                if($model->save()) {
                    return 100;
                }else {
                    return 300;
                }
            }else if($status == 3) {
                $member = AdminMember::findOne($model->user_id);
                $member->available_money += $model->money;
                //审核不通过，将金额返回到用户的可提现金额中去
                $transaction = Yii::$app->db->beginTransaction();
                if ($member->validate() && $model->validate()) {
                    try {
                        $member->save();
                        $model->save();
                        $transaction->commit();
                        return 100;
                    } catch (Exception $e) {
                        //捕获错误
                        return 300;
                        $transaction->rollback();
                    }
                }else{
                    return 300;
                }
            }
        }
    }

    // 批量审核
    public function actionBatchAudit()
    {
        if(Yii::$app->request->isAjax){
            $status = (int)Yii::$app->request->post('status');
            $ids = Yii::$app->request->post('ids');
            $err = 0;
            if(empty($ids)){
                return $this->json(100,'请选择批量审核的记录');
            }
            foreach ($ids as $id){
                $withdraw = AdminWithdraw::findOne($id);
                $withdraw->status = $status;
                $withdraw->update_time = time();
                if($status == 2){ // 通过
                    $this->sendWithdrawSuccessSms($withdraw->user_id,$withdraw->money);
                    if(!$withdraw->save()){
                        $err++;
                    }
                }elseif($status == 3){  // 不通过
                    $member = AdminMember::findOne($withdraw->user_id);
                    $member->available_money += $withdraw->money;
                    $trans = Yii::$app->db->beginTransaction();
                    try{
                        $withdraw->save();
                        $member->save();
                        $trans->commit();
                    }catch (\Exception $e){
                        $err++;
                        $trans->rollBack();
                    }
                }
            }
            if($err > 0){
                return $this->json(100,'批量审核失败');
            }else{
                return $this->json(200,'批量审核成功');
            }
        }
    }

    // 推送模板消息（提现成功）
    public function sendWithdrawSuccessSms($userId,$money)
    {
        /***************推送模板消息 | 提现成功*******************/
        //推送模板消息
        $member = AdminMember::findOne($userId);
        if($member->is_open) {
            $title = '提现成功';
            $content = "提现金额：{$money}；时间：".date('Y-m-d H:i:s');
            mBaseController::writeNotice($member->id,$title,$content);

            $temp = Yii::$app->params['wx']['sms']['withdraw_accept'];
            $data['first'] = ['value'=>'提现成功','color'=>'#173177'];
            $data['keyword1'] = ['value'=>$money,'color'=>'#173177'];
            $data['keyword2'] = ['value'=>date('Y-m-d H:i:s'),'color'=>'#173177'];
            $data['remark'] = ['value'=>'感谢您的使用','color'=>'#173177'];
            PublicController::sendTempMsg($temp,$member->openid,$data,'');
        }
        /***************推送模板消息 | 提现成功*******************/
    }

    /**
     * 导出数据
     */
    public function actionExport()
    {
        $state = ['1'=>'申请中','2'=>'成功','3'=>'失败'];
        $excel = new ExportExcelController();
        $query = AdminWithdraw::find()->joinWith('member')->orderBy('admin_withdraw.created_time DESC');
        $querys = Yii::$app->request->get('query');
        $query = self::condition($query, $querys);
        $model = $query->asArray()->all();
        $data[] = ['序号','微信昵称','收款人姓名','收款人账号','手机号码','金额/￥','状态','申请时间'];
        foreach ($model as $k=> $arr) {
            $data[$k+1]['id'] = $arr['id'];
            $data[$k+1]['user_id'] = $arr['member']['nickname'];
            $data[$k+1]['account_name'] = $arr['member']['account_name'];
            $data[$k+1]['account_number'] = $arr['member']['account_number'];
            $data[$k+1]['tel'] = $arr['member']['tel'];
            $data[$k+1]['money'] = $arr['money'];
            $data[$k+1]['status'] = $state[$arr['status']];
            $data[$k+1]['created_time'] = date('Y-m-d',$arr['created_time']);
        }
        $filename = '提现记录'.date('Ymd',time());
        $excel->download($data, $filename);
    }
}

