<?php
namespace mobile\controllers;

use common\controllers\PublicController;
use backend\models\AdminCommission;
use backend\models\AdminMember;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\AdminBuyAgent;
/**
 * Site controller
 */

class PayController extends Controller
{
    /**
     * @inheritdoc
     */
    public $enableCsrfValidation = false ;

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionOrder()
    {
        $order_id=Yii::$app->request->get('order_id');
        $data=AdminOrders::findOne($order_id);
        if($data){
            return $this->render('order',['order'=>$data]);
        }
    }
    public function actionWxpay()
    {
       $order_id=Yii::$app->request->get('order_id');
        $data=AdminOrders::findOne($order_id);
        if($data){
            return $this->render('wxpay',['order_msg'=>$data]);
        }
    }
    public function actionAlipay()
    {
        $order_sn = Yii::$app->request->get('order_id');
        $model = AdminBuyAgent::find()->where(['order_sn'=>$order_sn])->one();
        if($model->status==1) {
            $url  = Yii::$app->urlManager->createAbsoluteUrl(['pay/dell','order_id'=> $order_sn]);
            return $this->redirect($url);
        }else{
            exit;
            Yii::$app->getSession()->setFlash('error', '订单支付失败');
           // $url  = Yii::$app->urlManager->createAbsoluteUrl(['pay/dell','order_id'=> $order_sn]);
           // return $this->redirect($url);
        }
    }

