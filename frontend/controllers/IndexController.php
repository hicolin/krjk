<?php
namespace frontend\controllers;

use backend\models\AdminCase;
use backend\models\AdminComment;
use backend\models\AdminOrder2;
use backend\models\AdminOrders;
use backend\models\AdminQuestion;
use backend\models\AdminSites;
use common\controllers\PublicController;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\AdminRegions;
use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use yii\helpers\Url;
/**
 * Site controller
 */
class IndexController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'index';
    public $enableCsrfValidation = false;

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
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $filename = 'uploads/img/qrcode/obNwdwbAV5mZDqGkNAdquoBdzun0.jpg';
        $img = $this->GrabImage("http://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83eqsFicCU21tKom8S32qfkZKLZ3qZ2ejKDAG1Ny3Y1mh6zR19iaVAYS8XFIwnrOuHIicRTI2ibyPuciavNw/0", $filename);
        if ($img):echo '<pre><img src="' . $img . '"></pre>';
        //如果返回值为真，这显示已经采集到服务器上的图片
        else:echo "false";
        endif;
        exit;
        //否则，输出采集失败



        require ROOT . '/phpqrcode/qrlib.php';
        $url = 'http://www.baidu.com';
        //设置 header 头,直接输出图片
        Yii::$app->response->headers->set('Content-Type', 'image/png');
        //根据参数生成二维码 , 将其第二个参数值设为 false ,也就是不输出图片文件
        \QRcode::png($url, 'qrcode1.png', "L", 4, 1);
        echo 11;
        exit;
        die();
    }

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


}
