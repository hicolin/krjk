<?php

namespace backend\controllers;

use backend\models\AdminAward;
use backend\models\AdminCommission;
use backend\models\AdminDaiRecord;
use backend\models\AdminGrade;
use backend\models\AdminMember;
use common\controllers\PublicController;
use common\helps\ExportExcelController;
use common\models\UploadForm;
use Yii;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use mobile\controllers\PublicController as mPublicController;

/**
 * AdminMemberController implements the CRUD actions for AdminMember model.
 */
class AdminMemberController extends BaseController
{
    /**
     * @inheritdoc
     */
    public $layout = "lte_main";
    public $is_agent = 0;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all AdminMember models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = AdminMember::find()->joinwith('grades');
        $request = Yii::$app->request;
        $querys = Yii::$app->request->get('query');
        $grade = AdminGrade::find()->all();
        $query = self::condition($query,$querys);
        $agent = $request->get('agent');
        if(isset($agent)) {
            if($agent!=0){
                $query = $query->andWhere(['admin_grade.id'=>$agent]);
            }else{
                $query = $query->andWhere(['admin_member.grade'=>$agent]);
            }
        }
        $pageSize = (int)abs(PublicController::getSysInfo(36));
        $pagination = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => $pageSize,
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page']
        );
        $products = $query->offset($pagination->offset)
            ->orderBy('created_time DESC')
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        foreach ($products as &$val){
            $pre_member = AdminMember::findOne(['invitation'=>$val['bei_invitation']]);
            $val['pre_tel'] = $pre_member->tel;
            $val['pre_name'] = $pre_member->nickname;
        }
        $availableMoneySum = AdminMember::find()->sum('available_money');
        return $this->render('index', [
            'model' => $products,
            'pages' => $pagination,
            'query' => $querys,
            'is_agent' => $this->is_agent,
            'id' => '',
            'grade' =>$grade,
            'availableMoneySum'=>$availableMoneySum,
        ]);
    }

    // 查询条件
    public static function condition($query,$querys)
    {
        if (count($querys) > 0) {
            $realname = $querys['realname'];
            $nickname = $querys['nickname'];
            $grades = $querys['grade'];
            $tel = $querys['tel'];
            $isPartner = $querys['is_partner'];
            $isBlock = $querys['is_block'];
            $b_time = $querys['b_time'];
            $e_time = $querys['e_time'];
            if ($grades>0) {
                if ($grades == 4) {
                    $query = $query->andWhere(['=', 'admin_member.grade', 0]);
                } else {
                    $query = $query->andWhere(['=', 'admin_grade.id', $grades]);
                }
            }
            if ($nickname) {
                $query = $query->andWhere(['like', 'nickname', $nickname]);
            }
            if ($nickname) {
                $query = $query->andWhere(['like', 'nickname', $nickname]);
            }
            if ($realname) {
                $query = $query->andWhere(['like', 'real_name', $realname]);
            }
            if ($tel) {
                $query = $query->andWhere(['like', 'tel', $tel]);
            }
            if ($isPartner) {
                $query = $query->andWhere(['=', 'is_partner', $isPartner]);
            }
            if ($isBlock) {
                $query = $query->andWhere(['=', 'is_block', $isBlock]);
            }
            if($b_time) {
                $query = $query->andWhere(['>=','admin_member.created_time',$b_time]);
            }
            if($e_time) {
                $query = $query->andWhere(['<=','admin_member.created_time',$e_time]);
            }
        }
        return $query;
    }

    /**
     * Displays a single AdminMember model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AdminMember model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCreate()
    {
        $grade_name = AdminGrade::find()->all();
        $model = new AdminMember();
        $request = Yii::$app->request;
        if($model->load($request->post())) {
            $model->real_name = PublicController::filter($model->real_name);
            $model->tel = PublicController::filter($model->tel);
            $model->invitation = PublicController::getInvitation();
            $model->created_time = time();
            $model->update_time = time();
            if($model->save()) {
                return $this->redirect(['index']);
            }else {
                return $this->render('create', [
                    'model' => $model,
                    'grade_name'=>$grade_name,
                ]);
            }
        }else {
            return $this->render('create', [
                'model' => $model,
                'grade_name'=>$grade_name,
            ]);
        }
    }

    /**
     * Updates an existing AdminMember model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->isGet) {
            Yii::$app->session['member_update_url'] = Yii::$app->request->referrer;
        }
        $model = $this->findModel($id);
        $grade_name = AdminGrade::find()->all();
        $request = Yii::$app->request;
        if($model->load($request->post())) {
            $model->real_name = PublicController::filter($model->real_name);
            $model->tel = PublicController::filter($model->tel);
            $model->invitation = PublicController::filter($model->invitation);
            $model->update_time = time();
            if($model->save()) {
                Yii::$app->session->setFlash('success', '更新成功');
                $url = Yii::$app->session['member_update_url'];
                if ($url) {
                    unset(Yii::$app->session['member_update_url']);
                    return $this->redirect($url);
                }
                return $this->redirect(['admin-member/update', 'id' => $id]);
            }else {
                return $this->render('update', [
                    'model' => $model,
                    'grade_name'=>$grade_name,
                ]);
            }
        }else {
            return $this->render('update', [
                'model' => $model,
                'grade_name'=>$grade_name,
            ]);
        }
    }

    /**
     * Deletes an existing AdminMember model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDelrecord(array $ids)
    {
        if (count($ids) > 0) {
            $c = AdminMember::deleteAll(['in', 'id', $ids]);
            echo json_encode(array('errno' => 0, 'data' => $c, 'msg' => json_encode($ids)));
        } else {
            echo json_encode(array('errno' => 2, 'msg' => ''));
        }
    }

    // 更改锁定状态
    public function actionChangeBlock()
    {
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->get('id');
            $is_block = Yii::$app->request->get('is_block');
            $is_block = $is_block == 1 ? 2 : 1;   // 状态取反
            $res = AdminMember::updateAll(['is_block'=>$is_block],['id'=>$id]);
            if($res){
                return json_encode(['status'=>200,'msg'=>'更改成功']);
            }
            return json_encode(['status'=>100,'msg'=>'更改失败']);
        }
    }

    // 查看统计
    public function actionStatistic()
    {

        $userId = Yii::$app->request->get('userId');
        if (!$userId) { // post 请求值
            $userId = Yii::$app->request->post('userId');
        }
        $member = AdminMember::findOne($userId);
        if (!$member) {
            return $this->json(100, '用户不存在');
        }
        $cpaPrice = trim(PublicController::getSysInfo(37));
        $cpsPrice = trim(PublicController::getSysInfo(38));
        $members = AdminMember::findAll(['bei_invitation' => $member->invitation]);
        $ids = mPublicController::getSonIds($members);
        array_unshift($ids, $userId);  // 自身id添加进数组
        $now = time();
        $b_month = strtotime(date('Y-m-01 00:00:00'));
        $b_day = strtotime(date('Y-m-d') . ' 00:00:00');
        $b_week = $b_day - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600;
        // 团队成员
        $allMembers = AdminMember::find()->where(['in', 'id', $ids])->count();
        $monthMembers = AdminMember::find()->where(['in', 'id', $ids])
            ->andWhere(['between', 'created_time', $b_month, $now])->count();
        $weekMembers = AdminMember::find()->where(['in', 'id', $ids])
            ->andWhere(['between', 'created_time', $b_week, $now])->count();
        // 订单数量
        $allOrders = AdminDaiRecord::find()->where(['in', 'tid', $ids])->andWhere(['match_num' => 1])
            ->count();
        $monthOrders = AdminDaiRecord::find()->where(['in', 'tid', $ids])->andWhere(['match_num' => 1])
            ->andWhere(['between', 'match_time', $b_month, $now])->count();
        $weekOrders = AdminDaiRecord::find()->where(['in', 'tid', $ids])->andWhere(['match_num' => 1])
            ->andWhere(['between', 'match_time', $b_week, $now])->count();
        $allCpaOrders = AdminDaiRecord::find()->joinWith('product')
            ->where(['in', 'tid', $ids])->andWhere(['match_num' => 1])
            ->andWhere(['admin_dai_product.fy_type' => 1])->count();
        $allCpsOrders = AdminDaiRecord::find()->joinWith('product')
            ->where(['in', 'tid', $ids])->andWhere(['match_num' => 1])
            ->andWhere(['admin_dai_product.fy_type' => 2])->count();
        $monthCpaOrders = AdminDaiRecord::find()->joinWith('product')
            ->where(['in', 'tid', $ids])->andWhere(['match_num' => 1])
            ->andWhere(['between', 'match_time', $b_month, $now])
            ->andWhere(['admin_dai_product.fy_type' => 1])->count();
        $monthCpsOrders = AdminDaiRecord::find()->joinWith('product')
            ->where(['in', 'tid', $ids])->andWhere(['match_num' => 1])
            ->andWhere(['between', 'match_time', $b_month, $now])
            ->andWhere(['admin_dai_product.fy_type' => 2])->count();
        $weekCpaOrders = AdminDaiRecord::find()->joinWith('product')
            ->where(['in', 'tid', $ids])->andWhere(['match_num' => 1])
            ->andWhere(['between', 'match_time', $b_week, $now])
            ->andWhere(['admin_dai_product.fy_type' => 1])->count();
        $weekCpsOrders = AdminDaiRecord::find()->joinWith('product')
            ->where(['in', 'tid', $ids])->andWhere(['match_num' => 1])
            ->andWhere(['between', 'match_time', $b_week, $now])
            ->andWhere(['admin_dai_product.fy_type' => 2])->count();

        // 收入金额
//        $query = AdminAward::find()->where(['in', 'user_id', $ids]);
//        $allAward = $query->sum('money');
//        $monthAward = $query->andWhere(['between', 'create_time', $b_month, $now])->sum('money');
//        $weekAward = $query->andWhere(['between', 'create_time', $b_week, $now])->sum('money');
//        $weekAward || $weekAward = '0.00';
        $allAward = $allCpaOrders * $cpaPrice + $allCpsOrders * $cpsPrice;
        $monthAward = $monthCpaOrders * $cpaPrice + $monthCpsOrders * $cpsPrice;
        $weekAward = $weekCpaOrders * $cpaPrice + $weekCpsOrders * $cpsPrice;

        if (Yii::$app->request->isPost) {
            $pickDate = Yii::$app->request->post('pickDate');
            $begin = $pickDate . '-01';
            $beginTime = strtotime($begin);
            $endTime = strtotime("{$begin} + 1 month");
            $members = AdminMember::find()->where(['in', 'id', $ids])
                ->andWhere(['between', 'created_time', $beginTime, $endTime])->count();
            $orders = AdminDaiRecord::find()->where(['in', 'tid', $ids])->andWhere(['match_num' => 1])
                ->andWhere(['between', 'match_time', $beginTime, $endTime])->count();
            $cpaOrders = AdminDaiRecord::find()->joinWith('product')
                ->where(['in', 'tid', $ids])->andWhere(['match_num' => 1])
                ->andWhere(['between', 'match_time', $beginTime, $endTime])
                ->andWhere(['admin_dai_product.fy_type' => 1])->count();
            $cpsOrders = AdminDaiRecord::find()->joinWith('product')
                ->where(['in', 'tid', $ids])->andWhere(['match_num' => 1])
                ->andWhere(['between', 'match_time', $beginTime, $endTime])
                ->andWhere(['admin_dai_product.fy_type' => 2])->count();
//            $award = AdminAward::find()->where(['in', 'user_id', $ids])
//                ->andWhere(['between', 'create_time', $beginTime, $endTime])->sum('money');
            $award = $cpaOrders * $cpaPrice + $cpsOrders * $cpsPrice;
            $data = compact('members', 'orders', 'award', 'cpaOrders', 'cpsOrders');
            return $this->json(200, '获取成功', $data);
        }
        $data = compact('allMembers', 'monthMembers', 'weekMembers', 'allOrders', 'monthOrders', 'weekOrders',
            'allAward', 'monthAward', 'weekAward', 'allCpaOrders', 'allCpsOrders', 'monthCpaOrders',
            'monthCpsOrders', 'weekCpaOrders', 'weekCpsOrders');
        return json_encode(['status' => 200, 'data' => $data]);
    }

    /**
     * Finds the AdminMember model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminMember the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminMember::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     *查看我的下级
     */
    public function actionAgent()
    {
        $this->is_agent = 1;
        $id = yii::$app->request->get('id');
        $grade = AdminGrade::find()->all();
        //上一级，根据id查找上一级
        $pre_molde = AdminMember::find()->where(['invitation'=>AdminMember::findOne($id)->bei_invitation])->one();
        if($pre_molde) {
            $pre_url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/agent','id'=>$pre_molde->id]);
        }else {
            $pre_url = Yii::$app->urlManager->createUrl(Yii::$app->controller->id.'/index');
        }
        $arr_id = $this->getMemberIds($id);
        $query = AdminMember::find()->joinwith('grades')->andWhere(['in','admin_member.id',$arr_id]);
        $querys = Yii::$app->request->get('query');
        if (count($querys) > 0) {
            $realname = $querys['realname'];
            $tel = $querys['tel'];
            if ($realname) {
                $query = $query->andWhere(['like', 'real_name', $realname]);
            }
            if ($querys['grade']>0) {
            $query = $query->andWhere(['=', 'admin_grade.id', $querys['grade']]);
            } 
            if ($tel) {
                $query = $query->andWhere(['like', 'tel', $tel]);
            }
            if ($realname) {
                $query = $query->andWhere(['like', 'real_name', $realname]);
            }
            if ($tel) {
                $query = $query->andWhere(['like', 'tel', $tel]);
            }
            if($querys['b_time']) {
                $query = $query->andWhere(['>=','admin_member.created_time',$querys['b_time']]);
            }
            if($querys['e_time']) {
                $query = $query->andWhere(['<=','admin_member.created_time',$querys['e_time']]);
            }
        }
        if(isset($agent)) {
            if($agent!=0){
                $query = $query->andWhere(['admin_grade.id'=>$agent]);
            }else{
                $query = $query->andWhere(['admin_member.grade'=>$agent]);
            }
        }
        $pagination = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => '10',
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page']
        );
        $model = $query->offset($pagination->offset)
            ->orderBy('created_time DESC')
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        foreach ($model as &$val){
            $pre_member = AdminMember::findOne(['invitation'=>$val['bei_invitation']]);
            $val['pre_tel'] = $pre_member->tel;
            $val['pre_name'] = $pre_member->nickname;
        }
        $availableMoneySum = AdminMember::find()->sum('available_money');
        return $this->render('index', [
            'model' => $model,
            'pages' => $pagination,
            'query' => $querys,
            'is_agent' => $this->is_agent,
            'pre_url' => $pre_url,
            'id' => $id,
            'grade' =>$grade,
            'availableMoneySum'=>$availableMoneySum,
        ]);
    }

    /**
     *根据会员id获取下级会员的id集合
     */
    protected function getMemberIds($id)
    {
        $arr_id = [];
        $member = AdminMember::findOne($id);
        if($member) {
            $model = AdminMember::find()->where(['bei_invitation'=>$member->invitation])->all();
            if($model) {
                foreach ($model as $list) {
                    $arr_id[] = $list->id;
                }
            }
        }
        return $arr_id;
    }

    /**
     * 导出数据
     */
    public function actionExport()
    {
        $excel = new ExportExcelController();
        $model = Adminmember::find()->asArray()->all();
        $data[] = ['序号','昵称','真实姓名','openid','手机号码','代理商','代理推广佣金/￥','贷呗推广佣金/￥','可提现金额/￥','邀请码','被邀请码','省市区','详细地址','创建时间'];
        foreach ($model as $k=> $arr) {
            if($arr['grade']==0){
                $msg = '普通会员'; 
            }elseif($arr['grade']==1){
                $msg = '铜牌会员';    
            }elseif($arr['grade']==2){
                $msg = '银牌会员';
            }elseif($arr['grade']==3){
                $msg = '金牌会员';
            }
            $data[$k+1]['id'] = $arr['id'];
            $data[$k+1]['nickname'] = $arr['nickname']?:'无';
            $data[$k+1]['real_name'] = $arr['real_name']?:'无';
            $data[$k+1]['openid'] = $arr['openid']?:'无';
            $data[$k+1]['tel'] = $arr['tel']?:'无';
            $data[$k+1]['grade'] = $msg;
            $data[$k+1]['promotion_commission'] = $arr['promotion_commission'];
            $data[$k+1]['dai_commission'] = $arr['dai_commission'];
            $data[$k+1]['available_money'] = $arr['available_money'];
            $data[$k+1]['invitation'] = $arr['invitation']?:'无';
            $data[$k+1]['bei_invitation'] = $arr['bei_invitation']?:'无';
            $data[$k+1]['province'] = $arr['province']?:'无';
            $data[$k+1]['address'] = $arr['address']?:'无';
            $data[$k+1]['created_time'] = date('Y-m-d',$arr['created_time']);
        }

        $filename = '会员列表'.date('Ymd',time());
        $excel->download($data, $filename);
        //echo "<script>history.go(-1)</script>";

    }

    public function actionCommission($id)
    {
        $query = AdminCommission::find()->joinWith('member')->where(['user_id'=>$id]);
        $querys = Yii::$app->request->get('query');
        if (count($querys) > 0) {
            $name = $querys['name'];
            $type = $querys['type'];
            $b_time = $querys['b_time'];
            $e_time = $querys['e_time'];
            if ($name) {
                $query = $query->andWhere(['like', 'admin_member.real_name', $name]);
            }
            if($type>0) {
                $query = $query->andWhere(['admin_commission.type'=>$type]);
            }
            if($b_time) {
                $query = $query->andWhere(['>=','admin_commission.created_time',$b_time]);
            }
            if($e_time) {
                $query = $query->andWhere(['<=','admin_commission.created_time',$e_time]);
            }
        }
        $pageSize = (int)abs(PublicController::getSysInfo(36));
        $pagination = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => $pageSize,
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page']
        );
        $model = $query->offset($pagination->offset)
            ->orderBy('created_time DESC')
            ->limit($pagination->limit)
            ->all();
        return $this->render('commission',[
            'model' => $model,
            'pages' => $pagination,
            'query' => $querys,
            'id' => $id,
        ]);
    }

    public function actionCreateCommission()
    {
        $id = Yii::$app->request->get('id');
        if(Yii::$app->request->isPost) {
            $user_id = intval(Yii::$app->request->post('user_id'));
            $name = PublicController::filter(Yii::$app->request->post('name'));
            $money = floatval(Yii::$app->request->post('money'));
            $commission_money = floatval(Yii::$app->request->post('commission_money'));
            $model = new AdminCommission();
            $model->user_id = $user_id;
            $model->p_name = $name;
            $model->money = $money;
            $model->commission_money = $commission_money;
            $model->created_time = time();
            $model->type = 2;
            $model->msg = '推广码返佣';
            $member = AdminMember::findOne($user_id);
            $member->dai_commission += $commission_money;
            $member->available_money += $commission_money;
            $transaction = Yii::$app->db->beginTransaction();
            if ($member->validate() && $model->validate()) {
                try {
                    $member->save();
                    $model->save();
                    $transaction->commit();
                    /***************推送模板消息*******************/
                    //推送模板消息
                    if($member->is_open) {
                        //$url = Yii::$app->urlManager->urlManagerFrontend(['/member/product','type'=>1]);
                        $url = Yii::$app->request->getHostInfo().'/index.php?r=member%2Faccount';
                        $temp = '52PFBmUKkigsiu2MoTJgZhxm-Tfeg8b3BqGh85TZhBI';
                        $data['first'] = ['value'=>'您有一个收益到账通知','color'=>'#173177'];
                        $data['keyword1'] = ['value'=>'1','color'=>'#173177'];
                        $data['keyword2'] = ['value'=>date('Y-m-d H:i'),'color'=>'#173177'];
                        $data['keyword3'] = ['value'=>$commission_money,'color'=>'#173177'];
                        $data['remark'] = ['value'=>$name.'收益到账啦','color'=>'#173177'];
                        PublicController::sendTempMsg($temp,$member->openid,$data,$url);
                    }
                    /***************推送模板消息*******************/
                    $this->redirect(['commission','id'=>$user_id]);
                    Yii::$app->end();
                } catch (Exception $e) {
                    //捕获错误
                    $transaction->rollback();
                    return $this->render('create-commission');
                }
            }else{
                return 300;
            }
        }
        return $this->render('create-commission',[
            'id'=>$id
        ]);
    }

    /**
     * 导出数据
     */
    public function actionExportCommission($id)
    {
        $excel = new ExportExcelController();
        $model = AdminCommission::find()->joinWith('member')->where(['user_id'=>$id])->asArray()->all();
        $data[] = ['序号','交易人','类型','交易金额/￥','返佣金额/￥','产品名称','创建时间'];
        foreach ($model as $k=> $arr) {
            $data[$k+1]['id'] = $arr['id'];
            $data[$k+1]['jy_user_id'] = $arr['member']['real_name']?:'无';
            $data[$k+1]['type'] = $arr['type']==1?'购买代理':'代呗返佣';
            $data[$k+1]['money'] = $arr['money']?:'0';
            $data[$k+1]['commission_money'] = $arr['commission_money']?:'0';
            $data[$k+1]['p_name'] = $arr['type']==1?'购买代理':$arr['p_name'];
            $data[$k+1]['created_time'] = date('Y-m-d',$arr['created_time']);
        }
        $filename = AdminMember::findOne($id)->real_name.'-返佣明细'.date('Ymd',time());
        $excel->download($data, $filename);
        //echo "<script>history.go(-1)</script>";

    }



    /**
     * Deletes an existing AdminMember model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCommissionDelete($id)
    {
        AdminCommission::findOne($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionCommissionDelrecord(array $ids)
    {
        if (count($ids) > 0) {
            $this->actionDel($ids);
            $c = AdminCommission::deleteAll(['in', 'id', $ids]);
            echo json_encode(array('errno' => 0, 'data' => $c, 'msg' => json_encode($ids)));
        } else {
            echo json_encode(array('errno' => 2, 'msg' => ''));
        }
    }

    public function actionDel(array $ids){
        // print_r($ids);exit;
        foreach ($ids as $key => $value) {
            $commission = AdminCommission::find()->where(['id'=>$value])->one();
            $member = Adminmember::find()->where(['id'=>$commission['user_id']])->one();
            if($commission->type==1){ //购买代理返佣返佣
                $member->promotion_commission -=$commission->commission_money;
                $member->available_money -=$commission->commission_money;
            }elseif($commission->type==2){ //购买贷呗返佣
                $member->dai_commission -=$commission->commission_money;
                $member->available_money -=$commission->commission_money;
            }
                $member->save(false);
        }
            // if($member->save(false)){

            //     return true;
            // }
    }
}
