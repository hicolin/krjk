<?php
namespace mobile\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use mobile\controllers\WeChatController;
use yii\helpers\Url;
use backend\models\AdminBuyAgent;
use backend\models\AdminCommemts;
use backend\models\AdminCommission;
use backend\models\AdminDaiList;
use backend\models\AdminGoods;
use backend\models\AdminMember;
use common\controllers\PublicController;

/**
 * 异步通知回调控制器
 * 统一处理 支付后的回调逻辑
 */

class NotifyController extends MobileController
{

    public function init()
    {
        parent::init();    
        //只允许本机调用　判断本机调用　

    }

    /**
    * 支付状态回调完后 该方法进行响应
    * 需要传入订单编号 进行引导 
    */
    public function actionDell()
    {
        $order_id = Yii::$app->request->get('order_id');
        $model = AdminBuyAgent::find()->where(['order_sn'=>$order_id,'status'=>1])->one();
        if ( !$model ) {
        	return $this->ajaxOut(0,'订单不存在') ;
        	exit() ; 
        }
        $member= AdminMember::find()->where(['id'=>$model->user_id])->one();
        if ( !$model ) {
        	return $this->ajaxOut(0,'用户不存在') ;
        }
        if($model->is_dell){
        	return $this->ajaxOut(0,'已处理') ;
        }
        $model->is_dell = 1 ; 
        $model->save(false) ; //更新订单表　已处理佣金 
        // 支付完成后 模版通知
        if($member->agent==0){
            $member->agent = 1;
            $member->grade = 3;
            $member->save(false);
            if($member->is_open==1) {
                $title = '购买成功';
                $content = "您已经成功加入会员，赶快行动吧！";
                BaseController::writeNotice($member->id,$title,$content);

                $url = Yii::$app->urlManager->createAbsoluteUrl(['member/product','type'=>1]);
                $temp = Yii::$app->params['wx']['sms']['buy_success'];
                $data['name'] = ['value'=>'加入会员','color'=>'#173177'];
                $data['remark'] = ['value'=>'您已经成功加入会员，赶快行动吧！','color'=>'#173177'];
                PublicController::sendTempMsg($temp,$member->openid,$data,$url);
            }
        }
        //查找上级
        $pre_member = AdminMember::find()->where(['invitation'=>$member->bei_invitation])->one();
        if($pre_member) {
            //获取返佣比例
            $commission = AdminCommission::find()->where(['order_sn'=>$order_id])->one();
	        if($commission->status==0){
	            $rate = intval(PublicController::getSysInfo(7));
	            $pre_member->promotion_commission += number_format($model->money*$rate/100,2);
	            $pre_member->available_money += number_format($model->money*$rate/100,2);
	            //生成返佣明细
	            // $commission = new AdminCommission();
	            $commission->user_id = $pre_member->id;
	            $commission->jy_user_id = $model->user_id;
	            $commission->type = 1;
	            $commission->status= 1;
	            $commission->money = $model->money;
	            $commission->commission_money = number_format($model->money*$rate/100,2);;
	            $commission->created_time = time();
	            $commission->openid = $pre_member->openid;
	            $commission->jy_openid = $member->openid;
	            $commission->save(false);
	            $pre_member->save(false);
	            /**********上级推送**********/
	            if($pre_member->is_open) {
                    $title = "佣金到账提醒";
                    $content = "{$member->nickname}成功购买，已到账。收益金额：".($model->money*intval(PublicController::getSysInfo(7))/100).'/元；到账时间：'.date('Y-m-d H:i:s',time());
                    BaseController::writeNotice($pre_member->id,$title,$content);

	                $url = Yii::$app->urlManager->createAbsoluteUrl(['member/customer']);
	                $temp = Yii::$app->params['wx']['sms']['income_receive'];
	                $data['first'] = ['value'=>'佣金到账提醒','color'=>'#173177'];
	                $data['RightName'] = ['value'=>'加入会员','color'=>'#173177'];
	                $data['DealType'] = ['value'=>'购买成功','color'=>'#173177'];
	                $data['Money'] = ['value'=>($model->money*intval(PublicController::getSysInfo(7))/100).'/元','color'=>'#173177'];
	                $data['remark'] = ['value'=>$member->nickname."成功购买，已到账",'color'=>'#173177'];
	                PublicController::sendTempMsg($temp,$pre_member->openid,$data,$url);
	            }
	        }
	        return $this->ajaxOut(1,'处理over') ;
        }else{
        	return $this->ajaxOut(1,'处理over') ;
        }
    }

}
