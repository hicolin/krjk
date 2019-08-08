<?php
namespace mobile\controllers;

use backend\models\AdminAgentMessage;
use backend\models\AdminArticle;
use backend\models\AdminBankCard;
use backend\models\AdminCommission;
use backend\models\AdminDaiRecord;
use backend\models\AdminDrawMoney;
use backend\models\AdminMember;
use backend\models\AdminWithdraw;
use common\controllers\PublicController;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\AdminRegions;
use backend\models\AdminDaiProduct;
use common\utils\CommonFun;
use common\models\WeChat;
use yii\helpers\Url;
use backend\models\AdminBuyAgent;
use dosamigos\qrcode\QrCode;
use backend\models\AdminCount;


/**
 * Site controller
 */
//一键代理
class ShareController extends controller
{
 

    public function actionKeyagent()
    {           

         $this->getView()->title = '一键代理';
         return $this->render('keyagent');
    }

    // 一键推广
    public function actionImmediatePromotion(){
        $this->getView()->title = '一键推广';
        $user_id = Yii::$app->session->get('user_id');
        $url = Yii::$app->urlManager->createAbsoluteUrl(['share/loan-supermarket','uid'=>$user_id]);
        $res = \mobile\controllers\PublicController::getShortUrl($url);
        $res && $url = $res;
        $user_info = AdminMember::findOne($user_id);
        $wechat = new WeChat();
        $wechat->createImg2($user_info->tel,2);
        $str = substr(PublicController::getSysInfo(11),14,12);
        $pics = '/qrcode_temp2/'.$user_info->tel.$str.'.jpg';
        $picss = Yii::$app->request->hostInfo.$pics;
        return $this->render('immediate_promotion',compact('user_info','url','picss'));
    }

    /**
     * 生成图片二维码
     */
    public function actionQrcode()
    {    
         $url = Yii::$app->request->get('url');
         return QrCode::png($url, $size = 6, $margin = 1);    //调用二维码生成方法
    }
    /* 
        产品列表页
    */
    public function actionLoanSupermarket($type = 1)
    {
        $this->getView()->title = '贷款超市';
        $user_id = Yii::$app->request->get('uid');
        $member = AdminMember::findOne($user_id);
        $dai_product1 = AdminDaiProduct::find()->andwhere(['type' => 1, 'is_open' => 1])
            ->andwhere(['NOT', ['links' => '']])->asArray()->all();
        $fenpei = AdminAgentMessage::find()->where(['user_id' => $user_id, 'status' => 1, 'type' => 2])
            ->asArray()->all();
        $arr = array();
        $arr2 = array();
        foreach ($fenpei as $key => $value) {
            $arr[] = $value['p_id'];
        }
        foreach ($dai_product1 as $k => $list) {
            $arr2[] = $list['id'];
        }
        $arr3 = array_merge($arr2, $arr);
        $dai_product = AdminDaiProduct::find()->where(['in', 'id', $arr3])
            ->andwhere(['type' => 1, 'is_open' => 1])->Orderby('listorder asc')
            ->all();
        $account = AdminCount::find()->where(['is_match' => 1, 'apply_rate' => 3])
            ->andWhere(['>', 'money', 0])->andwhere(['!=', 'tel', '无'])
            ->orderBy('id desc')->limit(10)
            ->all();
        return $this->render('loan_supermarket',compact('type','dai_product','user_id','account','member'));
    }


    public function actionAgentmsg(){
        $sign = Yii::$app->request->get('sign');
        if($sign==1){
            $pid = Yii::$app->request->get('pid');
            $uid = Yii::$app->request->get('uid');
            $model = AdminAgentMessage::find()->where(['user_id'=>$uid,'p_id'=>$pid,'type'=>1])->one();
            $product =AdminDaiProduct::find()->where(['id'=>$pid])->one();
            // print_r($model);exit;
            if($product->links){
                if(!$model){
                    // echo "11";exit;
                    $agent = new AdminAgentMessage();
                    $agent->user_id = $uid;
                    $agent->p_id = $pid;
                    $agent->status = 1;
                    $agent->type = 1;
                    $agent->create_time = time();
                    if($agent->save(false)){
                        return 100;
                    }                    
                }else{
                        return 100;
                }
            }else{
                return 100;
            }
        }


    }


}