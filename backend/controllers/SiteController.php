<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\AdminUser;
use backend\models\BackendUser;
use backend\models\AdminUserRole;
use common\utils\CommonFun;
use common\controllers\PublicController1;
use backend\models\AdminMember;
use backend\models\AdminGoods;
use backend\models\AdminDaiRecord;
use backend\models\AdminBuyAgent;
use backend\models\AdminWithdraw;
use backend\models\AdminGrade;
/**
 * Site controller
 */
class SiteController extends BaseController
{
    public $layout = "lte_main";
    public $enableCsrfValidation=false;
    /**
     * @inheritdoc
     */
//     public function actions()
//     {
//         return [
//             'error' => [
//                 'class' => 'yii\web\ErrorAction',
//             ],
//         ];
//     }

    public function actionIndex()
    {
//        $user = AdminUser::findOne(156);
//        Yii::$app->user->login($user);
        if(Yii::$app->user->isGuest){
            $this->layout = "lte_main_login";
            return $this->render('login');
        }
        else{
//             $this->layout = "lte_main";
            $menus = Yii::$app->user->identity->getSystemMenus();
            $sysInfo = [
                ['name'=> '操作系统', 'value'=>php_uname('s')],  //'value'=>php_uname('s').' '.php_uname('r').' '.php_uname('v')],
                ['name'=>'PHP版本', 'value'=>phpversion()],
                ['name'=>'Yii版本', 'value'=>Yii::getVersion()],
                ['name'=>'数据库', 'value'=>$this->getDbVersion()],
                ['name'=>'AdminLTE', 'value'=>'V2.3.6'],
            ];
           
            /*
             * 开始修改后台内容开始
             */
            //今日0点时间戳
            $b_time=mktime(0,0,0,date('m'),date('d'),date('Y'));
            //几日24点时间戳
            $e_time=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
            //所有会员总数
            $queryall = AdminMember::find()->count();
            //所有铜牌会员总数
            $queryt = AdminMember::find() -> where(['grade'=> 1])->count();
            //所有银牌会员总数
            $queryy = AdminMember::find() -> where(['grade'=> 2])->count();
            //所有金牌会员总数
            $queryj = AdminMember::find() -> where(['grade'=> 2])->count();

            //今日会员总数
            $todayqueryall = AdminMember::find() -> Where(['>=','created_time',$b_time])->andWhere(['<=','created_time',$e_time])->count();
            //今日铜牌会员总数
            $todayqueryt = AdminMember::find() -> where(['grade'=> 1]) -> andWhere(['>=','created_time',$b_time])->andWhere(['<=','created_time',$e_time])->count();
            //今日银牌会员总数
            $todayqueryy = AdminMember::find() -> where(['grade'=> 2]) -> andWhere(['>=','created_time',$b_time])->andWhere(['<=','created_time',$e_time])->count();
            //今日金牌会员总数
            $todayqueryj = AdminMember::find() -> where(['grade'=> 3]) -> andWhere(['>=','created_time',$b_time])->andWhere(['<=','created_time',$e_time])->count();
            //php获取本月起始时间戳和结束时间戳
            $beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
            $endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));
            //本月会员总数
            $monthqueryall = AdminMember::find() -> Where(['>=','created_time',$beginThismonth])->andWhere(['<=','created_time',$endThismonth])->count();
            //本月铜牌会员总数
            $monthqueryt = AdminMember::find() -> where(['grade'=> 1]) -> andWhere(['>=','created_time',$beginThismonth])->andWhere(['<=','created_time',$endThismonth])->count();
            //本月银牌会员总数
            $monthqueryy = AdminMember::find() -> where(['grade'=> 2]) -> andWhere(['>=','created_time',$beginThismonth])->andWhere(['<=','created_time',$endThismonth])->count();
            //本月金牌会员总数
            $monthqueryj = AdminMember::find() -> where(['grade'=> 3]) -> andWhere(['>=','created_time',$beginThismonth])->andWhere(['<=','created_time',$endThismonth])->count();
            /*购买金银铜会员的价格*/
            // $modelt = AdminGrade::find() -> where(['id'=>1])->limit(1)->one();
            $modelt = AdminBuyAgent::find()->where(['grade_id'=>1,'status'=>1])->sum('money');
            $modely = AdminBuyAgent::find()->where(['grade_id'=>2,'status'=>1])->sum('money');
            $modelj = AdminBuyAgent::find()->where(['grade_id'=>3,'status'=>1])->sum('money');
            $totalmoney = AdminBuyAgent::find()->where(['status'=>1])->sum('money');
            // echo $modelt;exit;
            // echo $modelt;exit;
            //获取今日产品申请数
            $dairecord = AdminDaiRecord::find() -> Where(['>=','created_time',$b_time])->andWhere(['<=','created_time',$e_time])->count();
            //总产品申请数
            $dairecordall = AdminDaiRecord::find()->count();
            //获取线下购买喂审核人数
            $buyagent = AdminBuyAgent::find() -> where(['status'=> -2])->count();
            //获取提现金未审核人数
            $withdraw = AdminWithdraw::find() -> where(['status'=> 1])->count();
            //获取昨日会员购买的个数
            $beginYesterday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
            $endYesterday=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
            $yesterdayqueryagent = AdminMember::find() -> Where(['>=','created_time',$beginYesterday])->andWhere(['<=','created_time',$endYesterday])->asArray()->count();

