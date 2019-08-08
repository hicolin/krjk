<?php
namespace mobile\controllers;

use backend\models\AdminAgentMessage;
use backend\models\AdminArticle;
use backend\models\AdminAward;
use backend\models\AdminBankCard;
use backend\models\AdminBuyAgent;
use backend\models\AdminCategory;
use backend\models\AdminCommission;
use backend\models\AdminDaiProduct;
use backend\models\AdminDaiRecord;
use backend\models\AdminDrawMoney;
use backend\models\AdminLoanReport;
use backend\models\AdminMember;
use backend\models\AdminMemberPushstat;
use backend\models\AdminNotice;
use backend\models\AdminWithdraw;
use common\controllers\PublicController;
use common\models\UploadForm;
use common\models\WeChat;
use common\utils\CommonFun;
use mobile\controllers\PublicController as mPublicController;
use Yii;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\UploadedFile;
use mobile\controllers\Service;

/**
 * Site controller
 */
class MemberController extends BaseController
{
    /**
     * @inheritdoc
     */
    public $defaultAction='index';
    public $enableCsrfValidation = false ;
    public $user_id;
    public $user_info;
    public $AppID = 'wxb86a060a93449fcd';//微信公众账号
    public $AppSecret = 'f87705389bc1bf20a999c1e17455f457';///微信公众账号
    public $request;
    public $session;
    public $code;
    public $page_size = 15;
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
        parent::init();
        $this->request = Yii::$app->request;
        $this->session = Yii::$app->session;
        $this->user_id = $this->session->get('user_id');
        $this->user_info = AdminMember::findOne($this->user_id);
    }


    /**
     * 个人中心.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        //判断用户是否关注公众号
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
            if ($this->user_info->subscribe == 0) {
                echo '<div style="margin: 300px auto 0;width: 40%"><img style="width: 100%;" src="' . PublicController::getSysInfo(30) . '"></div>';
                echo '<div style="margin: 100px auto;width: 40%;font-size: 2.5rem;text-align: center">请关注公众号</div>';
                return;
            } else {
                $this->getView()->title = '我的后台';
                $this->user_info->tel = substr_replace($this->user_info->tel, '****', 3, 4);
                return $this->render('index', [
                    'user_info' => $this->user_info
                ]);
            }
        } else {
            if ($this->user_id) {
                $this->getView()->title = '我的后台';
                $this->user_info->tel = substr_replace($this->user_info->tel, '****', 3, 4);
                return $this->render('index', [
                    'user_info' => $this->user_info
                ]);
            } else {
                $url = Yii::$app->urlManager->createUrl('/index/index');
                Yii::$app->getSession()->setFlash('jump', '您还未登录,点击确定前往登录');
                Yii::$app->getSession()->setFlash('url', Url::toRoute(['index/login']));
                $this->redirect($url);
            }
        }

    }

    // 检查并处理支付宝分佣
    public function checkAlipayFyStatus($user_info)
    {
        $user_id = $user_info->id;
        $buy_agent = AdminBuyAgent::findOne(['user_id'=>$user_id,'status'=>1,'type'=>1,'fy_status'=>0]);
        if($buy_agent){
            $order_id = $buy_agent->order_sn;
            $type = 'fy_reissue';
            return $this->redirect(['pay/dell','order_id'=>$order_id,'type'=>$type]);
        }
    }

    /**
     * 个人信息
     */
    public function actionInfo()
    {
        if (!Yii::$app->session['user_id']) {
            return $this->redirect(['index/login']);
        }
        $this->getView()->title = '个人信息';
        $bei_invitation = $this->user_info->bei_invitation;
        $pre_member = AdminMember::findOne(['invitation' => $bei_invitation]);
        return $this->render('info', [
            'user_info' => $this->user_info,
            'pre_member' => $pre_member,
        ]);
    }

    /**
     * 修改手机号码
     */
    public function actionChangeTel()
    {
        $this->getView()->title = '修改手机号';
        if($this->request->isPost) {
            $tel = PublicController::filter($this->request->post('tel'));
            $code = $this->request->post('code');
            $state = $this->validateCode($tel,$code);
            if($state != 100) {
                return $state;
            }else {
                $model = AdminMember::findOne($this->user_id);
                $data = AdminMember::find()->where('tel=' . $tel)->one();//查询是否有手机号码
                if($data){
                    return 600;
                }
                $model->tel = $tel;
                if($model->save()) {
                    return 100;
                }else {
                    return 500;
                }
            }
        }
        return $this->render('change-tel',[
            'user_info'=> $this->user_info,
        ]);
    }

    /**
     * 修改密码
     */
    public function actionChangePsd()
    {
        $this->getView()->title = '修改密码';
        $tel = $this->user_info->tel;
        $this->user_info->tel = substr_replace($this->user_info->tel, '****', 3, 4);
        if($this->request->isPost) {
            $code = intval($this->request->post('code'));
            $psd1 = PublicController::filter($this->request->post('psd1'));
            $psd2 = PublicController::filter($this->request->post('psd2'));
            if($psd1 != $psd2) {
                return 101;     //两次密码不一样
            }else{
                $state = $this->validateCode($tel,$code);
                if($state != 100) {
                    return $state;
                }else {
                    $model = AdminMember::findOne($this->user_id);
                    $model->passwd = Yii::$app->security->generatePasswordHash($psd1);
                    if($model->save()) {
                        return 100;
                    }else {
                        return 500;
                    }
                }
            }
        }
        return $this->render('change-psd',[
            'user_info'=>$this->user_info,
            'tel'=>$tel,
        ]);
    }

    /**
     * 修改地址
     */
    public function actionChangeAddress()
    {
        $this->getView()->title = '修改地址';
        if($this->request->isPost) {
            $province = PublicController::filter($this->request->post('province'));
            $address = PublicController::filter($this->request->post('address'));
            $model = AdminMember::findOne($this->user_id);
            $model->province = $province;
            $model->address = $address;
            if($model->save()) {
                yii::$app->getSession()->setFlash('success', '修改成功');
                $url = Yii::$app->urlManager->createAbsoluteUrl('member/index');
                $this->redirect($url);
                Yii::$app->end();
            }else {
                yii::$app->getSession()->setFlash('error', '修改失败');
                echo "<script>window.history.go(-1)</script>";exit;
            }
        }
        return $this->render('change-address',[
            'user_info'=>$this->user_info,
        ]);
    }

    // 修改账户
    public function actionChangeAccount()
    {
        if(Yii::$app->request->isPost) {
            $account_number = PublicController::filter(Yii::$app->request->post('account_number'));
            $account_name = PublicController::filter(Yii::$app->request->post('account_name'));
            $model = AdminMember::findOne($this->user_id);
            $model->account_number = $account_number;
            $model->account_name = $account_name;
            if(!$account_number) {
                return 101;
            }else if(!$account_name) {
                return 102;
            }else {
                if($model->save()) {
                    return 200;
                }else {
                    return 500;
                }
            }
        }else {
            $this->getView()->title = '修改账户';
            return $this->render('change-account',[
                'user_info'=>$this->user_info,
            ]);
        }
    }

    // 上传付款码
    public function actionUploadPayCode()
    {
        if(Yii::$app->request->isAjax){
            $userId = Yii::$app->session['user_id'];
            if(!$userId){
                return $this->json(100,'请登录后重试');
            }
            $model = new UploadForm();
            $model->imageFile = UploadedFile::getInstanceByName('file');
            $res = $model->upload();
            $result = json_decode($res,true);
            if($result['status'] == 200){
                $member = AdminMember::findOne($userId);
                $member->pay_code = $result['path'];
                $member->save(false);
            }
            return $res;
        }
    }


    /**
     * 验证手机号码和验证码
     * @return integer
     */
    protected function validateCode( $tel='', $code='' )
    {
        $code_tel = $this->session->get('send_tel');
        $code_num = $this->session->get('send_code');
        if($tel != $code_tel) {
            return 200;     //不是当前手机号
        }else if(!$code_num) {
            return 300;     //验证码不正确
        }else if($code != $code_num) {
            return 300;
        }else{
            return 100;
        }
    }

    /**
     * 我的账户
     */
    public function actionAccount()
    {
        $this->getView()->title = '我的账户';
        $this->user_info->tel = substr_replace($this->user_info->tel, '****', 3, 4);
        $userId = $this->user_info->id;
        $award = AdminAward::find()->where(['user_id'=>$userId])->sum('money');

        $query = AdminCommission::find()->where(['user_id'=>$this->user_id,'type'=>2])
            ->orderBy('created_time DESC');
        if(Yii::$app->request->isPost) {
            //加载更多
            $page = Yii::$app->request->post('page');
            $model = $query->offset(($page-1)*$this->page_size)->limit($this->page_size)->all();
            $li = '';
            if($model) {
                foreach ($model as $list) {
                    $li .= '<tr><td>'.$list->p_name.'</td>';
                    $li .= '<td>' . Service::dealTrader($list->jy_user_info) . '</td>';
                    $li .= '<td>' . $list->commission_money .'</td>';
                    $li .= '<td>'.date('Y-m-d',$list->created_time).'</td></tr>';
                }
            }
            return $li;
        }else {
            $model = $query->offset(0)->limit($this->page_size)->all();
        }
        return $this->render('account',[
            'user_info'=>$this->user_info,
            'award'=>$award,
            'model' => $model,
        ]);
    }

    // 活动奖励记录
    public function actionActivityAward()
    {
        $this->view->title = '活动奖励';
        $userId = $this->user_info->id;
        $pageSize = 20;
        $query = AdminAward::find()->where(['user_id'=>$userId]);
        if(Yii::$app->request->isAjax){
            $page = Yii::$app->request->get('page');
            $awards = $query->orderBy('create_time desc')
                ->offset(($page-1)*$pageSize)
                ->limit($pageSize)
                ->asArray()->all();
            if(empty($awards)){
                return $this->json(100,'没有数据了');
            }
            foreach ($awards as &$list){
                $list['create_time'] = date('Y-m-d H:i',$list['create_time']);
            }
            return $this->jsonData(200,$awards);
        }
        $total = $query->count();
        $awards = $query ->orderBy('create_time desc')
            ->limit($pageSize)->asArray()->all();
        return $this->render('activity-award',compact('total','awards','pageSize'));
    }

    /**
     * 我的徒弟
     */
    public function actionCustomer()
    {
        $this->getView()->title = '我的粉丝';
        $user_info = $this->user_info;
        $this->user_info->tel = substr_replace($this->user_info->tel, '****', 3, 4);
        //取关注成功的徒弟
        $query =  AdminMember::find()->Where(['bei_invitation'=>$this->user_info->invitation]);
        $member = $query->orderBy('created_time DESC')
            ->offset(0)->limit($this->page_size)
            ->all();
        $member_num = $query->count();
        $query2 = AdminMember::find()->Where(['bei_invitation'=>$this->user_info->invitation]);
        $buy_member = $query2->andWhere(['>','grade',0])
            ->orderBy('created_time DESC')
            ->offset(0)->limit($this->page_size)
            ->all();
        $buy_member_num = $query->andWhere(['>','grade',0])->count();
        return $this->render('customer',compact('user_info','member','buy_member','member_num','buy_member_num'));
    }

    // 报表统计
    public function actionStatistic()
    {
        $this->getView()->title = '报表统计';
        $userId = Yii::$app->session['user_id'];
        if(!$userId){
            return $this->redirect(['index/login']);
        }
        $cpaPrice = trim(PublicController::getSysInfo(37));
        $cpsPrice = trim(PublicController::getSysInfo(38));
        $member = AdminMember::findOne($userId);
        $members = AdminMember::findAll(['bei_invitation'=>$member->invitation]);
        $ids = mPublicController::getSonIds($members);
        array_unshift($ids,$userId);  // 自身id添加进数组
        $now = time();
        $b_month = strtotime(date('Y-m-01 00:00:00'));
        $b_day = strtotime(date('Y-m-d').' 00:00:00');
        $b_week = $b_day - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600;
        // 团队成员
        $allMembers = AdminMember::find()->where(['in','id',$ids])->count();
        $monthMembers = AdminMember::find()->where(['in','id',$ids])
            ->andWhere(['between','created_time',$b_month,$now])->count();
        $weekMembers = AdminMember::find()->where(['in','id',$ids])
            ->andWhere(['between','created_time',$b_week,$now])->count();
        // 订单数量
        $allOrders = AdminDaiRecord::find()->where(['in','tid',$ids])->andWhere(['match_num'=>1])
            ->count();
        $monthOrders = AdminDaiRecord::find()->where(['in','tid',$ids])->andWhere(['match_num'=>1])
            ->andWhere(['between','match_time',$b_month,$now])->count();
        $weekOrders = AdminDaiRecord::find()->where(['in','tid',$ids])->andWhere(['match_num'=>1])
            ->andWhere(['between','match_time',$b_week,$now])->count();
        $allCpaOrders = AdminDaiRecord::find()->joinWith('product')
            ->where(['in','tid',$ids])->andWhere(['match_num'=>1])
            ->andWhere(['admin_dai_product.fy_type' => 1])->count();
        $allCpsOrders = AdminDaiRecord::find()->joinWith('product')
            ->where(['in','tid',$ids])->andWhere(['match_num'=>1])
            ->andWhere(['admin_dai_product.fy_type' => 2])->count();
        $monthCpaOrders = AdminDaiRecord::find()->joinWith('product')
            ->where(['in','tid',$ids])->andWhere(['match_num'=>1])
            ->andWhere(['between','match_time',$b_month,$now])
            ->andWhere(['admin_dai_product.fy_type' => 1])->count();
        $monthCpsOrders = AdminDaiRecord::find()->joinWith('product')
            ->where(['in','tid',$ids])->andWhere(['match_num'=>1])
            ->andWhere(['between','match_time',$b_month,$now])
            ->andWhere(['admin_dai_product.fy_type' => 2])->count();
        $weekCpaOrders = AdminDaiRecord::find()->joinWith('product')
            ->where(['in','tid',$ids])->andWhere(['match_num'=>1])
            ->andWhere(['between','match_time',$b_week,$now])
            ->andWhere(['admin_dai_product.fy_type' => 1])->count();
        $weekCpsOrders = AdminDaiRecord::find()->joinWith('product')
            ->where(['in','tid',$ids])->andWhere(['match_num'=>1])
            ->andWhere(['between','match_time',$b_week,$now])
            ->andWhere(['admin_dai_product.fy_type' => 2])->count();

        // 收入金额
//        $query = AdminAward::find()->where(['in','user_id',$ids]);
//        $allAward = $query->sum('money');
//        $monthAward = $query->andWhere(['between','create_time',$b_month,$now])->sum('money');
//        $weekAward = $query->andWhere(['between','create_time',$b_week,$now])->sum('money');
        $allAward = $allCpaOrders * $cpaPrice + $allCpsOrders * $cpsPrice;
        $monthAward = $monthCpaOrders * $cpaPrice + $monthCpsOrders * $cpsPrice;
        $weekAward = $weekCpaOrders * $cpaPrice + $weekCpsOrders * $cpsPrice;

        if (Yii::$app->request->isPost) {
            $pickDate = Yii::$app->request->post('pickDate');
            $begin = $pickDate . '-01';
            $beginTime = strtotime($begin);
            $endTime = strtotime("{$begin} + 1 month");
            $members = AdminMember::find()->where(['in','id',$ids])
                ->andWhere(['between','created_time',$beginTime,$endTime])->count();
            $orders = AdminDaiRecord::find()->where(['in','tid',$ids])->andWhere(['match_num'=>1])
                ->andWhere(['between','match_time',$beginTime,$endTime])->count();
            $cpaOrders = AdminDaiRecord::find()->joinWith('product')
                ->where(['in','tid',$ids])->andWhere(['match_num'=>1])
                ->andWhere(['between','match_time',$beginTime,$endTime])
                ->andWhere(['admin_dai_product.fy_type' => 1])->count();
            $cpsOrders = AdminDaiRecord::find()->joinWith('product')
                ->where(['in','tid',$ids])->andWhere(['match_num'=>1])
                ->andWhere(['between','match_time',$beginTime,$endTime])
                ->andWhere(['admin_dai_product.fy_type' => 2])->count();
//            $award = AdminAward::find()->where(['in','user_id',$ids])
//                ->andWhere(['between','create_time',$beginTime,$endTime])->sum('money');
            $award = $cpaOrders * $cpaPrice + $cpsOrders * $cpsPrice;
            $data = compact('members', 'orders', 'award', 'cpaOrders', 'cpsOrders');
            return $this->json(200, '获取成功', $data);
        }
        return $this->render('statistic',compact('allMembers','monthMembers','weekMembers','allOrders',
            'monthOrders','weekOrders','allAward','monthAward','weekAward', 'allCpsOrders', 'allCpaOrders',
            'monthCpaOrders', 'monthCpsOrders', 'weekCpaOrders', 'weekCpsOrders'));
    }

    /**
     * 代呗返佣明细
     * @return string
     */
    public function actionDaiCommission()
    {
        $this->getView()->title = '返现记录';
        $query = AdminCommission::find()->where(['user_id'=>$this->user_id,'type'=>2])
            ->orderBy('created_time DESC');
        if(Yii::$app->request->isPost) {
            //加载更多
            $page = Yii::$app->request->post('page');
            $model = $query->offset(($page-1)*$this->page_size)->limit($this->page_size)->all();
            $li = '';
            if($model) {
                foreach ($model as $list) {
                    $li .= '<tr><td>'.$list->p_name.'</td>';
                    $li .= '<td>' . Service::dealTrader($list->jy_user_info) . '</td>';
                    $li .= '<td>' . $list->commission_money .'</td>';
                    $li .= '<td>'.date('Y-m-d',$list->created_time).'</td></tr>';
                }
            }
            return $li;
        }else {
            $model = $query->offset(0)->limit($this->page_size)->all();
            return $this->render('dai-commission',[
                'model'=>$model,
            ]);
        }
    }

    /**
     * 生成海报
     */
    public function actionPoster()
    {
        $this->getView()->title = '生成海报';
        $a = 1;
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == $a) {  //前面加了一个！，直接走移动端生成海报
            $url = Yii::$app->urlManager->createAbsoluteUrl(['list/buy-agent', 'invitation' => $this->user_info->invitation]);
            $res = \mobile\controllers\PublicController::getShortUrl($url);
            $res && $url = $res;
            $wechat = new WeChat();
            $wechat->createImg($this->user_info->openid, 2);
            $str = substr(PublicController::getSysInfo(9), 14, 12);
            return $this->render('poster', [
                'user_info' => $this->user_info,
                'url' => $url,
                'pic' => Url::base() . '/qrcode_temp/' . $this->user_info->openid . $str . '.jpg',
            ]);
        } else {
            $url = Yii::$app->urlManager->createAbsoluteUrl(['index/register', 'invitation' => $this->user_info->invitation]);
            $res = \mobile\controllers\PublicController::getShortUrl($url);
            $res && $url = $res;
            $wechat = new WeChat();
            $wechat->createImg1($this->user_info->tel, 2);
            $str = substr(PublicController::getSysInfo(9), 14, 12);
            $pics = '/qrcode_temp/' . $this->user_info->tel . $str . '.jpg';
            $picss = Yii::$app->request->hostInfo . '/' . $pics;
            return $this->render('poster', [
                'user_info' => $this->user_info,
                'str' => $str,
                'url' => $url,
                'pic' => $picss,
            ]);
        }
    }


    /**
     * 生成推广海报
     */
    /**
     * 生成推广海报
     * @param $poster_path          //背景图片
     * @param $url                  //生成二维码的链接
     * @param $pid                  //产品id
     * @param string $filename_code //生成二维码的路径
     * @return string               //返回海报路径
     */
    public function createPosters($poster_path, $url, $pid='', $filename_code='')
    {
        $qrcode_path="./qrcode_temp/promote/";
        $filename_code = $filename_code?:'./uploads/img/qrcode/'.'_promote.jpg';
        PublicController::qrCode($url,$filename_code,10);
        //创建图片的实例
        //var_dump($poster_path);exit;
        $dst = imagecreatefromstring(file_get_contents($poster_path));
        $src = imagecreatefromstring(file_get_contents($filename_code));
        list($src_w, $src_h) = getimagesize($filename_code);
        list($dst_w, $dst_h, $dst_type) = getimagesize($poster_path);
        $src_x = ($dst_w-$src_w)/2;
        $src_y = ($dst_h*5/11);
        imagecopymerge($dst, $src, $src_x, $src_y, 0, 0, $src_w, $src_h, 100);
        header('Content-Type: image/jpeg');
        $str = substr($poster_path,14,13);
        imagejpeg($dst,$qrcode_path.$pid.$str.".jpg",90);
        imagedestroy($dst);
        imagedestroy($src);
        return $qrcode_path.$pid.$str.".jpg";
    }


    /**
     * 代呗
     * $type 1,贷款产品，2，信用卡产品
     */
    public function actionProduct($type=1)
    {
        $type_info = ['1'=>'口子','2'=>'信用卡'];
        $this->getView()->title = $type_info[$type];
        $dai_product=AdminDaiProduct::find()->with('agent')->where(['admin_dai_product.type'=>$type,'is_open'=>1])
            ->Orderby('admin_dai_product.listorder asc')->offset(0)->limit($this->page_size)->all();
        return $this->render('product',[
            'type' =>$type,
            'dai_product' =>$dai_product,
            'user_info' =>$this->user_info,
        ]);
    }

    /**
     * 加入代理
     */
    public function actionJoinAgent($id)
    {
        $this->getView()->title = '立即加入';
        $model = AdminDaiProduct::findOne($id);
        $type = $this->request->get('type');
        return $this->render('join-agent',compact('model','type'));
    }

    /**
     * 立即加入
     */
    public function actionSubJoin()
    {
        $this->getView()->title = '立即加入';
        $userId = Yii::$app->session['user_id'];
        if (!$userId) {
            return $this->redirect(['index/login']);
        }
        //生城二维码，将会员id带上
        $pid = intval($this->request->get('pid'));
        $type = $this->request->get('type');
        $model = AdminDaiProduct::findOne($pid);
        $promUrl = Yii::$app->urlManager->createAbsoluteUrl(['other/sub-product', 'uid' => $this->user_id, 'pid' => $model->id, 'type' => $type]);
        $res = \mobile\controllers\PublicController::getShortUrl($promUrl);
        if($res){
            $promUrl = $res;
        }
        $pic = $this->createPoster('.' . $model->join_pic, $promUrl, $pid);
        $pics = substr($pic, 1);
        $pic = Yii::$app->request->getHostInfo() . $pics;
        return $this->render('sub-join', compact('promUrl', 'pic', 'model'));
    }

    /**
     * 生成推广海报
     */
    /**
     * 生成推广海报
     * @param $poster_path          //背景图片
     * @param $url                  //生成二维码的链接
     * @param $pid                  //产品id
     * @param string $filename_code //生成二维码的路径
     * @return string               //返回海报路径
     */
    public function createPoster($poster_path, $url, $pid='', $filename_code='')
    {
        $qrcode_path="./qrcode_temp/promote/";
        is_dir($qrcode_path) or mkdir($qrcode_path,0777,true);
          if(strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')){
                $code_num = $this->user_info->openid;
         }else{
                $code_num = $this->user_info->tel;
         }   
        $filename_code = $filename_code?:'./uploads/img/qrcode/'.$code_num.'_promote.jpg';
        PublicController::qrCode($url,$filename_code,10);
        //创建图片的实例
        //var_dump($poster_path);exit;
        $dst = imagecreatefromstring(file_get_contents($poster_path));
        $src = imagecreatefromstring(file_get_contents($filename_code));

        list($src_w, $src_h) = getimagesize($filename_code);
        list($dst_w, $dst_h, $dst_type) = getimagesize($poster_path);
        $src_x = ($dst_w-$src_w)/2;
        $src_y = ($dst_h*5/11);
        imagecopymerge($dst, $src, $src_x, $src_y, 0, 0, $src_w, $src_h, 100);
        header('Content-Type: image/jpeg');
        $str = substr($poster_path,14,13);
        imagejpeg($dst,$qrcode_path.$code_num.$pid.$str.".jpg",90);
        imagedestroy($dst);
        imagedestroy($src);
        return $qrcode_path.$code_num.$pid.$str.".jpg";
    }

    /**
     * 加入代理前身份验证和数据生成
     * @param $pid
     * @return int
     */
    public function actionValidateAgent($pid)
    {
        //先判断该产品是否是使用推广码
        $product = AdminDaiProduct::findOne($pid);
        $model = AdminAgentMessage::find()->where(['user_id'=>$this->user_id,'p_id'=>$pid])->one();
        if($product->links) {
            // echo "11";exit;
            //不使用推广码
            if(!$model) {
                $agent = new AdminAgentMessage();
                $agent->user_id = $this->user_id;
                $agent->p_id = $pid;
                $agent->status = 1;
                $agent->type = 1;
                $agent->create_time = time();
                if($agent->save()) {
                    // 取消该条模板消息
                    return 100;     //直接生成普通的二推广维码
                }else {
                    return 200;     //提交失败
                }
            }else if($model->status) {
                return 100;
            }else {
                return 100;     //
            }
        }else {
            //使用推广码
            if(!$model) {
                //如果是第一次申请，生成一条记录
                $agent = new AdminAgentMessage();
                $agent->user_id = $this->user_id;
                $agent->p_id = $pid;
                $agent->status = 0;
                $agent->type = 2;
                $agent->create_time = time();
                if($agent->save()) {
                    //提交成功，等待审核
                    return 300;
                }else {
                    return 200;     //提交失败
                }
            }else if($model->status){
                return 400;     //直接通过
            }else{
                return 300;     //正在审核
            }
        }
    }

    /**
     * 客户申请页面
     */
    public function actionSubProduct()
    {
        $this->getView()->title = '提交信息';
        if($this->request->isPost) {
            $dai_record = new AdminDaiRecord();
            $dai_record->user_id = $this->user_id;
            $dai_record->name = $this->request->post('name');
            $dai_record->tel = $this->request->post('tel');
            $dai_record->ip = CommonFun::getClientIp();
            $dai_record->phone_system = PublicController::getSystem();
            $dai_record->created_time = time();
            $dai_record->tid = $this->request->post('uid');
            $dai_record->pid = $this->request->post('pid');
            if($dai_record->save()) {
                $model = AdminDaiProduct::findOne($this->request->post('pid')); 
                /***************推送模板消息*******************/
                //查找上级
                $pre_member = AdminMember::findOne($dai_record->tid);
                if($pre_member && $pre_member->is_open) {
                    // 这里新增判断 是否限制模版消息推送次数
                    //$pre_member->openid ;
                    $openid = $pre_member->openid ; 
                    $day    = date('Ymd');
                    $pushStat = adminMemberPushstat::find()->where( ['openid'=>$openid,'day'=>$day] )->one() ;
                    if(!$pushStat){
                        $pushmodel = new adminMemberPushstat() ;
                        $pushmodel->openid = $openid ; 
                        $pushmodel->day = $day ; 
                        $pushmodel->save() ; 
                        $todayNum = 0 ; 
                    }else{
                        $todayNum = $pushStat->num ; 
                    } 
                    // 判断是否满足次数限制
                    if( $pre_member->mp_push_daynum > $todayNum ){
                        // 新增今日  +1 
                        $pushStat = adminMemberPushstat::find()->where( ['openid'=>$openid,'day'=>$day] )->one() ; 
                        $pushStat->num ++ ; 
                        $pushStat->save();
                        $title = '您好，您申请加入成功了'.$model->title;
                        $content = "申请人：".'*'.mb_substr($dai_record->name, 1)."；申请时间：".date('Y-m-d H:i',$dai_record->created_time)."；手机号码：".substr_replace($dai_record->tel,'****',3,4);;
                        BaseController::writeNotice($pre_member->id,$title,$content);
                        // 发送模版消息
                        $url = Yii::$app->urlManager->createAbsoluteUrl(['member/apply-rate','type'=>1,'pid'=>$dai_record->pid]);
                        $temp = Yii::$app->params['wx']['sms']['loan_apply'];
                        $data['first'] = ['value'=>'您的好友在您的推荐下申请了'.$model->title,'color'=>'#173177'];
                        $showName = '*'.mb_substr($dai_record->name, 1) ; 
                        $data['keyword1'] = ['value'=>$showName,'color'=>'#173177'];
                        $data['keyword2'] = ['value'=>substr_replace($dai_record->tel,'****',3,4),'color'=>'#173177'];
                        $data['keyword3'] = ['value'=>date('Y-m-d H:i',$dai_record->created_time),'color'=>'#173177'];
                        $data['remark'] = ['value'=>'','color'=>'#173177'];
                        PublicController::sendTempMsg($temp,$pre_member->openid,$data,$url); 
                    }    
                }
                /***************推送模板消息*******************/

                if($model->links) {
                    $this->redirect($model->links);
                    Yii::$app->end();
                }else {
                    $pid = $this->request->post('pid');
                    $tid = $this->request->post('uid');
                    $agent_msg = AdminAgentMessage::find()->where(['p_id'=>$pid,'user_id'=>$tid])->one();
                    if($agent_msg) {
                        if($agent_msg->promo_code) {
                            $this->redirect($agent_msg->promo_url);
                            Yii::$app->end();
                        }else{
                            yii::$app->getSession()->setFlash('error', '系统出错！');
                            echo "<script>window.history.go(-1)</script>";return;
                        }
                    } else{
                        yii::$app->getSession()->setFlash('error', '系统出错！');
                        echo "<script>window.history.go(-1)</script>";return;
                    }
                }
            }else{
                yii::$app->getSession()->setFlash('error', '提交失败，请重新提交！');
                echo "<script>window.history.go(-1)</script>";return;
            }
        }else{
            $uid = $this->request->get('uid');
            $pid = $this->request->get('pid');
            $model = AdminDaiProduct::findOne($pid);
            //申请列表
            //$dai_model = AdminDaiRecord::find()->orderBy('created_time DESC')->offset(0)->limit(10)->all();
        }
        //已申请人数
        $apply_num = AdminDaiRecord::find()->where(['pid'=>$pid])->count();
        $type = $this->request->get('type');
        $view = $type==1?'sub-product':'sub-product_bank';
        return $this->render($view,[
            'uid'=>$uid,
            'model'=>$model,
            'apply_num'=>$apply_num,
            //'dai_model'=>$dai_model,
        ]);
    }

    /**
     * 客户信息
     */
    public function actionCustomerList()
    {
        $this->getView()->title = '客户信息';
        $user_info = $this->user_info;
        $this->user_info->tel = substr_replace($this->user_info->tel,'****',3,4);
        $loans = AdminDaiRecord::find()->joinWith('product')
            ->where(['tid' => $this->user_id])
            ->orderBy(['admin_dai_product.create_time'=>SORT_DESC])
            ->groupBy('admin_dai_record.pid')
            ->asArray()->all();
        //本月累计人数
//        $begin_month=mktime(0,0,0,date('m'),1,date('Y'));
//        $end_month=mktime(23,59,59,date('m'),date('t'),date('Y'));
        $num = AdminDaiRecord::find()->andWhere(['tid'=>$this->user_id, 'match_num' => 0])
            ->count();
        return $this->render('customer-list',compact('user_info','loans','num'));
    }

    /**
     * 申请进度
     */
    public function actionApplyRate()
    {
        $this->getView()->title = '申请进度';
        $pid = $this->request->get('pid');
        //通过申请记录表链接统计表
        $model = AdminDaiRecord::find()->where(['pid'=>$pid,'tid'=>$this->user_id]);
        $model = $model->orderBy('created_time DESC')
//            ->limit($this->page_size)  // 单人单个产品记录不会太多，就不做分页了
            ->asArray()->all();
        $data = $model;
        $real_name = AdminMember::findOne($this->user_id)->real_name;
        return $this->render('apply-rate',compact('type','pid','data','real_name'));
    }

    /**
     * 返回本月推广产品申请人数量
     */
    public static function getNum($pid,$user_id)
    {
//        $begin_month=mktime(0,0,0,date('m'),1,date('Y'));
//        $end_month=mktime(23,59,59,date('m'),date('t'),date('Y'));
        return AdminDaiRecord::find()->andWhere(['pid'=>$pid,'tid'=>$user_id])
            ->count();
    }

    /**
     * 返回本月推广产品申请成功人数量
     */
    public static function getApplySuccessNum($user_id)
    {
//        $begin_month=mktime(0,0,0,date('m'),1,date('Y'));
//        $end_month=mktime(23,59,59,date('m'),date('t'),date('Y'));
        return AdminDaiRecord::find()->andWhere(['tid'=>$user_id,'match_num'=>1])->andWhere('sign is null')
//            ->andWhere(['between','created_time',$begin_month,$end_month])->count();
            ->count();
    }

    /**
     * 返回本月下级申请成功数量
     * @param $user_id
     * @return int|string
     */
    public static function getNextApplySuccessNum($user_id)
    {
//        $begin_month=mktime(0,0,0,date('m'),1,date('Y'));
//        $end_month=mktime(23,59,59,date('m'),date('t'),date('Y'));
        $member = AdminMember::findOne($user_id);
        $ids = [];
        $members = AdminMember::find()->where(['bei_invitation'=>$member->invitation])->asArray()->all();
        foreach ($members as $list){
            array_push($ids,$list['id']);
        }
        $count = AdminDaiRecord::find()->andWhere(['match_num'=>1])->andWhere(['in','tid',$ids])->andWhere('sign is null')
            ->count();
        return $count;
    }

    /**
     * 排行榜
     */
    public function actionRankList()
    {
        $this->getView()->title = '排行榜';
        $period = (int)Yii::$app->request->get('period');
        $type = (int)Yii::$app->request->get('type');
        !$period && $period = 1;
        !$type && $type = 1;
        $member = [];
        // 本周一与周日
        $per_monday = date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
        $per_monday = strtotime($per_monday.' 00:00:00');
        $per_sunday = date('Y-m-d', (time() + (7 - (date('w') == 0 ? 7 : date('w'))) * 24 * 3600));
        $per_sunday = strtotime($per_sunday.' 23:59:59');
        $per_month = strtotime(date('Y-m-01'));
        $now = time();
        if($period == 1 && $type == 1){  // 总排行 佣金
            $member = AdminMember::find()->where(['grade' => 3])->asArray()->all();
            foreach ($member as &$list){
                $list['money'] = $list['promotion_commission'] + $list['dai_commission'];
                $list['tip'] = '累计奖金总额<span class="text-main"> '.$list['money'].' </span>元';
            }
            array_multisort(array_column($member,'money'),SORT_DESC,$member);
            $member = array_slice($member,0,10);
        }else if($period == 1 && $type == 2){ // 总排行 代理
            $member = self::getAgentRank();
        }else if($period == 2 && $type == 1){ // 月排行 佣金
            $member = self::getCommissionRank($per_month,$now);
        }else if($period == 2 && $type == 2){ // 月排行 代理
            $member = self::getAgentRank($per_month,$now);
        } else if($period == 3 && $type == 1){ // 周排行 佣金
            $member = self::getCommissionRank($per_monday,$per_sunday);
        }else if($period == 3 && $type == 2) { // 周排行 代理
            $member = self::getAgentRank($per_monday,$per_sunday);
        }
        return $this->render('rank-list', [
            'info' => $member,
            'period' => $period,
            'type' => $type,
        ]);
    }

    /**
     * 获取代理排行数据
     * @param $sql
     * @return array|ActiveRecord[]
     */
    public static function getAgentData($sql)
    {
        $query = ActiveRecord::findBySql($sql)->asArray()->all();
        $bei_invitations = array_column($query,'bei_invitation');
        $member = AdminMember::find()->where(['in','invitation',$bei_invitations])->asArray()->all();
        foreach ($member as $key => &$val){
            foreach ($query as $v){
                if($val['invitation'] == $v['bei_invitation']){
                    $val['c'] = $v['c'];
                }
            }
        }
        array_multisort(array_column($member,'c'),SORT_DESC,$member);
        foreach ($member as $key => &$val){
            $val['tip'] = '累计推广<span class="text-main"> '.$val['c'].' </span>人';
        }
        return $member;
    }

    /**
     * 获取代理排行
     * @param string $b_time
     * @param string $e_time
     * @return array|ActiveRecord[]
     */
    public static function getAgentRank($b_time='',$e_time='')
    {
        if($b_time && $e_time){
            // MySQL rand()随机排序
            $sql = "SELECT m.bei_invitation,COUNT(1) AS c FROM admin_member AS m WHERE (m.`created_time` BETWEEN {$b_time} AND {$e_time}) AND m.`grade` = 3 GROUP BY m.`bei_invitation` ORDER BY c DESC,RAND() LIMIT 10";
            $member = self::getAgentData($sql);
        }else{
            $sql = "SELECT m.`bei_invitation`,COUNT(1) AS c FROM admin_member AS m WHERE m.`grade`=3 GROUP BY m.`bei_invitation` ORDER BY c DESC LIMIT 11";  // grade = 3 购买了金牌会员
            $member = self::getAgentData($sql);
        }
        return $member;
    }

    /**
     * 获取佣金排行
     * @param $b_time
     * @param $e_time
     * @return array|ActiveRecord[]
     */
    public static function getCommissionRank($b_time,$e_time)
    {
        $sql = "SELECT c.user_id,SUM(c.`commission_money`) AS s FROM admin_commission AS c WHERE c.`created_time` BETWEEN {$b_time} AND {$e_time} GROUP BY user_id ORDER BY s DESC LIMIT 10";
        $query = ActiveRecord::findBySql($sql)->asArray()->all();
        $ids = array_column($query,'user_id');
        $ids_str = implode(',',$ids);
        $ids_str_quote = "'".$ids_str."'";
        $sql = "SELECT * FROM admin_member WHERE id IN ({$ids_str}) ORDER BY FIND_IN_SET(id,{$ids_str_quote})";
        $member = ActiveRecord::findBySql($sql)->asArray()->all();
        foreach ($member as $key => &$val){
            $val['tip'] = '累计奖金总额<span class="text-main"> '.$query[$key]['s'].' </span>元';
        }
        return $member;
    }

    /**
     * 我要提现
     */
    public function actionWithdraw()
    {
        $this->getView()->title = '我要提现';
        $minmoney = PublicController::getSysInfo(29);
        if($this->request->isPost) {
            $money = abs(floatval($this->request->post('money')));
            if($money < $minmoney) {
                return 200;
            }else if(!$this->user_info->account_number || !$this->user_info->account_name || !$this->user_info->pay_code) {
                return 304;     //收款账户信息未绑定
            }else {
                $state = $this->actionDellWithdraw($money);
                if($state != 100) {
                    return $state;
                }else{
                    $member = AdminMember::findOne($this->user_id);
                    $model = new AdminWithdraw();
                    $member->available_money -= $money;
                    $model->user_id = $this->user_id;
                    $model->money = $money;
                    $model->created_time = time();
                    $transaction = Yii::$app->db->beginTransaction();
                    if ($member->validate() && $model->validate()) {
                        try {
                            $member->save();
                            $model->save();
                            $transaction->commit();
                            //提现成功后推送一条消息给用户
                            /************提现成功后推送***********/
                            //判断是否开启
                            if($this->user_info->is_open){
                                // 提现申请私信暂不记录

                                $url = Yii::$app->urlManager->createAbsoluteUrl(['member/withdraw-record']);
                                $temp = Yii::$app->params['wx']['sms']['withdraw_accept'];
                                $data['first'] = ['value'=>'提现申请已经提交成功','color'=>'#173177'];
                                $data['keyword1'] = ['value'=>$this->user_info->nickname,'color'=>'#173177'];
                                $data['keyword2'] = ['value'=>date('Y-m-d H:i:s',$model->created_time),'color'=>'#173177'];
                                $data['keyword3'] = ['value'=>$model->money.'/元','color'=>'#173177'];
                                $data['keyword4'] = ['value'=>'转到填写的账户信息里面','color'=>'#173177'];
                                PublicController::sendTempMsg($temp,$this->user_info->openid,$data,$url);
                            }
                            /************提现成功后推送end***********/
                            return 100;
                        } catch (\Exception $e) {
                            //捕获错误
                            $transaction->rollback();
                            return 400;
                        }
                    }else{
                        return 400;
                    }
                }
            }
        }
        return $this->render('withdraw',[
            'user_info'=>$this->user_info,
            'minmoney' =>$minmoney,
        ]);
    }

    /**
     * 处理我要提现
     * @return integer
     * 200 金额不正确
     * 300 提现的金额大于可提现金额
     */
    public function actionDellWithdraw($money)
    {
        //判断金额
        if(!$money) {
            return 200;
        }else if(!is_numeric($money)) {
            return 200;
        }else if($money > $this->user_info->available_money){
            return 300;
        }else{
            return 100;
        }
    }

    /**
     * 新手指南
     */
    public function actionTutorial()
    {
        $this->view->title = '新手指南';
        return $this->render('tutorial',[]);
    }

    /**
     * 我要提额
     */
    public function actionWithdrawMoney($cat_ids=4)
    {
        $user_id = $this->session->get('user_id');
        $user_info = AdminMember::findOne($user_id);
        $this->getView()->title = '我要提额';
        $model = AdminDrawMoney::find()->all();
        $keyword= $this->request->get('keywords');
        if($keyword){
            $keywords =$keyword;
        }else{
            $keywords ='';
        }
        if($this->user_id){
         $info = AdminArticle::find()->andwhere(['cat_id'=>$cat_ids])
            ->andwhere(['like','title',$keywords])
            ->orderBy('create_time DESC')
            ->offset(0)->limit(5)
            ->all();
         $cat_id =['4','5','6','7'];
         $category =AdminCategory::find()->where(['in','id',$cat_id])->all();
         return $this->render('withdraw-money',[
            'model'=>$model,
            'info'=>$info,
            'category'=>$category,
            'cat_id' =>$cat_ids,
            'keywords' =>$keywords,
             'user_info' =>$user_info,
        ]);
        }else{
            $url = Yii::$app->urlManager->createUrl('/index/index');
            Yii::$app->getSession()->setFlash('jump', '您还未登录,点击确定前往登录');
            Yii::$app->getSession()->setFlash('url', Url::toRoute(['index/login']));
            $this->redirect($url);Yii::$app->end();
        }
    }
    public function actionLoadMores(){
        $page = Yii::$app->request->get('page');
        $cat_ids = Yii::$app->request->get('cat_ids');
        $user_id = $this->session->get('user_id');
        $user_info = AdminMember::findOne($user_id);
        // return $page;
        $page_size =5;
        $keyword= $this->request->get('keywords');
        if($keyword){
            $keywords =$keyword;
        }else{
            $keywords ='';
        }
        $info = AdminArticle::find()->andwhere(['cat_id'=>$cat_ids])
            ->andwhere(['like','title',$keywords])
            ->orderBy('create_time DESC')
            ->offset(($page-1)*$page_size)->limit($page_size)
            ->all();
        $li = '';
        foreach ($info as $key => $value) {
            $url = Yii::$app->urlManager->createAbsoluteUrl(['list/detail','id'=>$value->art_id]);
                 $li .='<li>
                    <a onclick="see(\''.$value['permission'].'\',\''.$url.'\',\''.$value['grade'].'\',\''.$user_info['grade'].'\')">';
                 $li .='<img src="'.$value->img.'"  class="fl"/>';
                 $li .='<p>'.PublicController::substr($value->title, 40).'</p>';
                 $li .='<span>'.date('Y-m-d',$value->create_time).'  </span>';      
                 $li .='
                        <div class="clear"></div>
                    </a>
                </li>';
        }
        return $li;
    }

    /**
     * 公告私信
     */
    public function actionNotice()
    {
        if(!Yii::$app->session['user_id']){
            return $this->redirect(['index/login']);
        }
        $this->getView()->title = '私信列表';
        $notices = AdminNotice::find()->where(['user_id'=>Yii::$app->session['user_id']])
            ->orderBy(['create_time'=>SORT_DESC])->limit(20)->all();
        return $this->render('notice',[
            'notices'=>$notices,
        ]);
    }

    // 加载私信
    public function actionLoadNotice()
    {
        if(Yii::$app->request->isAjax){
            $user_id = Yii::$app->session['user_id'];
            if(!$user_id){
                return $this->redirect(['index/login']);
            }
            $page = Yii::$app->request->post('page');
            $pageSize = 20;
            $notices = AdminNotice::find()->where(['user_id'=>$user_id])
                ->orderBy(['create_time'=>SORT_DESC])
                ->offset(($page - 1)*$pageSize)
                ->limit($pageSize)
                ->asArray()->all();
            if(!$notices){
                return $this->json(100,'没有更多数据');
            }
            foreach ($notices as &$val){
                $val['url'] = Url::to(['member/notice-detail','id'=>$val['id']]);
                $val['create_time'] = date('Y-m-d H:i',$val['create_time']);
            }
            return $this->json(200,$notices);
        }
    }

    // 私信详情
    public function actionNoticeDetail()
    {
        if(!Yii::$app->session['user_id']){
            return $this->redirect(['index/login']);
        }
        $this->getView()->title = '私信详情';
        $id = (int)Yii::$app->request->get('id');
        $notice = AdminNotice::findOne($id);
        if($notice->is_read == 1){
            AdminNotice::updateAll(['is_read'=>2],['id'=>$id]);
        }
        return $this->render('notice-detail',[
            'model'=>$notice,
        ]);
    }

    /**
     * 推广奖励
     * 和我的徒弟一样
     */
    public function actionPromoteAward()
    {
        $this->actionCustomer();
    }
    /**
     * 代呗奖励
     */
    public function actionProductAward()
    {
        $this->getView()->title = '产品奖励';
        //累计提现金额
        $tx_money = AdminWithdraw::find()->andWhere(['user_id'=>$this->user_id])->andWhere(['!=','status',3])->sum('money');
        $query = AdminBankCard::find()->orderBy('create_time DESC');
        //取信用卡数据
        $data['card'] = $query->where(['type'=>1])->all();
        //贷款数据
        $data['loan'] = $query->where(['type'=>2])->all();
        $this->user_info->tel = substr_replace($this->user_info->tel,'****',3,4);

        $data1 = [];
        $this->user_info->tel = substr_replace($this->user_info->tel,'****',3,4);
        $model = AdminAgentMessage::find()->joinWith('product')
            ->where(['user_id'=>$this->user_id])->all();
        if($model) {
            foreach ($model as $list) {
                $data1[$list->product['type']][] = $list;
            }
        }
        //本月累计人数
        $begin_month=mktime(0,0,0,date('m'),1,date('Y'));
        $end_month=mktime(23,59,59,date('m'),date('t'),date('Y'));
        $num = AdminDaiRecord::find()->andWhere(['tid'=>$this->user_id])
            ->andWhere(['between','created_time',$begin_month,$end_month])->count();

        //获取代呗本月收入
        $begin_month=mktime(0,0,0,date('m'),1,date('Y'));
        $end_month=mktime(23,59,59,date('m'),date('t'),date('Y'));
        $commission = AdminCommission::find()
            ->andWhere(['user_id'=>$this->user_id,'type'=>2])
            ->andWhere(['between','created_time',$begin_month,$end_month])
            ->sum('commission_money');
        return $this->render('product-award',[
            'user_info'=>$this->user_info,
            'tx_money'=>$tx_money,
            'data'=>$data,
            'data1'=>$data1,
            'commission'=>$commission,
            'num'=>$num,
        ]);
    }
    /**
     * 提现记录
     */
    public function actionWithdrawRecord()
    {
        $this->getView()->title = '领取记录';
        //1，申请中，2，成功，3，失败
        $state = [1=>'申请中',2=>'成功',3=>'失败'];
        $page_size = 11;
        $model = AdminWithdraw::find()
            ->where(['user_id'=>$this->user_id])
            ->offset(0)
            ->limit($page_size)
            ->orderBy('created_time DESC')->all();
        return $this->render('withdraw-record',[
            'model'=>$model,
            'state'=>$state,
        ]);
    }
    /**
     * 加载更多记录
     * @return string
     * $type  4 关注成功和购买成功
     * $type  3 提现记录
     */
    public function actionLoadMore($type=3)
    {
        $state = [1=>'申请中',2=>'成功',3=>'失败'];
        $page_size = $this->page_size;
        $page = $this->request->get('page');
        $li = '';
        $query = AdminWithdraw::find()->where(['user_id'=>$this->user_id])->orderBy('created_time DESC');
        $model = $query->offset(($page-1)*$page_size)->limit($page_size)->all();
        if ($model) {
            if ($type == 3) {
                foreach ($model as $list) {
                    $li .= '<li><h1>提现:￥' . $list->money . '</h1><h2>' . date('Y-m-d H:i:s', $list->created_time) . '</h2>';
                    $li .= '<span class="fr">' . $state[$list->status] . '</span><div class="clear"></div></li>';
                }
            }
        }
        return $li;
    }

    // 加载更多（我的粉丝）
    public function actionLoadCustomer()
    {
        $page = Yii::$app->request->get('page');
        $state = Yii::$app->request->get('state');
        $pageSize = $this->page_size;
        $query = $query = AdminMember::find()->andWhere(['bei_invitation'=>$this->user_info->invitation]);
        if($state == 1){
            $query = $query->andWhere(['>','grade',0]);
        }
        $model = $query ->orderBy('created_time DESC')
            ->offset(($page-1)*$pageSize)
            ->limit($pageSize)->all();
        if($model){
            $li = '';
            foreach ($model as $list) {
                $li .= '<li>';
                $li .= '<span>'.date('Y-m-d',$list->created_time).'</span>';
                $li .= '<span>'.$list->tel.'</span>';
                $li .= '<span>'.$list->nickname.'</span>';
                if($list->grade > 0){
                    $li .= '<span>已购买</span>';
                }else{
                    $li .= '<span>未购买</span>';
                }
                $li .= '</li>';
            }
            return $li;
        }
    }

    /**
     * 生成二维码
     * $url 链接地址
     * $size 二维码大小
     * $margin 外边距
     */
    public function actionQrcode($url='',$size=4, $margin=1,$file_name=false)
    {
        require ROOT.'/phpqrcode/qrlib.php';
        //设置 header 头,直接输出图片
        Yii::$app->response->headers->set('Content-Type', 'image/png');
        //根据参数生成二维码 , 将其第二个参数值设为 false ,也就是不输出图片文件
        \QRcode::png($url, $file_name, "L", $size, $margin);
        //die();
    }
    /**
     * 关闭和开启推送
     */
    public function actionIsOpen()
    {
        $is_open = $this->user_info->is_open;
        $this->user_info->is_open = abs($is_open-1);
        if($this->user_info->save()) {
            return 100;
        }else{
            return 200;
        }
    }

    /**
     * 推送次数限制
     */
    public function actionPushnumSet($num)
    {
        $mp_push_daynum = $num;
        $this->user_info->mp_push_daynum = abs(intval($mp_push_daynum));
        if($this->user_info->save()) {
            return 100;
        }else{
            return 200;
        }
    } 

    /**
     * 发送短信配置信息
     */
    public function actionSms($tel)
    {
        $code = rand(100000, 999999);
      
        //$param['SMS_7785270'] = json_encode(['code'=>$code,'product'=>'卡农社区']);
       // $param = json_encode(['code'=>"$code"]);
        $this->session->set('send_code',$code);
        $this->session->set('send_tel',$tel);
        $res = PublicController::setCodes2($tel,$code);
        if($res){
            return json_encode(['status'=>200,'msg'=>'发送成功']);
        }else{
            return json_encode(['status'=>100,'msg'=>'发送失败']);
        }
    }

    /**
     * 加载更多申请进度
     */
    public function actionMoreApply()
    {
        $page = $this->request->get('page');
        $type = $this->request->get('type');
        $pid = $this->request->get('pid');
        $li = '';
        //通过申请记录表链接统计表
        $model = AdminDaiRecord::find()->joinWith('count',true,'INNER JOIN')
            ->orderBy('admin_count.apply_time DESC')
            ->andWhere(['pid'=>$pid,'tid'=>$this->user_id,'admin_count.apply_rate'=>$type])
            ->offset(($page-1)*$this->page_size)->limit($this->page_size)->all();
        if($model) {
            foreach ($model as $list) {
                $li .= "<tr><td>".date('Y-m-d',$list->count['apply_time'])."</td><td>".substr_replace($list->tel,'****',3,4)."</td></tr>";
            }
        }
        return $li;
    }
    public function actionAjaxUpdate() {
        $id = $this->session->get('user_id');
        $val = yii::$app->request->post('pic');
        $key = yii::$app->request->post('field');
        $model = AdminMember::findOne($id);
        $model->$key=$val;
        if($model->save()) {
            echo 1;
        }
    }

    // 下款报备
    public function actionLoanReport()
    {
        $this->getView()->title = '下款报备';
        $userId = Yii::$app->session->get('user_id');
//        if ($userId != 9290) {
//            return $this->render('test');
//        }
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $loanReport = new AdminLoanReport();
            $res = $loanReport->create($post, $userId);
            if ($res['status'] != 200) {
                return $this->json(100, $res['msg']);
            }
            return $this->json(200, '提交成功');
        }
        return $this->render('loan-report');
    }

    // 下款报备图片上传
    public function actionUploadLoanImg()
    {
        if (Yii::$app->request->isAjax) {
            $model = new UploadForm();
            $model->imageFile = UploadedFile::getInstanceByName('file');
            return $model->upload();
        }
    }

}
