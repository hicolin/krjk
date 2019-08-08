<?php
namespace mobile\controllers;

use backend\models\AdminAnnounce;
use backend\models\AdminBanner;
use backend\models\AdminDaiProduct;
use backend\models\AdminHandCard;
use backend\models\AdminKey;
use backend\models\AdminMember;
use backend\models\AdminProductCategory;
use backend\models\AdminRegiones;
use common\controllers\PublicController;
use common\models\WeChat;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;


/**
 * Site controller
 */
class IndexController extends MobileController
{

    public $enableCsrfValidation = false;
    public $session;
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
    
    public function init() {
        $this->session = Yii::$app->session;
        parent::init();  
        // 非ajax 主动微信登录
        if (!Yii::$app->request->isAjax) {
            // $this->wxLogin();
        }
    }
    
    public function actionGgg(){
        include_once dirname(__FILE__).'/../../vendor/ucenter.php'; 
        //判断此用户名密码是否可登陆DZ
        $arr =  uc_user_login('13663002790', 'weixin321');
        var_dump($arr) ;  
    }

    public function api_get( $params = array() ){
        $url  = 'https://www.51kanong.com/plugin.php?id=third_data_mem_check:comiis_sms&sign=' . $this->_makesign( $params );
        $url .= '&'. http_build_query($params);
        return $this->curl_get( $url );
    }
    
    private function _makesign( $params = array() ){
        $params = array_filter( $params );
        ksort( $params );
        $secretkey = 'SSvrNpex4V7siI8NFc9rwF';
        $params['secretkey'] = $secretkey;
        $sign = strtoupper( md5( urldecode(http_build_query($params)) ));
        return $sign;
    }
    
    function curl_get( $url ){
        $ch = curl_init();
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $return_data = curl_exec($ch);
        curl_close($ch);
        return $return_data;
    }

    public function actionPush()
    {
        $weChat = new WeChat();
       // $weChat->firstValid();
        // PublicController::setMenu();
        $weChat->responseMsg();
    }

    // 首页
    public function actionIndex()
    {
        $style = (int)Yii::$app->request->get('style');
        $style || $style = 2;
        $user_id = Yii::$app->session['user_id'];
        $user_info = AdminMember::findOne($user_id);
        // 取出最新公告一条 并判断用户是否已经读取
        $announce = AdminAnnounce::find()->Orderby('create_time desc')->limit(1)->asArray()->one();
        $announceAlreadyShow = 0;
        if (isset($_COOKIE['ans_time_' . $announce['art_id']])) {
            $cookies = Yii::$app->request->cookies;//注意此处是request
            $anstime = $cookies->get('ans_time_' . $announce['art_id'], '');
            $cookTime = intval($anstime->value);
            if ($cookTime >= $announce['create_time']) {
                $announceAlreadyShow = 1;
            }
        }
        if (!$announce) $announceAlreadyShow = 1;
        if (!$announceAlreadyShow && $announce) {
            $cookies = Yii::$app->response->cookies;
            $cookies->add(new \yii\web\Cookie([
                'name' => 'ans_time_' . $announce['art_id'],
                'value' => $announce['create_time'],
                'expire' => time() + 3600 * 24 * 30
            ]));
        }
        $dealNum = AdminDaiProduct::find()->where(['style' => 1, 'is_open' => 1])->count();
        $reportNum = AdminDaiProduct::find()->where(['style' => 2, 'is_open' => 1])->count();
        $this->view->title = '首页';
        $categories = AdminProductCategory::find()->orderBy('sort asc')->asArray()->all();
        $data = [];
        foreach ($categories as $val){  // 组装分类下所有产品
            $products = AdminDaiProduct::find()->where(['style'=>$style,'is_open'=>1,'cate_id'=>$val['id']])
                ->orderBy('listorder asc')->asArray()->all();
            if ($user_id) {
                foreach ($products as &$list) {
                    $promUrl = Url::to(['other/sub-product', 'uid' => $user_id, 'pid' => $list['id']], true);
                    $list['shortUrl'] = \mobile\controllers\PublicController::getShortUrl($promUrl);
                }
            }
            if(!empty($products)){
                $data[$val['name']] = $products;
            }
        }
        $banners = AdminBanner::find()->all();
        return $this->render('index', compact( 'announce', 'user_info', 'announceAlreadyShow',
            'dealNum', 'reportNum','data', 'banners'));
    }