            //获取昨日产品购买的个数
            $yesterdayquerydairecord = AdminDaiRecord::find() -> Where(['>=','created_time',$beginYesterday])->andWhere(['<=','created_time',$endYesterday])->count();
            //获取本月产品购买的订单量
            $thismonthquerydairecord = AdminDaiRecord::find() -> Where(['>=','created_time',$beginThismonth])->andWhere(['<=','created_time',$endThismonth])->count();
            /*
            单品销售排名
            */
            $dairecordquery = AdminDaiRecord::findBySql('select a.tid,count(*) as count,b.title from admin_dai_record as a ,admin_dai_product as b where  a.pid = b.id group by a.pid order by count desc limit 5 ')->asArray()->all();

            /*
                推广
            */
            $tgquery = AdminMember::find()
                ->select(['bei_invitation','count(1) as count'])
                ->groupBy('bei_invitation')
                ->andWhere(['is not','bei_invitation',null])
                ->orderBy(['count'=>SORT_DESC])
                ->limit(10)
                ->asArray()
                ->all();
            foreach ($tgquery as &$value){
                $value['nickname'] = AdminMember::findOne(['invitation'=>$value['bei_invitation']])->nickname;
                unset($value['bei_invitation']);
            }

            /*
             * 开始修改后台内容结束
             */
            return $this->render('index', [
                'queryall' => $queryall,
                'queryt' => $queryt,
                'queryy' => $queryy,
                'queryj' => $queryj,
                'todayqueryall' => $todayqueryall,
                'todayqueryt' => $todayqueryt,
                'todayqueryy' => $todayqueryy,
                'todayqueryj' => $todayqueryj,
                'monthqueryall' => $monthqueryall,
                'monthqueryt' => $monthqueryt,
                'monthqueryy' => $monthqueryy,
                'monthqueryj' => $monthqueryj,
                'modelt'=>$modelt,
                'modely'=>$modely,
                'modelj'=>$modelj,
                'totalmoney'=>$totalmoney,
                'dairecord'=>$dairecord,
                'dairecordall'=>$dairecordall,
                'buyagent'=>$buyagent,
                'yesterdayqueryagent'=>$yesterdayqueryagent,
                'yesterdayquerydairecord'=>$yesterdayquerydairecord,
                'thismonthquerydairecord'=>$thismonthquerydairecord,
                'dairecordquery'=>$dairecordquery,
                'tgquery'=> $tgquery,
                'withdraw'=>$withdraw,
                'system_menus' => $menus,
                'sysInfo'=>$sysInfo
            ]);
        }
    }

    public function actionLogin()
    {
        
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');
        $rememberMe = Yii::$app->request->post('remember');



        if(AdminUser::login($username, $password, $rememberMe) == true){
            AdminUser::updateAll(
                ['last_ip' => CommonFun::getClientIp()],
                ['uname' => $username]

                );
            //return $this->goBack();
            echo json_encode(['errno'=>0]);
        }
        else{
            echo json_encode(['errno'=>2]);
        }
    }

    
    public function actionLogout()
    {
        Yii::$app->user->identity->clearUserSession();
        Yii::$app->user->logout();
        return $this->goHome();
    }
    public function actionPsw()
    {
       $userRole = AdminUserRole::find()->with('role')->andWhere(['user_id'=>Yii::$app->user->identity->id])->one();
        return $this->render('psw',[
            'user_role' => $userRole->role->name
        ]);
    }
    public function actionPswSave()
    {
        $old_password = Yii::$app->request->post('old_password', '');
        $new_password = Yii::$app->request->post('new_password', '');
        $confirm_password = Yii::$app->request->post('confirm_password', '');
        if(empty($old_password) == true){
            $msg = array('error'=>2, 'data'=>array('old_password'=>'旧密码不正确'));
            echo json_encode($msg);
            exit();
        }
        if(empty($new_password) == true){
            $msg = array('error'=>2, 'data'=>array('new_password'=>'新密码不能为空'));
            echo json_encode($msg);
            exit();
        }
        if(empty($confirm_password) == true){
            $msg = array('error'=>2, 'data'=>array('confirm_password'=>'确认密码不能为空'));
            echo json_encode($msg);
            exit();
        }
        if($new_password != $confirm_password){
            $msg = array('error'=>2, 'data'=>array('confirm_password'=>'两次新密码不相同'));
            echo json_encode($msg);
            exit();
        }
        if(Yii::$app->user->isGuest == false){
            $user = AdminUser::findByUsername(Yii::$app->user->identity->uname);
            if(BackendUser::validatePassword($user, $old_password) == true){
                $user->password = Yii::$app->security->generatePasswordHash($new_password);
                $user->save();
                $msg = array('errno'=>0, 'msg'=>'保存成功');
                echo json_encode($msg);
            }
            else{
                $msg = array('errno'=>2, 'data'=>array('old_password'=>'旧密码不正确'));
                echo json_encode($msg);
            }
        }
        else{
            $msg = array('errno'=>2, 'msg'=>'请先登录');
            echo json_encode($msg);
        }
    }
    private function getDbVersion(){
        $driverName = Yii::$app->db->driverName;
        if(strpos($driverName, 'mysql') !== false){
            $v = Yii::$app->db->createCommand('SELECT VERSION() AS v')->queryOne();
            $driverName = $driverName .'_' . $v['v'];
        }
        return $driverName;
    }
    
    /**
     * 全局错误处理
     */
    public function actionError()
    {
        $exception = Yii::$app->getErrorHandler()->exception;
        $statusCode = $exception->statusCode;
//         return $this->render('error', ['name' => $statusCode, 'message'=>$exception->__toString()]);
        return $this->render('error', ['name' => $statusCode, 'message'=>"系统出错，具体错误信息请查看runtime\logs\app.log"]);
         
    }
    /**
     * 添加的内容 王兴东
     */
    public function actionGetorderchartcount()
    {
        $type = isset($_POST['date']) ? $_POST['date'] : 4;
        $data = array();
        if ($type == 1)
        {
            //今日
            $start = mktime(0,0,0,date('m'),date('d'),date('Y'));
            $end =   mktime(23, 59, 59, date('m'), date('d'), date('Y'));
            for ($i = 0; $i < 24; $i ++)
            {
                $date_start = strtotime(date("Y-m-d H:i:s", $start + 3600 * $i));
                $date_end = strtotime(date("Y-m-d H:i:s", $start + 3600 * ($i + 1)));
                $count = AdminDaiRecord::find() -> Where(['>=','created_time',$date_start])->andWhere(['<=','created_time',$date_end])->count();
                $wxd = (string)$i;
                $data[$i] = array(
                    $wxd,
                    intval($count)
                );

            }
        }else if($type == 2)
        {
            //昨日
            $yesterday = date('d') - 1;
            $start=mktime(0, 0, 0, date('m'), $yesterday, date('Y'));
            $end=mktime(23, 59, 59, date('m'), $yesterday, date('Y'));
            for ($j = 0; $j < 24; $j ++)
            {
                $date_start = strtotime(date("Y-m-d H:i:s", $start + 3600 * $j));
                $date_end = strtotime(date("Y-m-d H:i:s", $start + 3600 * ($j + 1)));
                $count = AdminDaiRecord::find() -> Where(['>=','created_time',$date_start])->andWhere(['<=','created_time',$date_end])->count();
                $wxd = (string)($j);
                $data[$j] = array(
                    $wxd,
                    intval($count)
                );
            }

        }
        else if($type == 3)
        {
            //本周
            $timestamp = time();
            $start = strtotime(date('Y-m-d', strtotime("+0 week Monday", $timestamp))) - 604800;
           $end =  strtotime(date('Y-m-d', strtotime("+0 week Sunday", $timestamp))) + 24 * 3600 - 1 - 604800;
            for ($j = 0; $j < 7; $j ++) {
                $date_start = strtotime(date("Y-m-d H:i:s", $start + 86400 * $j));
                $date_end = strtotime(date("Y-m-d H:i:s", $start + 86400 * ($j + 1)));
                $count = AdminDaiRecord::find() -> Where(['>=','created_time',$date_start])->andWhere(['<=','created_time',$date_end])->count();
                
                $wxd = (string)($j+ 1);
                $data[$j] = array(
                    '星期' . $wxd,
                    intval($count)
                );
            }
        }else if($type == 4)
        {
            $start =  mktime(0,0,0,date('m'),1,date('Y'));
           $end =  mktime(23,59,59,date('m'),date('t'),date('Y'));

            for ($j = 0; $j < ($end + 1 - $start) / 86400; $j ++) {
                $date_start = strtotime(date("Y-m-d H:i:s", $start + 86400 * $j));

                $date_end = strtotime(date("Y-m-d H:i:s", $start + 86400 * ($j + 1)));
                $count = AdminDaiRecord::find() -> Where(['>=','created_time',$date_start])->andWhere(['<=','created_time',$date_end])->count();
                
                /*$count = AdminDaiRecord::find() ->count();*/
                $wxd = (string)(1 + $j);
                $data[$j] = array(
                    $wxd . '日',
                    intval($count)
                );
            }

        }
        return json_encode($data);
    }

    public function actionGetweixinfanschartcount()
    {
        $type = isset($_POST['date']) ? $_POST['date'] : 1;
        $data = array();
        if ($type == 1)
        {
            //今日
            $start = mktime(0,0,0,date('m'),date('d'),date('Y'));
            $end =   mktime(23, 59, 59, date('m'), date('d'), date('Y'));
            for ($i = 0; $i < 24; $i ++)
            {
                $date_start = strtotime(date("Y-m-d H:i:s", $start + 3600 * $i));
                $date_end = strtotime(date("Y-m-d H:i:s", $start + 3600 * ($i + 1)));
                $count = AdminMember::find() -> Where(['>=','created_time',$date_start])->andWhere(['<=','created_time',$date_end]) ->andWhere(['subscribe'=> 1]) ->count();
                $wxd = (string)$i;
                $data[$i] = array(
                    $wxd,
                    intval($count)
                );

            }
        }else if($type == 2)
        {
            //昨日
            $yesterday = date('d') - 1;
            $start=mktime(0, 0, 0, date('m'), $yesterday, date('Y'));
            $end=mktime(23, 59, 59, date('m'), $yesterday, date('Y'));
            for ($j = 0; $j < 24; $j ++)
            {
                $date_start = strtotime(date("Y-m-d H:i:s", $start + 3600 * $j));
                $date_end = strtotime(date("Y-m-d H:i:s", $start + 3600 * ($j + 1)));
                $count = AdminMember::find() -> Where(['>=','created_time',$date_start])->andWhere(['<=','created_time',$date_end])->andWhere(['subscribe'=> 1])->count();
                $wxd = (string)($j);
                $data[$j] = array(
                    $wxd,
                    intval($count)
                );
            }

        }
        else if($type == 3)
        {
            //本周
            $timestamp = time();
            $start = strtotime(date('Y-m-d', strtotime("+0 week Monday", $timestamp))) - 604800;
            $end =  strtotime(date('Y-m-d', strtotime("+0 week Sunday", $timestamp))) + 24 * 3600 - 1 - 604800;
            for ($j = 0; $j < 7; $j ++) {
                $date_start = strtotime(date("Y-m-d H:i:s", $start + 86400 * $j));
                $date_end = strtotime(date("Y-m-d H:i:s", $start + 86400 * ($j + 1)));
                $count = AdminMember::find() -> Where(['>=','created_time',$date_start])->andWhere(['<=','created_time',$date_end])->andWhere(['subscribe'=> 1])->count();

                $wxd = (string)($j+ 1);
                $data[$j] = array(
                    '星期' . $wxd,
                    intval($count)
                );
            }
        }else if($type == 4)
        {
            $start =  mktime(0,0,0,date('m'),1,date('Y'));
            $end =  mktime(23,59,59,date('m'),date('t'),date('Y'));

            for ($j = 0; $j < ($end + 1 - $start) / 86400; $j ++) {
                $date_start = strtotime(date("Y-m-d H:i:s", $start + 86400 * $j));

                $date_end = strtotime(date("Y-m-d H:i:s", $start + 86400 * ($j + 1)));
                $count = AdminMember::find() -> Where(['>=','created_time',$date_start])->andWhere(['<=','created_time',$date_end])->andWhere(['subscribe'=> 1])->count();

                /*$count = AdminDaiRecord::find() ->count();*/
                $wxd = (string)(1 + $j);
                $data[$j] = array(
                    $wxd . '日',
                    intval($count)
                );
            }

        }
        return json_encode($data);
    }

    public function actionProtocol()
    {
        $this->layout = false;
        return $this->render('protocol');
    }
   
}
