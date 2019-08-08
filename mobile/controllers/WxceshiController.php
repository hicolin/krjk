<?php
namespace mobile\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\AdminRegions;
use backend\models\AdminMember;
use common\controllers\PublicController;
use common\models\WeChat;
/**
 * Site controller
 */
class WxceshiController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction='index';
    public $enableCsrfValidation = false ;
    public $request;
    public $user_id;
    public $user_info;
    public $AppID = 'wx54e7d01d4bdd34d9';
    //public $AppID = 'wxe62cbcb6a265bda4';
    public $AppSecret = 'ad0749e899c3178b1806518a0c99d708';
    //public $AppSecret = '776f300f60f9bb4212961ee4fda6fcc4';
    public $session;
    public $code;
    public $to_url;
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

    /**
     * init
     */
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->request = Yii::$app->request;
        $this->session = Yii::$app->session;

    }

    public function index(){

       

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
     * 兼容替换掉emoji表情
     * @param $text
     * @param string $replaceTo
     * @return mixed|string
     */
    protected function filterEmoji($text, $replaceTo = '?')
    {
        $clean_text = "";
        // Match Emoticons
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clean_text = preg_replace($regexEmoticons, $replaceTo, $text);
        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clean_text = preg_replace($regexSymbols, $replaceTo, $clean_text);
        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clean_text = preg_replace($regexTransport, $replaceTo, $clean_text);
        // Match Miscellaneous Symbols
        $regexMisc = '/[\x{2600}-\x{26FF}]/u';
        $clean_text = preg_replace($regexMisc, $replaceTo, $clean_text);
        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        $clean_text = preg_replace($regexDingbats, $replaceTo, $clean_text);
        return $clean_text;
    }

    /**
     * weChat Login
     */
    public function actionLogin()
    {
        //$url = Yii::$app->urlManager->createAbsoluteUrl('member/index');
        $url = $this->request->get('url');
        $invitation = $this->request->get('invitation');
        $code = $this->request->get('code'); 
        $url_1 = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->AppID."&secret=".$this->AppSecret."&code=".$code."&grant_type=authorization_code";
        //$refresh_token = $this->getData($url_1)->refresh_token;
        //var_dump( json_decode($this->file_get_contents_safe($url_1)) ) ; die ; 

        $refresh_token = json_decode($this->file_get_contents_safe($url_1))->refresh_token;  
        $url_2 = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=".$this->AppID."&grant_type=refresh_token&refresh_token=".$refresh_token;
        $openid = json_decode($this->file_get_contents_safe($url_2))->openid;
        //$openid = $this->getData($url_2)->openid;
        $access_token = json_decode($this->file_get_contents_safe($url_2))->access_token;
        $url_3 = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
        $data_wx = json_decode($this->file_get_contents_safe($url_3));
        $openid = $data_wx->openid;
        $nickname = $data_wx->nickname;
        $head_img  = $data_wx->headimgurl ;
        $unionid = $data_wx->unionid ; 
        $model = AdminMember::find()->where(['openid'=>$openid])->one();
        $user_info = $this->getUserInfo($openid);
        if(isset($user_info->subscribe)) {
            $subscribe = 1;
        }else {
            $subscribe = 0;
        }
        $nickname = $this->filterEmoji($nickname) ; 
        //如果存在这条数据
        if($model){
            if($model->tel){
               $model->nickname = $nickname;
                $model->pic = $head_img;
                $model->login_time = time();
                $model->subscribe = $subscribe;
                if($unionid){
                    $model->unionid = $unionid ; 
                }
                if($model->save(false)) {
                    $this->session->set('user_id',$model->id);
                    Yii::$app->request->referrer;
                    $this->redirect($url);
                    //Yii::$app->request->getReferrer()
                    Yii::$app->end();
                }
            }else{
                
             $urlb= Yii::$app->urlManager->createAbsoluteUrl(['yzmobile/bdmobile']);
             $this->redirect($urlb);Yii::$app->end();
            }

        }else{
            Yii::$app->session['subscribe']=$subscribe;
            Yii::$app->session['nickname']=$nickname;
            Yii::$app->session['pic']=$head_img;
            Yii::$app->session['openid']=$openid;
            Yii::$app->session['unionid']=$unionid;
            Yii::$app->session['invitation']=PublicController::getInvitation();
            Yii::$app->session['bei_invitation']=$invitation;
            Yii::$app->session['created_time']=time();
            Yii::$app->session['url']=$url;
            $urlb= Yii::$app->urlManager->createAbsoluteUrl(['yzmobile/bdmobile']);
            $this->redirect($urlb);Yii::$app->end();
        }

    }

    /**
     * 返回接口数据
     * @return json
     * @param url
     */
    protected function getData($url)
    {
        //print_r(json_decode($this->file_get_contents_safe($url)));exit;
        if(!$this->file_get_contents_safe($url)) {
            $this->getData($url);
        }
        return json_decode($this->file_get_contents_safe($url));
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        echo 111;exit;
        return $this->render('index');
    }

    /**
     * 拉取用户信息，UnionID机制
     */
    public function getUserInfo($openid)
    {
        $weChat = new WeChat();
        $access_token = $weChat->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $data = json_decode($this->file_get_contents_safe($url));
        return $data;
    }
}