    // 我要贷款
    public function actionWantloan()
    {
        $this->getView()->title = '贷款超市';
        $user_id = Yii::$app->session->get('user_id');
        $categories = AdminProductCategory::find()->orderBy('sort asc')->asArray()->all();
        $data = [];
        foreach ($categories as $val){
            $products = AdminDaiProduct::find()->where(['is_open'=>1,'cate_id'=>$val['id']])->orderBy('listorder asc')->asArray()->all();
            if(!empty($products)){
                $data[$val['name']] = $products;
            }
        }
        return $this->render('wantloan', compact('type', 'data', 'user_id'));
    }

    /*
    * 公告列表
    */
    public function actionAnnounce(){
        Yii::$app->view->title='公告列表';
        $model = AdminAnnounce::find(); 
        $info = $model->where([])
                ->orderBy('create_time DESC')
                ->offset(0)->limit(50)
                ->all();
        return $this->render('announce',compact('info'));
    }
    /**
     * 详情
     */
    public function actionAnnounceDetail($id)
    {
        $model=AdminAnnounce::findOne($id);
        $this->getView()->title = '公告详情';
         return $this->render('announce_detail',[
            'model'=>$model,
         ]);
    }

    // 注册页面
    public function actionRegister()
    {
        $this->getView()->title = '用户注册';
        $sign = Yii::$app->request->get('sign');
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
            return $this->render('register', [

            ]);
            $sign = Yii::$app->request->get('sign');
            $invitation = Yii::$app->request->get('invitation');
            if (1 || $sign == 201) {
                $url = Yii::$app->urlManager->createAbsoluteUrl(['list/buy-agent', 'invitation' => $invitation]);
                $this->redirect($url);
                Yii::$app->end();
            }
        } else {
            return $this->render('register');
        }
    }

    //登陆页面
    public function actionLogin()
    {
        $this->getView()->title = '用户登陆';
        return $this->render('login');
    }

//找回密码页面
    public function actionForgetpass()
    {
        $this->getView()->title = '忘记密码';
        return $this->render('forgetpass');
    }

