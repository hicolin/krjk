<?php
namespace frontend\controllers;
use backend\models\AdminOrders;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * Site controller
 */

class PayController extends Controller
{
    /**
     * @inheritdoc
     */
    public $enableCsrfValidation = false ;
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
    public function actionWxpay()
    {
       $order_id=Yii::$app->request->get('order_id');
        $data=AdminOrders::findOne($order_id);
        if($data){
            return $this->render('wxpay',['order_msg'=>$data]);
        }
    }

    public function actionOrder()
    {
        $order_id=Yii::$app->request->get('order_id');
        $data=AdminOrders::findOne($order_id);
        if($data){
            return $this->render('order',['order'=>$data]);
        }
    }
    public function actionAlipay()
    {
        $order_id=Yii::$app->request->post('order_id');
        $data=AdminOrders::findOne($order_id);
        if($data){
            return $this->render('alipay',['order'=>$data]);
        }
    }


    public function actionPay()
    {

        file_put_contents('haha.txt',11);
        $order_id=Yii::$app->request->post('order_id');
        $data=AdminOrders::findOne($order_id);
        if($data){
            return $this->render('alipay',['order'=>$data]);
        }
    }


    public function actionNotify()
    {

        echo  22;exit;

        $order_id=Yii::$app->request->post('order_id');
        $data=AdminOrders::findOne($order_id);
        if($data){
            return $this->render('alipay',['order'=>$data]);
        }
    }

}
