<?php

namespace backend\controllers;

use Yii;
use backend\models\AdminBuyAgent;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use common\helps\ExportExcelController;
use backend\models\AdminMember;
use common\controllers\PublicController;
use backend\models\AdminCommission;
use backend\models\AdminPay;
use backend\models\AdminGrade;
use mobile\controllers\BaseController as mBaseController;

/**
 * AdminBuyAgentController implements the CRUD actions for AdminBuyAgent model.
 */
class AdminBuyAgentController extends BaseController
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
     * Lists all AdminBuyAgent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = AdminBuyAgent::find()->joinWith('member')->joinWith('paytype')->joinWith('grade')
            ->where(['<>', 'status' , 0]);
        $querys = Yii::$app->request->get('query');
        $payname = AdminPay::find()->all();
        $grade_name = AdminGrade::find()->all();
        $payid = $querys['pay_id'];
        $grade_id = $querys['grade_id'];
        if (count($querys) > 0) {
            if ($querys['nickname']) {
                $query = $query->andWhere(['like', 'nickname', $querys['nickname']]);
            }
            if ($querys['pay_id'] != -1) {
                $query = $query->andWhere(['=', 'admin_buy_agent.type', $payid]);
            }
            if ($querys['grade_id'] != -1) {
                $query = $query->andWhere(['=', 'admin_grade.id', $grade_id]);
            }
            if ($querys['tel']) {
                $query = $query->andWhere(['like', 'tel', $querys['tel']]);
            }
            if ($querys['order_sn']) {
                $query = $query->andWhere(['like', 'order_sn', $querys['order_sn']]);
            }
            if ($querys['b_time']) {
                $query = $query->andWhere(['>=', 'admin_buy_agent.created_time', $querys['b_time']]);
            }
            if ($querys['e_time']) {
                $query = $query->andWhere(['<=', 'admin_buy_agent.created_time', $querys['e_time']]);
            }
        }
        $pageSize = (int)abs(PublicController::getSysInfo(36));
        $pagination = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => $pageSize,
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page']
        );
        $products = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('created_time DESC')
            ->all();
        return $this->render('index', [
            'model' => $products,
            'pages' => $pagination,
            'query' => $querys,
            'payname' => $payname,
            'grade_name' => $grade_name,
        ]);
    }

    /*
        测试分销专用
    */
    public function actionCommissiondd()
    {
        $money = 1000;
        $grade = 3;
        $num = 2;
        $rate = explode(',', PublicController::getSysInfo(7));    //1级 金牌
        $rate2 = explode(',', PublicController::getSysInfo(12));  //2级 银牌
        $rate3 = explode(',', PublicController::getSysInfo(13)); //3级  铜牌 0 1 2
        if ($grade != 0) {
            if ($grade == 3) { //  金牌 一 二 三 级
                foreach ($rate as $key => $value) {
                    if ($key == ($num - 1)) {
                        $commisssion = number_format($money * $value / 100, 2, '.', '');
                    }
                }
            }
            if ($grade == 2) { //  银牌 一 二 三 级
                foreach ($rate2 as $key => $value) {
                    if ($key == ($num - 1)) {
                        $commisssion = number_format($money * $value / 100, 2, '.', '');
                    }
                }
            }
            if ($grade == 1) { //  铜牌 一 二 三 级
                foreach ($rate3 as $key => $value) {
                    if ($key == ($num - 1)) {
                        $commisssion = number_format($money * $value / 100, 2, '.', '');
                    }
                }
            }
        } else {
            $commisssion = 0;
        }
        return $commisssion;
    }

    public function actionStatus()
    {
        $id = Yii::$app->request->post('id');
        $status = Yii::$app->request->post('status');
        $money = Yii::$app->request->post('je');
        $ju_content = Yii::$app->request->post('cont');
        $grade_id = Yii::$app->request->post('grade_id');
        $agent = AdminBuyAgent::find()->where(['id' => $id])->one();
        $agent->status = $status;
        $agent->money = $money;
        $agent->ju_content = $ju_content;
       
        // $this->actionDell($agent->order_sn,$grade_id);
        if ($agent->save(false)) {
            if ($agent->status == 1) {
                $this->actionDell($agent->order_sn, $grade_id);
                return 1;
            } else {
                return 1;
            }
        } else {
            return 2;
        }
    }
    /*
     
    */
    /*  $grade 等级
        $money 佣金
    */
    public function actionCommission($money, $grade, $num)
    {
        $rate = explode(',', PublicController::getSysInfo(7));    //1级 金牌
        $rate2 = explode(',', PublicController::getSysInfo(12));  //2级 银牌
        $rate3 = explode(',', PublicController::getSysInfo(13)); //3级  铜牌 0 1 2
        if ($grade != 0) {
            if ($grade == 3) { //  金牌 一 二 三 级
                foreach ($rate as $key => $value) {
                    if ($key == ($num - 1)) {
                        $commisssion = number_format($money * $value / 100, 2, '.', '');
                    }
                }
            }
            if ($grade == 2) { //  银牌 一 二 三 级
                foreach ($rate2 as $key => $value) {
                    if ($key == ($num - 1)) {
                        $commisssion = number_format($money * $value / 100, 2, '.', '');
                    }
                }
            }
            if ($grade == 1) { //  铜牌 一 二 三 级
                foreach ($rate3 as $key => $value) {
                    if ($key == ($num - 1)) {
                        $commisssion = number_format($money * $value / 100, 2, '.', '');
                    }
                }
            }
        } else {
            $commisssion = 0;
        }
        return $commisssion;
    }

    //审核通过分佣
    public function actionDell($order_sn, $grade_id)
    {
        header("Content-type:text/html;charset=utf-8");
        $model = AdminBuyAgent::find()->where(['order_sn' => $order_sn, 'status' => 1])->one();
        $member = AdminMember::find()->where(['id' => $model->user_id])->one();
        if ($grade_id == 1) {
            $msg = '铜牌会员';
        } elseif ($grade_id == 2) {
            $msg = '银牌会员';
        } elseif ($grade_id == 3) {
            $msg = '金牌会员';
        }
        /**********支付成功通知end**********/
        if ($member->grade != $grade_id) {
            $member->grade = $grade_id;
            $member->agent = 1;
            // $member->agent = 1;
            $member->save(false);
            if ($member->is_open == 1) {
                $title = '购买成功';
                $content = "您已经成功加入会员，赶快行动吧！";
                mBaseController::writeNotice($member->id,$title,$content);

                $url = Yii::$app->request->hostInfo."/index.php?r=index%2Findex&type=1";
                $temp = Yii::$app->params['wx']['sms']['buy_success'];
                $data['first'] = ['value' => '您好,您已成功成为' . $msg, 'color' => '#173177'];
                $data['keyword1'] = ['value' => '***会员卡', 'color' => '#173177'];
                $data['keyword2'] = ['value' => date('Y-m-d H:i', $model->created_time), 'color' => '#173177'];
                $data['keyword3'] = ['value' => '购买成功', 'color' => '#173177'];
                $data['keyword4'] = ['value' => $model->money, 'color' => '#173177'];
                $data['remark'] = ['value' => '您已经成功加入' . $msg . ',赶快行动吧!', 'color' => '#173177'];
                PublicController::sendTempMsg($temp, $member->openid, $data, $url);
            }
        }
        //查找上级
        $pre_member = AdminMember::find()->where(['invitation' => $member->bei_invitation])->one(); //1级
        $pre_member2 = AdminMember::find()->where(['invitation' => $pre_member->bei_invitation])->one();//2级
        $pre_member3 = AdminMember::find()->where(['invitation' => $pre_member2->bei_invitation])->one();//3级
        $pre_commission = $this->actionCommission($model->money, $pre_member->grade, 1);
        $pre_commission2 = $this->actionCommission($model->money, $pre_member2->grade, 2);
        $pre_commission3 = $this->actionCommission($model->money, $pre_member3->grade, 3);
        // echo $order_sn;exit;
        if ($pre_member) {
            //获取返佣比例
            $commission = AdminCommission::find()->where(['order_sn' => $order_sn])->one();
            if ($commission->status == 0) {
                $pre_member->promotion_commission += $pre_commission;
                $pre_member->available_money += $pre_commission;
                //生成返佣明细
                $commission->user_id = $pre_member->id;//用户id
                $commission->jy_user_id = $model->user_id;//交易人
                $commission->type = 1;
                $commission->status = 1;
                $commission->money = $model->money;
                $commission->commission_money = $pre_commission;
                $commission->created_time = time();
                $commission->openid = $pre_member->openid;
                $commission->jy_openid = $member->openid;
                $commission->save(false);
                $pre_member->save(false);
                if ($pre_member2 && $pre_commission2 != 0) { //2级
                    $commission = new AdminCommission();
                    $pre_member2->promotion_commission += $pre_commission2;
                    $pre_member2->available_money += $pre_commission2;
                    $commission->user_id = $pre_member2->id;
                    $commission->jy_user_id = $model->user_id;
                    $commission->type = 1;
                    $commission->status = 1;
                    $commission->money = $model->money;
                    $commission->commission_money = $pre_commission2;
                    $commission->created_time = time();
                    $commission->openid = $pre_member2->openid;
                    $commission->jy_openid = $member->openid;
                    $commission->save(false);
                    $pre_member2->save(false);
                }
                if ($pre_member3 && $pre_commission3 != 0) {  //3级\
                    $commission = new AdminCommission();
                    $pre_member3->promotion_commission += $pre_commission3;
                    $pre_member3->available_money += $pre_commission3;
                    $commission->user_id = $pre_member3->id;
                    $commission->jy_user_id = $model->user_id;
                    $commission->type = 1;
                    $commission->status = 1;
                    $commission->money = $model->money;
                    $commission->commission_money = $pre_commission3;
                    $commission->created_time = time();
                    $commission->openid = $pre_member3->openid;
                    $commission->jy_openid = $member->openid;
                    $commission->save(false);
                    $pre_member3->save(false);
                }
                /**********上级推送**********/
                //判断上级是否开启推送
                $temp = Yii::$app->params['wx']['sms']['income_receive'];
                if ($pre_member->is_open == 1) {
                    $title = "佣金到账提醒";
                    $content = "{$member->nickname}成功购买，已到您账户。收益金额：{$pre_commission}元；到账时间：".date('Y-m-d H:i', $model->created_time);
                    BaseController::writeNotice($pre_member->id,$title,$content);

                    $url = Yii::$app->request->getHostInfo() . '/index.php?r=member%2Faccount';
                    $data['first'] = ['value' => '佣金到账提醒', 'color' => '#173177'];
                    $data['income_amount'] = ['value' => $pre_commission . '/元', 'color' => '#173177'];
                    $data['income_time'] = ['value' => date('Y-m-d H:i', $model->created_time), 'color' => '#173177'];
                    $data['remark'] = ['value' => $member->nickname . "成功购买，已到您账户", 'color' => '#173177'];
                    PublicController::sendTempMsg($temp, $pre_member->openid, $data, $url);
                }
                if ($pre_member2->is_open == 1 && $pre_commission2 != 0) {
                    $title = "佣金到账提醒";
                    $content = "{$member->nickname}成功购买，已到您账户。收益金额：{$pre_commission}元；到账时间：".date('Y-m-d H:i', $model->created_time);
                    BaseController::writeNotice($pre_member->id,$title,$content);

                    $url = Yii::$app->request->getHostInfo() . '/index.php?r=member%2Faccount';
                    $data['first'] = ['value' => '佣金到账提醒', 'color' => '#173177'];
                    $data['income_amount'] = ['value' => $pre_commission2 . '/元', 'color' => '#173177'];
                    $data['income_time'] = ['value' => date('Y-m-d H:i', $model->created_time), 'color' => '#173177'];
                    $data['remark'] = ['value' => $member->nickname . "成功购买，已到您账户", 'color' => '#173177'];
                    PublicController::sendTempMsg($temp, $pre_member2->openid, $data, $url);
                }
                if ($pre_member3->is_open == 1 && $pre_commission3 != 0) {
                    $title = "佣金到账提醒";
                    $content = "{$member->nickname}成功购买，已到您账户。收益金额：{$pre_commission}元；到账时间：".date('Y-m-d H:i', $model->created_time);
                    BaseController::writeNotice($pre_member->id,$title,$content);

                    $url = Yii::$app->request->getHostInfo() . '/index.php?r=member%2Faccount';
                    $data['first'] = ['value' => '佣金到账提醒', 'color' => '#173177'];
                    $data['income_amount'] = ['value' => $pre_commission3 . '/元', 'color' => '#173177'];
                    $data['income_time'] = ['value' => date('Y-m-d H:i', $model->created_time), 'color' => '#173177'];
                    $data['remark'] = ['value' => $member->nickname . "成功购买，已到您账户", 'color' => '#173177'];
                    PublicController::sendTempMsg($temp, $pre_member3->openid, $data, $url);
                }
            }
            return true;
            Yii::$app->end();
            /***********上级推送end*********/
        } else {
            return true;
            Yii::$app->end();
        }

        
    }

    /**
     * Displays a single AdminBuyAgent model.
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
     * Creates a new AdminBuyAgent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminBuyAgent();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AdminBuyAgent model.
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
     * Deletes an existing AdminBuyAgent model.
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
            $c = AdminBuyAgent::deleteAll(['in', 'id', $ids]);
            echo json_encode(array('errno' => 0, 'data' => $c, 'msg' => json_encode($ids)));
        } else {
            echo json_encode(array('errno' => 2, 'msg' => ''));
        }
    }

    /**
     * Finds the AdminBuyAgent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminBuyAgent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminBuyAgent::findOne($id)) !== null) {
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
        $model = AdminBuyAgent::find()->joinWith('member')->where(['status' => 1])->asArray()->all();
        $data[] = ['序号', '微信昵称', '手机号码', '金额/￥ ', '购买时间'];
        foreach ($model as $k => $arr) {
            $data[$k + 1]['id'] = $arr['id'];
            $data[$k + 1]['user_id'] = $arr['member']['nickname'];
            $data[$k + 1]['tel'] = $arr['member']['tel'];
            $data[$k + 1]['money'] = $arr['money'];
            $data[$k + 1]['created_time'] = date('Y-m-d', $arr['created_time']);
        }
        $filename = '购买记录' . date('Ymd', time());
        $excel->download($data, $filename);
    }
}
