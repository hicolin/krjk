<?php
namespace mobile\controllers;

use common\controllers\PublicController;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\WeChat;
use backend\models\AdminMember;
use backend\models\AdminDaiProduct;
use yii\helpers\Url;
use backend\models\AdminRegions;


/**
 * Site controller
 */
class YzmobileController extends MobileController
{

    public $enableCsrfValidation = false;
    private $_msg_template = array(
        'text' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content></xml>',//文本回复XML模板
        'image' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[image]]></MsgType><Image><MediaId><![CDATA[%s]]></MediaId></Image></xml>',//图片回复XML模板
        'music' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[music]]></MsgType><Music><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><MusicUrl><![CDATA[%s]]></MusicUrl><HQMusicUrl><![CDATA[%s]]></HQMusicUrl><ThumbMediaId><![CDATA[%s]]></ThumbMediaId></Music></xml>',//音乐模板
        'news' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[news]]></MsgType><ArticleCount>%s</ArticleCount><Articles>%s</Articles></xml>',// 新闻主体
        'news_item' => '<item><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><PicUrl><![CDATA[%s]]></PicUrl><Url><![CDATA[%s]]></Url></item>',//某个新闻模板
    );

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    public function init(){
        parent::init();   
    }

    /**
    * 微信绑定手机号
    */
    public function actionBdmobile()
    { 
        $nickname=Yii::$app->session['nickname'];
        $url=Yii::$app->session['url']; 
        if(!$nickname && !$url){
            return $this->redirect(['index/index']);
        }
        $this->getView()->title = '绑定手机号码';
        return $this->render('bdmobile', [
        ]);
    }

    /**
    * 绑定手机号 验证方法 
    * 沿用已有记录的用户名 以及密码 如果存在的话
    */
    public function actionBdmobileadd(){
        $tel = Yii::$app->request->post('tel');
        $code = Yii::$app->request->post('code');
        $password = Yii::$app->request->post('password');
        $code_num = Yii::$app->session->get('send_code');//发送短信后接收验证码
        $code_tel = Yii::$app->session->get('send_tel');//发送短信号码
        $subscribe=Yii::$app->session['subscribe'];
        $nickname=Yii::$app->session['nickname'];
        $head_img=Yii::$app->session['pic'];
        $openid=Yii::$app->session['openid'];
        $unionid=Yii::$app->session['unionid'];
        $invitation=Yii::$app->session['invitation'];
        $bei_invitation=Yii::$app->session['bei_invitation'];
        $created_time=Yii::$app->session['created_time'];
        $url=Yii::$app->session['url'];
        $data = AdminMember::find()->where('tel=' . $tel)->one();//查询是否有手机号码
        if($data){
            if($data->openid){
                Yii::$app->getSession()->setFlash('error', '绑定失败,手机号码已经绑定');
                echo "<script>window.history.go(-1)</script>";
                exit;
            }else{
                if (($code_num != $code) || ($code_tel != $tel)) {
                    Yii::$app->getSession()->setFlash('error', '绑定失败,手机号或验证码不正确');
                    echo "<script>window.history.go(-1)</script>";
                    exit;
                }else{ 
                    $data->tel = $tel;
                    if(!$data->passwd){
                        //加密密码
                        $data->passwd = Yii::$app->getSecurity()->generatePasswordHash($password);
                    } 
                    $data->subscribe = $subscribe;
                    if(!$data->nickname){
                        $data->nickname = $nickname;
                    }
                    if(!$data->pic){
                        $data->pic = $head_img;
                    }
                    if(!$data->invitation){
                        $data->invitation = $invitation;
                    } 
                    $data->openid = $openid;
                    $data->unionid = $unionid;  
                    $data->created_time = $created_time;
                    if($data->save(false)){
                        /******推送一条消息给推荐人******/
                        if($data->bei_invitation) {
                            $pre_member = AdminMember::find()->where(['invitation'=>$data->bei_invitation])->one();
                            //如果存在上级
                            if($pre_member) {
                                $title = '你有新的下级注册成功';
                                $content = "客户昵称：{$nickname};注册时间：".date('Y-m-d H:i:s',$data->created_time);
                                BaseController::writeNotice($pre_member->id,$title,$content);

                                $temp = Yii::$app->params['wx']['sms']['register_success'];
                                $sms_data['first'] = ['value'=>'你有新的下级注册成功','color'=>'#173177'];
                                $sms_data['keyword1'] = ['value'=>$nickname,'color'=>'#173177'];
                                $sms_data['keyword2'] = ['value'=>'暂无','color'=>'#173177'];
                                $sms_data['keyword3'] = ['value'=>'暂无','color'=>'#173177'];
                                $sms_data['keyword4'] = ['value'=>date('Y-m-d H:i:s',$data->created_time),'color'=>'#173177'];
                                $url_5 = Yii::$app->urlManager->createAbsoluteUrl(['member/customer']);
                                PublicController::sendTempMsg($temp,$pre_member->openid,$sms_data,$url_5);
                            }
                        }
                        /******推送一条消息给推荐人******/

                        Yii::$app->session->set('user_id',$data->id);
                        Yii::$app->session->set('tel',$data->tel);
                        $gourl = Yii::$app->urlManager->createAbsoluteUrl(['index/index']);
                        $url = $url ? $url :  $gourl ;
                        $this->redirect($url);
                        Yii::$app->end();     
                    }
                } 
            }
        }else{
            $model = new AdminMember();
            $model->subscribe = $subscribe;
            $model->tel =$tel;
            $model->passwd = Yii::$app->getSecurity()->generatePasswordHash($password);//加密密码
            $model->nickname = $nickname;
            $model->pic = $head_img;
            $model->openid = $openid;
            $model->unionid = $unionid; 
            $model->invitation = $invitation;
            $model->bei_invitation = $bei_invitation;
            $model->created_time = $created_time;
            if($model->save(false)) {
                /******推送一条消息给推荐人******/
                if($bei_invitation) {
                    $pre_member = AdminMember::find()->where(['invitation'=>$bei_invitation])->one();
                    //如果存在上级
                    if($pre_member) {
                        $title = '你有新的下级注册成功';
                        $content = "客户昵称：{$nickname};注册时间：".date('Y-m-d H:i:s',$data->created_time);
                        BaseController::writeNotice($pre_member->id,$title,$content);

                        $temp = Yii::$app->params['wx']['sms']['register_success'];
                        $data['first'] = ['value'=>'你有新的下级注册成功','color'=>'#173177'];
                        $data['keyword1'] = ['value'=>$nickname,'color'=>'#173177'];
                        $data['keyword2'] = ['value'=>'暂无','color'=>'#173177'];
                        $data['keyword3'] = ['value'=>'暂无','color'=>'#173177'];
                        $data['keyword4'] = ['value'=>date('Y-m-d H:i:s',$model->created_time),'color'=>'#173177'];
                        $url_5 = Yii::$app->urlManager->createAbsoluteUrl(['member/customer']);
                        PublicController::sendTempMsg($temp,$pre_member->openid,$data,$url_5);
                    }
                }
                /******推送一条消息给推荐人******/
                Yii::$app->session->set('user_id',$model->id);
                Yii::$app->session['pic']='';
                Yii::$app->session['send_tel']='';
                Yii::$app->session['openid']='';
                Yii::$app->session['unionid']='';
                Yii::$app->session['invitation']='';
                Yii::$app->session['bei_invitation']='';
                Yii::$app->session['invitation']='';
                return $this->redirect($url);
            }else{
                $this->actionLogin();
            }
        }
        exit;
    }



}