    /**
     * 异步处理购买后的数据
     */
    public function actionDell()
    {
        header("Content-type:text/html;charset=utf-8");
        $order_sn = Yii::$app->request->get('order_id');
        // 支付宝分佣补发标识
        $type = Yii::$app->request->get('type');
        $model = AdminBuyAgent::find()->where(['order_sn' => $order_sn, 'status' => 1])->one();
        // 防止补发分佣后的同步回调，再次分佣
        if($model->type == 1 && $model->fy_status == 1){
            return $this->redirect(['member/index']);
        }
        $member = AdminMember::find()->where(['id' => $model->user_id])->one();
        $grade_id = $model->grade_id;
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
            // $member->agent = 1;
            $member->save(false);
            if ($member->is_open == 1) {
                $title = '购买成功';
                $content = "您已经成功加入会员，赶快行动吧！";
                BaseController::writeNotice($member->id, $title, $content);
            }
        }
        //查找上级
        $pre_member = AdminMember::find()->where(['invitation' => $member->bei_invitation])->one(); //1级
        $pre_member2 = AdminMember::find()->where(['invitation' => $pre_member->bei_invitation])->one();//2级
        $pre_member3 = AdminMember::find()->where(['invitation' => $pre_member2->bei_invitation])->one();//3级
        $pre_commission = PublicController::commission($model->money, $pre_member->grade, 1);
        $pre_commission2 = PublicController::commission($model->money, $pre_member->grade, 2);
        $pre_commission3 = PublicController::commission($model->money, $pre_member->grade, 3);
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
                    $content = "{$member->nickname}成功购买，已到您账户。收益金额：{$pre_commission}元；到账时间：" . date('Y-m-d H:i', $model->created_time);
                    BaseController::writeNotice($pre_member->id, $title, $content);
                }
                if ($pre_member2->is_open == 1 && $pre_commission2 != 0) {
                    $title = "佣金到账提醒";
                    $content = "{$member->nickname}成功购买，已到您账户。收益金额：{$pre_commission}元；到账时间：" . date('Y-m-d H:i', $model->created_time);
                    BaseController::writeNotice($pre_member->id, $title, $content);
                }
                if ($pre_member3->is_open == 1 && $pre_commission3 != 0) {
                    $title = "佣金到账提醒";
                    $content = "{$member->nickname}成功购买，已到您账户。收益金额：{$pre_commission}元；到账时间：" . date('Y-m-d H:i', $model->created_time);
                    BaseController::writeNotice($pre_member->id, $title, $content);
                }
            }
            /***********上级推送end*********/
        }
        // 更改支付宝该笔订单的分佣状态
        $model->fy_status = 1;
        $model->save(false);
        if($type && $type == 'fy_reissue'){
            return $this->redirect(['member/index']);
        }
        return $this->render('result');
    }

    public function actionAlipayReturn()
    {
        $root = Yii::getAlias('@root');
        require_once $root . '/mobilealipay/alipay/config.php';
        require_once $root . '/mobilealipay/alipay/wappay/service/AlipayTradeService.php';
        $get = Yii::$app->request->get();
        $alipaySevice = new \AlipayTradeService($config);
        $result = $alipaySevice->check($get);
        if ($result) {
            return $this->redirect(['member/index']);
        }
    }

    public function actionAlipayNotify()
    {
        file_put_contents('test.txt',var_export($_POST,true));
        $root = Yii::getAlias('@root');
        require_once $root . '/mobilealipay/alipay/config.php';
        require_once $root . '/mobilealipay/alipay/wappay/service/AlipayTradeService.php';
        $post = Yii::$app->request->post();
        $alipaySevice = new \AlipayTradeService($config);
        $alipaySevice->writeLog(var_export($post,true));
        $result = $alipaySevice->check($post);
        if ($result) {
            $order_sn = $post['out_trade_no'];
            $total_amount = $post['total_amount'];
            if ($post['trade_status'] == 'TRADE_FINISHED') {
                // todo 判断订单在商户是否处理
            } else if ($post['trade_status'] == 'TRADE_SUCCESS') {
                $buyAgent = AdminBuyAgent::findOne(['order_sn' => $order_sn]);
                $member = AdminMember::findOne($buyAgent->user_id);
                if ($buyAgent->status == 0 && $buyAgent->money == $total_amount) {
                    file_put_contents('test.txt', "\n验证通过", FILE_APPEND);
                    $trans = Yii::$app->db->beginTransaction();
                    try {
                        $buyAgent->status =1;
                        $buyAgent->save(false);
                        $member->grade = $buyAgent->grade_id;
                        $member->save(false);
                        $this->dealCommission($buyAgent->order_sn);
                        $trans->commit();
                    } catch (\Exception $e) {
                        $trans->rollBack();
                    }
                }
            }
            echo "success";
        } else {
            echo "fail";
        }
    }

    public function dealCommission($order_sn)
    {
        $model = AdminBuyAgent::find()->where(['order_sn' => $order_sn])->one();
        $member = AdminMember::find()->where(['id' => $model->user_id])->one();
        $grade_id = $model->grade_id;
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
            // $member->agent = 1;
            $member->save(false);
            if ($member->is_open == 1) {
                $title = '购买成功';
                $content = "您已经成功加入会员，赶快行动吧！";
                BaseController::writeNotice($member->id, $title, $content);
            }
        }
        //查找上级
        $pre_member = AdminMember::find()->where(['invitation' => $member->bei_invitation])->one(); //1级
        $pre_member2 = AdminMember::find()->where(['invitation' => $pre_member->bei_invitation])->one();//2级
        $pre_member3 = AdminMember::find()->where(['invitation' => $pre_member2->bei_invitation])->one();//3级
        $pre_commission = PublicController::commission($model->money, $pre_member->grade, 1);
        $pre_commission2 = PublicController::commission($model->money, $pre_member->grade, 2);
        $pre_commission3 = PublicController::commission($model->money, $pre_member->grade, 3);
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
                    $content = "{$member->nickname}成功购买，已到您账户。收益金额：{$pre_commission}元；到账时间：" . date('Y-m-d H:i', $model->created_time);
                    BaseController::writeNotice($pre_member->id, $title, $content);

                    $url = Yii::$app->request->getHostInfo() . '/index.php?r=member%2Faccount';
                    $data['first'] = ['value' => '佣金到账提醒', 'color' => '#173177'];
                    $data['income_amount'] = ['value' => $pre_commission . '/元', 'color' => '#173177'];
                    $data['income_time'] = ['value' => date('Y-m-d H:i', $model->created_time), 'color' => '#173177'];
                    $data['remark'] = ['value' => $member->nickname . "成功购买，已到您账户", 'color' => '#173177'];
                    PublicController::sendTempMsg($temp, $pre_member->openid, $data, $url);
                }
                if ($pre_member2->is_open == 1 && $pre_commission2 != 0) {
                    $title = "佣金到账提醒";
                    $content = "{$member->nickname}成功购买，已到您账户。收益金额：{$pre_commission}元；到账时间：" . date('Y-m-d H:i', $model->created_time);
                    BaseController::writeNotice($pre_member->id, $title, $content);

                    $url = Yii::$app->request->getHostInfo() . '/index.php?r=member%2Faccount';
                    $data['first'] = ['value' => '佣金到账提醒', 'color' => '#173177'];
                    $data['income_amount'] = ['value' => $pre_commission2 . '/元', 'color' => '#173177'];
                    $data['income_time'] = ['value' => date('Y-m-d H:i', $model->created_time), 'color' => '#173177'];
                    $data['remark'] = ['value' => $member->nickname . "成功购买，已到您账户", 'color' => '#173177'];
                    PublicController::sendTempMsg($temp, $pre_member2->openid, $data, $url);
                }
                if ($pre_member3->is_open == 1 && $pre_commission3 != 0) {
                    $title = "佣金到账提醒";
                    $content = "{$member->nickname}成功购买，已到您账户。收益金额：{$pre_commission}元；到账时间：" . date('Y-m-d H:i', $model->created_time);
                    BaseController::writeNotice($pre_member->id, $title, $content);

                    $url = Yii::$app->request->getHostInfo() . '/index.php?r=member%2Faccount';
                    $data['first'] = ['value' => '佣金到账提醒', 'color' => '#173177'];
                    $data['income_amount'] = ['value' => $pre_commission3 . '/元', 'color' => '#173177'];
                    $data['income_time'] = ['value' => date('Y-m-d H:i', $model->created_time), 'color' => '#173177'];
                    $data['remark'] = ['value' => $member->nickname . "成功购买，已到您账户", 'color' => '#173177'];
                    PublicController::sendTempMsg($temp, $pre_member3->openid, $data, $url);
                }
            }
            /***********上级推送end*********/
        }
    }
    
}
