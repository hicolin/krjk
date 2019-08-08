<?php
namespace mobile\controllers;

use backend\models\AdminMember;
use common\models\WeChat;
use Yii;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use mobile\controllers\WeChatController;
use yii\helpers\Url;
use yii\web\Response;

/**
 * 手机模块 基础控制器
 */
class MobileController extends Controller
{

    public $AppID;

    public function init()
    {
        parent::init();
        $this->AppID = Yii::$app->params['wx']['app_id'];
        $userModel = $this->checkLogin();
       // if(!$userModel && !$this->isWx()){
        if(!$userModel){
            $this->checkCookieLogin();
        }
        // 账号状态检测
        if($userModel && $userModel->is_block == 2){  // 已登录且账号已锁定情况
            unset(Yii::$app->session['user_id']);
            unset(Yii::$app->session['tel']);
            $login_url = Yii::$app->urlManager->createAbsoluteUrl(['index/login']);
            return $this->redirect($login_url);
        }
    }

    // 检测Cookie登录
    public function checkCookieLogin()
    {
        $tel = $_COOKIE['tel'];
        $flag = $_COOKIE['flag'];
        if($tel && $flag){
            $member = AdminMember::findOne(['tel'=>$tel]);
            $str = md5($member->tel.$member->passwd);
            if($flag == $str){
                Yii::$app->session->set('user_id', $member->id);
                Yii::$app->session->set('tel', $member->tel);
            }
        }
    }

    // 判断是否为微信浏览器
    public function isWx()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        } else {
            return false;
        }
    }
    
    // 检测用户是否登录，未登录用户直接跳转登录页
    public function checkLoginStatus()
    {
        $userId = Yii::$app->session['user_id'];
        if(!$userId){
            return $this->redirect(['index/login']);
        }
    }

    /**
     * 拉取用户信息，UnionID机制
     */
    public  function getUserInfo($openid)
    {
        $weChat = new WeChat();
        $access_token = $weChat->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $data = json_decode($this->file_get_contents_safe($url));
        return $data;
    }

    /**
     * 兼容file_get_contengs 请求Https出错的情况
     */
    protected function file_get_contents_safe($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /** 
    * 检测用户是否已登录 
    **/
    public function checkLogin(){

        $user_id = Yii::$app->session['user_id'];
        if(!$user_id){
            return false ;
        }
        $model = AdminMember::findOne($user_id);
        if(!$model){
            Yii::$app->session->destroy(); 
            return false ;
        }
        return $model ; 
    }

    /**
    * 微信登录 公共方法 
    */
    public function wxLogin(){
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
            $to_url = Yii::$app->request->getHostInfo().Yii::$app->request->url;
//            return $this->redirect($to_url);exit;  //不做微信登录加的
            //echo $to_url ; die;
            $invitation = Yii::$app->request->get('invitation');
            $url = urlencode(Yii::$app->urlManager->createAbsoluteUrl(['we-chat/login','url'=>$to_url,'invitation'=>$invitation]));
            $wx_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->AppID."&redirect_uri=".$url."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
            Yii::$app->user->setReturnUrl(Yii::$app->request->referrer);
                $this->redirect($wx_url);
        }
    }

    /**
     * 功能描述：所有数据的统一返回接口
     * status 1 正常 0 异常
     * info 返回信息
     * data 返回数据 
     * @return string
     */
    public function ajaxOut($status=0,$msg='',$data=[]){
        $outputData = [
            'status'=>$status,
            'info'=>$msg,
            'data'=>$data
        ];
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->headers->add('pragma', 'no-chche');
        Yii::$app->response->data = $outputData;
        Yii::$app->response->send();
    }

    // 调试函数
    public static function dd()
    {
        $params = func_get_args();
        foreach ($params as $v){
            VarDumper::dump($v,10,true);
        }
        exit(1);
    }


    /**
     * 返回JSON信息
     * @param $status
     * @param $msg
     * @param string $url
     * @return string
     */
    public function json($status,$msg,$url='')
    {
        if(!$url){
            return json_encode(['status'=>$status,'msg'=>$msg]);
        }
        return json_encode(['status'=>$status,'msg'=>$msg,'url'=>$url]);
    }

    /**
     * 返回JSON数据
     * @param $status
     * @param $data
     * @param string $url
     * @return string
     */
    public function jsonData($status,$data,$url='')
    {
        if(!$url){
            return json_encode(['status'=>$status,'data'=>$data]);
        }
        return json_encode(['status'=>$status,'data'=>$data,'url'=>$url]);
    }


}