// 注册操作
    public function actionRegisteradd()
    {
        if (Yii::$app->request->isPost){
            $member = new AdminMember;
            $tel = Yii::$app->request->post('tel');
            $code = Yii::$app->request->post('code');
            $password = Yii::$app->request->post('password');
            $nickname = Yii::$app->request->post('nickname');
            $yqm = Yii::$app->request->post('yqm');
            $count = AdminMember::find()->where('tel=' . $tel)->count();
            $invitations = AdminMember::find()->where(['invitation' => $yqm])->one();//是否存在邀请码
            if ($yqm && !$invitations) {
                return $this->json(100, '邀请码不正确');
            }
            $code_num = Yii::$app->session->get('send_code');//发送短信后接收验证码
            $code_tel = Yii::$app->session->get('send_tel');//发送短信号码
            if (($code_num != $code) || ($code_tel != $tel)) {
                return $this->json(100, '手机号或验证码不正确');
            } elseif ($count > 0) {
                return $this->json(100, '手机号码已经注册');
            } else {
                $member->nickname = $nickname;
                $member->invitation = PublicController::getInvitation();
                $member->passwd = Yii::$app->getSecurity()->generatePasswordHash($password);//加密密码
                $member->bei_invitation = $yqm;
                $member->tel = $tel;
                $member->created_time = time();
                if ($member->save(false)) {
                    $url = PublicController::getSysInfo(33);
                    return $this->json(200, '注册成功,请下载APP', $url);
                }
            }
        }
    }

    //登陆操作
    public function actionLoginadd()
    {
        $tel = Yii::$app->request->post('tel');
        $password = Yii::$app->request->post('password');
        $model = AdminMember::find()->where(['tel' => $tel])->one();
       if(!isset($model->id)) {
           return $this->json(100, '登陆失败,没有此用户');
        } elseif ($model->is_block == 2){
           return $this->json(100, '此账号已被锁定，请联系管理员!');
        }elseif (Yii::$app->security->validatePassword($password, $model->passwd)) {
            $nickname = $model->nickname;
            //无论用户是否存在都去登陆dz
            Yii::$app->session->set('user_id', $model->id);
            Yii::$app->session->set('tel', $model->tel);
           // cookie 登录
            $flag = md5($model->tel.$model->passwd);
            $lifetime = time()+3600*24*30;
            setcookie('tel',$model->tel,$lifetime, '/');
            setcookie('flag',$flag,$lifetime, '/');
            return $this->json(200, '登陆成功');
        } else {
            return $this->json(100, '登陆失败，手机号或密码不正确');
        }
    }
    //UC同步登陆
    public function actionSynlogin(){
        if( Yii::$app->request->get('skey') != '=F^lsY5@PYVS7!is~|CpQ*5w55iqkRj' ) return;
        
        $nickname = Yii::$app->request->get('username');
        if( $nickname == null ) return;     
        
        //根据昵称判断是否存在，如存在则登陆
        $model = AdminMember::find()->where(['nickname' => $nickname])->one();
        if( isset($model->id) ){        
            Yii::$app->session->set('user_id', $model->id);
            Yii::$app->session->set('tel', $model->tel);
        }else{
            echo 'error';
        }
    }

    // 退出登陆
    public function actionLoginout()
    {
        Yii::$app->session['user_id'] = '';
        Yii::$app->session['tel'] = '';
        // 清除cookie登录
        setcookie('tel','',time() - 3600, '/');
        setcookie('flag','',time() - 3600, '/');
        Yii::$app->getSession()->setFlash('success', '退出成功');
        $url = Yii::$app->urlManager->createAbsoluteUrl(['index/index', 'type' => 1]);
        return $this->redirect($url);
    }

    //找回密码
    public function actionFindpassword()
    {
        $tel = Yii::$app->request->post('tel');
        $code = Yii::$app->request->post('code');
        $password = Yii::$app->request->post('password');
        $code_num = Yii::$app->session->get('send_code');//发送短信后接收验证码
        $code_tel = Yii::$app->session->get('send_tel');//发送短信号码
        $model = AdminMember::find()->where(['tel' => $tel])->one();
        if (($code_num != $code) || ($code_tel != $tel)) {
            Yii::$app->getSession()->setFlash('error', '找回密码失败,手机号或验证码不正确');
            echo "<script>window.history.go(-1)</script>";
            exit;
        } elseif ($model) {
            $model->passwd = Yii::$app->getSecurity()->generatePasswordHash($password);
            if ($model->save(false)) {
                Yii::$app->getSession()->setFlash('success', '找回密码成功');
                $url = Yii::$app->urlManager->createAbsoluteUrl('index/login');
                $this->redirect($url);
                Yii::$app->end();
            } else {
                Yii::$app->getSession()->setFlash('error', '找回密码失败');
                echo "<script>window.history.go(-1)</script>";
                exit;
            }
        }else{
            Yii::$app->getSession()->setFlash('error', '找回密码失败,无此用户');
            echo "<script>window.history.go(-1)</script>";
            exit;
        }
    }
    /**
     * 发送短信配置信息 注册
     */
    public function actionTsms($tel)
    {
        $telone = AdminMember::find()->where(['tel' => $tel])->count();
        if ($telone > 0) {
            $data['telone'] = $telone;
            return json_encode($data);
        } else {
            $code = rand(100000, 999999);
          //  $code = 123456;
           // $param = json_encode(['code' => "$code"]);
            Yii::$app->session->set('send_code', $code);
            Yii::$app->session->set('send_tel', $tel);
            if(PublicController::setCodes2($tel,$code)){
                 $data['msg']  = -200;
            }
            return json_encode($data);
            // return PublicController::sendSms($tel, $param, $temp);
        }
    }
    /**
     * 发送短信配置信息 ,找回密码/验证码
     */
    public function actionSms($tel,$temp)
    {       
        $code = rand(100000, 999999);
       // $param = json_encode(['code' => "$code"]);
        Yii::$app->session->set('send_code',$code);
        Yii::$app->session->set('send_tel',$tel);
        if(PublicController::setCodes2($tel,$code)){
             $data['msg'] = -200;
             return json_encode($data);
        }
    }
    //微信端短信发送
    public function actionMixWsms($tel, $temp = 'lxdkcs_02'){
        $code = rand(100000, 999999);
       //  $code = 123456;
       // $param = json_encode(['code' => "$code"]);
        Yii::$app->session->set('send_code', $code);
        Yii::$app->session->set('send_tel', $tel);
        // $vercode = PublicController::sendSms($tel, $param, $temp);
        $vercode = PublicController::setCodes2($tel,$code);
        //判断用户是否存在
        $model = AdminMember::find()->where(['tel' => $tel])->one();
       // $vercode = true;
        if( $vercode){
            return json_encode([ 'status'=>1,'info'=>0,'isU'=>$model ? 1 : 0 ] );
        }else{
            return json_encode([ 'status'=>0,'info'=>'发送失败'] ) ;
        }
    }

    public function actionWsms($tel, $temp = 'lxdkcs_02')
    {
        $code = rand(100000, 999999);
        // $code = 123456;
        $param = json_encode(['code' => "$code"]);
        Yii::$app->session->set('send_code', $code);
        Yii::$app->session->set('send_tel', $tel);
        if(PublicController::setCodes2($tel,$code)){
            return 1;
        }else{
            return 2;
        }
        // return PublicController::sendSms($tel, $param, $temp);
    }
    //发送验证码
    /**
     * 更新菜单
     */
    public function actionUpdateMenu()
    {
        if (PublicController::setMenu()) {
            return 200;
        } else {
            return 100;
        }
    }

    /**
     * 删除菜单
     */
    public function actionDelMenu()
    {
        if (PublicController::delMenu()) {
            return 200;
        } else {
            return 100;
        }
    }
    /* 
        一键入驻
    */
    public function actionKeyEnter(){
        $this->view->title = '一键入住';
        $region=new AdminRegiones();
        $regions=$region->find()->where(['parent_id'=>1])->all();
        return $this->render('keyenter', [
            'regions'=>$regions,
        ]);
    }
    //一键入驻数据处理
    public function actionSubData(){
        $name = Yii::$app->request->post('name');
        $tel = Yii::$app->request->post('tel');
        $yzm = Yii::$app->request->post('code');
        $sex = Yii::$app->request->post('sex');
        $msg = Yii::$app->request->post('msg');
        $province = Yii::$app->request->post('province');
        $city = Yii::$app->request->post('city');
        $area = Yii::$app->request->post('area');
        $address = Yii::$app->request->post('address');
        $code_num = Yii::$app->session->get('send_code');//发送短信后接收验证码
        $code_tel = Yii::$app->session->get('send_tel');//发送短信号码
        if ($code_num != $yzm) {
            return 300;
        }
       if($code_tel != $tel){
            return 200;
        }
        $key = new AdminKey(); 
        $key->name =$name;
        $key->mobile =$tel;
        $key->sex =$sex;
        $key->province =$province;
        $key->city = $city;
        $key->area = $area;
        $key->address =$address;
        $key->content =$msg;
        $key->created_time = time();
        if($key->save(false)){
             return 100;
        }   
    }

    public function actionGetAddress(){
        $id = Yii::$app->request->get('id');
        $result = AdminRegiones::find()->where('parent_id='.$id)->asArray()->all();
        return json_encode($result);
    }

    public function actionCard()
    {
        $this->getView()->title = '我要办卡';
        $handcard = AdminHandCard::find()->orderBy('create_time desc')->all();
        return $this->render('card', compact('handcard'));
    }

    public function actionClassify() {
        $this->getView()->title = '分类大全';
        $userId = Yii::$app->session['user_id'];
        if (!$userId) {
            return $this->redirect(['index/login']);
        }
        $member = AdminMember::findOne($userId);
        if ($member->grade == 0) { // 普通会员
            return $this->redirect(['index/buy-agent-remind']);
        }
        $url = PublicController::getSysInfo(35);
        return $this->redirect($url);
    }

    public function actionBuyAgentRemind()
    {
        $this->getView()->title = '提示';
        return $this->render('buy-agent-remind');
    }
}
