<?php

namespace backend\controllers;

use backend\models\AdminCommission;
use backend\models\AdminCount;
use backend\models\AdminDaiProduct;
use backend\models\AdminDaiRecord;
use backend\models\AdminMember;
use common\controllers\PublicController;
use common\helps\ExportExcelController;
use Yii;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use mobile\controllers\BaseController as mBaseController;

/**
 * AdminCountController implements the CRUD actions for AdminCount model.
 */
class AdminCountController extends BaseController
{
    /**
     * @inheritdoc
     */
    public $layout = "lte_main";

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
     * Lists all AdminCount models.
     * @return mixed
     */
    public function actionIndex()
    {
        $querys = Yii::$app->request->get('query');
        $isMatch = Yii::$app->request->get('is_match');
        $rate = ['1' => '注册成功', '2' => '申请中', '3' => '审核通过', '4' => '审核失败'];
        $query = AdminCount::find()->Orderby('id desc');
        if ($isMatch && $isMatch == 2) {
            $query = $query->andWhere(['is_match' => $isMatch]);
        }
        $query = self::condition($query, $querys);
        $pageSize = (int)abs(PublicController::getSysInfo(36));
        $pagination = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => $pageSize,
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page']
        );
        $products = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index', [
            'model' => $products,
            'pages' => $pagination,
            'rate' => $rate,
            'query' => $querys,
        ]);
    }

    // 搜索条件
    public static function condition($query, $querys)
    {
        if (count($querys) > 0) {
            $name = $querys['name'];
            $tel = $querys['tel'];
            $p_name = $querys['p_name'];
            $b_time = $querys['b_time'];
            $e_time = $querys['e_time'];
            if ($name) {
                $query = $query->andWhere(['like', 'name', $name]);
            }
            if ($tel) {
                $query = $query->andWhere(['like', 'tel', $tel]);
            }
            if ($querys['p_name']) {
                $query = $query->andWhere(['like', 'p_name', $p_name]);
            }
            if (isset($querys['is_match']) && $querys['is_match'] < 4) {
                $query = $query->andWhere(['is_match' => $querys['is_match']]);
            }
            if ($b_time) {
                $query = $query->andWhere(['>=', 'created_time', $b_time]);
            }
            if ($e_time) {
                $query = $query->andWhere(['<=', 'created_time', $e_time]);
            }
        }
        return $query;
    }

    /**
     * Displays a single AdminCount model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $rate = ['1' => '注册成功', '2' => '申请中', '3' => '审核通过', '4' => '审核失败'];
        return $this->render('view', [
            'model' => $this->findModel($id),
            'rate' => $rate,
        ]);

    }

    /**
     * Creates a new AdminCount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminCount();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AdminCount model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AdminCount model.
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
            $c = AdminCount::deleteAll(['in', 'id', $ids]);
            echo json_encode(array('errno' => 0, 'data' => $c, 'msg' => json_encode($ids)));
        } else {
            echo json_encode(array('errno' => 2, 'msg' => ''));
        }
    }

    // 导出数据
    public function actionExport()
    {
        $excel = new ExportExcelController();
        $filename = '统计管理' . date('Ymd', time());
        $model = AdminCount::find()->orderBy(['id' => SORT_DESC]);
        $query = Yii::$app->request->get('query');
        $model = self::condition($model, $query);
        $model = $model->asArray()->all();
        $apply_rate = [1 => '注册成功', 2 => '申请中', 3 => '审核通过', 4 => '审核失败'];
        $is_match = [0 => '否', 1 => '是'];
        $fy_type = [1 => 'cpa 固定返佣(元)', 2 => 'cps 百分比返佣(点)'];
        $data[] = ['手机号码', '姓名', '产品名称', '返佣金额/元', '返佣类型', '申请进度', '是否匹配', '导入时间'];
        foreach ($model as $k => $arr) {
            $data[$k + 1]['tel'] = $arr['tel'];
            $data[$k + 1]['name'] = $arr['name'];
            $data[$k + 1]['p_name'] = $arr['p_name'];
            $data[$k + 1]['commission_money'] = $arr['commission_money'];
            $data[$k + 1]['fy_type'] = $fy_type[$arr['p_name']];
            $data[$k + 1]['apply_rate'] = $apply_rate[$arr['apply_rate']];
            $data[$k + 1]['is_match'] = $is_match[$arr['is_match']];
            $data[$k + 1]['created_time'] = date('Y-m-d H:i:s', $arr['created_time']);
        }
        $excel->download($data, $filename);
    }

    /**
     * Finds the AdminCount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminCount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminCount::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     *导入excel
     */
    public function actionLoad()
    {
        Yii::getLogger()->log(Yii::$app->request->get('file'), 2, '调试');
        $excelFile = Yii::$app->request->get('file');
        $phpexcel = new PHPExcel;
        $excelReader = PHPExcel_IOFactory::createReader('Excel5');
        $phpexcel = $excelReader->load($excelFile)->getSheet(0);// 载入文件并获取第一个sheet
        $total_line = $phpexcel->getHighestRow();
        $total_column = $phpexcel->getHighestColumn();
        for ($row = 2; $row <= $total_line; $row++) {
            $data = array();
            for ($column = 'A'; $column <= $total_column; $column++) {
                $data[] = trim($phpexcel->getCell($column . $row)->getValue());
            }
        }
    }

    // 获取申请记录
    public function getDaiRecord($title, $tel)
    {
        $model = AdminDaiRecord::find()
            ->joinWith('member')
            ->joinWith('product')
            ->andWhere(['admin_dai_record.match_num' => 0, 'admin_dai_product.title' => $title])
            ->andWhere(['admin_dai_record.tel' => $tel])
            ->orderBy('created_time desc')
            ->one();
        return $model;
    }

    // 按照返佣比例分佣
    public function doCommissionByRate($data)
    {
        $commissionRate = intval($data[0]['commission_rate']) / 100;
        $totalNum = count($data); // 总条数
        $successNum = (int)$totalNum * $commissionRate;
        $shuffleData = PublicController::shuffle_assoc($data);
        $sum = 1;
        foreach ($shuffleData as $list) {
            if ($sum <= $successNum) {
                $model = $this->getDaiRecord($list['p_name'], $list['tel']);
                if ($model) {
                    $this->dellData($model, $list);
                }
            } else {
                $model = $this->getDaiRecord($list['p_name'], $list['tel']);
                if ($model) {
                    $model->match_num = 2; // 已失效
                    $model->type = $list['apply_rate'];
                    $model->match_time = time(); // 更新匹配时间
                    $model->save(false);
                    $count = AdminCount::findOne(['order_sn' => $list['order_sn']]);
                    $count->is_match = 3; // 匹配失败
                    $count->save(false);
                }
            }
            $sum++;
        }
    }

    /**
     * 导入数据统计
     */
    public function actionCommission()
    {
        $data = Yii::$app->request->post('data');
        if ($data) {
            $commissionRate = $data[0]['commission_rate']; // 取第一条数据的返佣比例
            if ($commissionRate) {
                $commissionRate = (int)$commissionRate;
                if ($commissionRate < 0 || $commissionRate > 100) {
                    return 300;
                }
                $this->doCommissionByRate($data);
            } else {
                foreach ($data as $list) {
                    $query = AdminDaiRecord::find()
                        ->joinWith('member')
                        ->joinWith('product')
                        ->andWhere(['admin_dai_record.match_num' => 0, 'admin_dai_product.title' => $list['p_name']]);//满足的条件
                    //如果存在手机号，通过手机号匹配用户申请表，匹配格式为187****7256，每条信息最多只能匹配一次
                    if ($list['tel'] != '无') {
                        if ($list['type_tel'] == 1) {
                            //导入表格的电话号码 与 申请列表  admin-dai-record 里的申请人的手机号做匹配  前3后4
                            $query = $query->andWhere(['concat_ws(\'****\',substring(admin_dai_record.tel, 1, 3),substring(admin_dai_record.tel, 8, 4))' => $list['tel']]);
                        } elseif ($list['type_tel'] == 2) {
                            //导入表格的电话号码 与 申请列表  admin-dai-record 里的申请人的手机号做匹配  前3后3
                            $query = $query->andWhere(['concat_ws(\'*****\',substring(admin_dai_record.tel, 1, 3),substring(admin_dai_record.tel, 9, 3))' => $list['tel']]);
                        } elseif ($list['type_tel'] == 4) {
                            //导入表格的电话号码 与 申请列表  admin-dai-record 里的申请人的手机号做匹配  前3后2
                            $query = $query->andWhere(['concat_ws(\'******\',substring(admin_dai_record.tel, 1, 3),substring(admin_dai_record.tel, 10, 2))' => $list['tel']]);
                        } elseif ($list['type_tel'] == 3) {
                            //导入表格的电话号码 与 申请列表  admin-dai-record 里的申请人的手机号做匹配  全号匹配
                            $query = $query->andWhere(['admin_dai_record.tel' => $list['tel']]);
                        }
                    } else {
                        $query = $query->andWhere(['admin_dai_record.name' => $list['name']]);
                    }
                    $count = $query->groupBy('admin_dai_record.tel')->count();//查询匹配条数
                    if ($count > 1) {  //大于一条 号码 产品 名字  出现重复数据
                        $adminCount = AdminCount::find()->where(['order_sn' => $list['order_sn']])->one();
                        $adminCount->is_match = 2;
                        $adminCount->save(false);
                    } elseif ($count == 1) {  //只有一条 正确
                        $model = $query->one();
                        $this->dellData($model, $list);
                    }
                }
            }
        }
        return 100;
    }

    // 佣金
    public function actionCommission2($money, $grade, $num)
    {
        $rate = explode(',', PublicController::getSysInfo(18));    //1级 金牌
        $rate2 = explode(',', PublicController::getSysInfo(19));  //2级 银牌
        $rate3 = explode(',', PublicController::getSysInfo(20)); //3级  铜牌
        if ($grade != 0) {
            if ($grade == 3) { //  金牌 一 二 三 级
                foreach ($rate as $key => $value) {
                    if ($key == ($num - 1)) {
                        $commisssion = number_format($money * $value / 100, 2, '.', '');
                    }
                }
            }
            if ($grade == 2) { //  银牌 一 二 三 级
                foreach ($rate2 as $key => $value) {
                    if ($key == ($num - 1)) {
                        $commisssion = number_format($money * $value / 100, 2, '.', '');
                    }
                }
            }
            if ($grade == 1) { //  铜牌 一 二 三 级
                foreach ($rate3 as $key => $value) {
                    if ($key == ($num - 1)) {
                        $commisssion = number_format($money * $value / 100, 2, '.', '');
                    }
                }
            }
        } else {
            $commisssion = 0;
        }
        return $commisssion;
    }

    /*
        fy_type  1:按点返佣  2：%返佣
    */
    public function actionCommission3($pid, $money, $grade, $num)
    {
        $product = AdminDaiProduct::find()->where(['id' => $pid])->one();
        $rate = explode(',', $product->fy_one);    //1级 金牌
        $rate2 = explode(',', $product->fy_two);  //2级 银牌
        $rate3 = explode(',', $product->fy_three); //3级  铜牌
        if ($grade != 0) {
            if ($grade == 3) { //  金牌 一 二 三 级
                foreach ($rate as $key => $value) {
                    if ($key == ($num - 1)) {
                        if ($product->fy_type == 2) { //
                            $commisssion = number_format($money * $value / 100, 2, '.', '');
                        } else if ($product->fy_type == 1) {
                            $commisssion = $value;
                        }
                    }
                }
            }
            if ($grade == 2) { //  银牌 一 二 三 级
                foreach ($rate2 as $key => $value) {
                    if ($key == ($num - 1)) {
                        if ($product->fy_type == 2) { //
                            $commisssion = number_format($money * $value / 100, 2, '.', '');
                        } else if ($product->fy_type == 1) {
                            $commisssion = $value;
                        }
                    }
                }
            }
            if ($grade == 1) { //  铜牌 一 二 三 级
                foreach ($rate3 as $key => $value) {
                    if ($key == ($num - 1)) {
                        if ($product->fy_type == 2) { //
                            $commisssion = number_format($money * $value / 100, 2, '.', '');
                        } else if ($product->fy_type == 1) {
                            $commisssion = $value;
                        }
                    }
                }
            }
        } else {
            $commisssion = 0;
        }
        return $commisssion;
    }

    /**
     * 处理数据
     * @param $model
     * @param $list
     */
    public function dellData($model, $list)
    {
        //将该条数据的匹配状态改成1
        $dai_record = AdminCount::find()->where(['order_sn' => $list['order_sn']])->one();
        $product = AdminDaiProduct::find()->where(['id' => $model->pid])->one();
        if ($dai_record && $dai_record->is_match == 0) {
            $dai_record->fy_type = $product->fy_type;
            $dai_record->is_match = 1;
            if (!$dai_record->save(false)) {
                return false;
            }
        }
        //将匹配到的申请记录匹配次数加1
        $model->match_num = 1;
        $model->type = $list['apply_rate'];
        $model->match_time = time(); // 更新匹配时间
        $model->save(false);
        //查找上级
        $pre_member = AdminMember::find()->where(['id' => $model->tid])->one();//1级
        $pre_member2 = AdminMember::find()->where(['invitation' => $pre_member->bei_invitation])->one();//2级
        $pre_member3 = AdminMember::find()->where(['invitation' => $pre_member2->bei_invitation])->one();//3级

        $pre_commission = $this->actionCommission3($model->pid, $list['money'], $pre_member->grade, 1);
        $pre_commission2 = $this->actionCommission3($model->pid, $list['money'], $pre_member2->grade, 2);
        $pre_commission3 = $this->actionCommission3($model->pid, $list['money'], $pre_member3->grade, 3);
        // 新增字段 姓名不存在则记录手机号
        if (!$list['name']) {
            $list['name'] = $list['tel'];
        }

        if ($pre_member) {
            $pre_member->dai_commission += $pre_commission;
            $pre_member->available_money += $pre_commission;
            if (!$pre_member->validate()) {
                $tmp_earr = $pre_member->getFirstErrors();
                foreach ($pre_member->activeAttributes() as $ati) {
                    if (isset($tmp_earr[$ati]) && !empty($tmp_earr[$ati]))
                        echo $tmp_earr[$ati];
                }
            }
            $pre_member->save(false);
            //记录返佣明细
            $commission = new AdminCommission();
            $commission->user_id = $pre_member->id;
            $commission->jy_user_id = $model->user_id;
            $commission->jy_user_info = $list['name'] . ' | 1';
            $commission->type = 2;
            $commission->money = $list['money'];
            $commission->commission_money = $pre_commission;
            $commission->created_time = time();
            $commission->openid = $pre_member->openid;
            $commission->jy_openid = $model->member['openid'];
            $commission->msg = '统计数据';
            $commission->p_name = $dai_record->p_name;
            $commission->save('false');
            if (!$commission->validate()) {
                $tmp_earr = $commission->getFirstErrors();
                foreach ($commission->activeAttributes() as $ati) {
                    if (isset($tmp_earr[$ati]) && !empty($tmp_earr[$ati]))
                        echo $tmp_earr[$ati];
                }
            }
        }

        if ($pre_member2 && $pre_commission2 != 0) {
            $pre_member2->dai_commission += $pre_commission2;
            $pre_member2->available_money += $pre_commission2;
            if (!$pre_member2->validate()) {
                $tmp_earr = $pre_member2->getFirstErrors();
                foreach ($pre_member2->activeAttributes() as $ati) {
                    if (isset($tmp_earr[$ati]) && !empty($tmp_earr[$ati]))
                        echo $tmp_earr[$ati];
                }
            }
            $pre_member2->save(false);
            //记录返佣明细
            $commission = new AdminCommission();
            $commission->user_id = $pre_member2->id;
            $commission->jy_user_id = $model->user_id;
            $commission->jy_user_info = $list['name'] . ' | 2';
            $commission->type = 2;
            $commission->money = $list['money'];
            $commission->commission_money = $pre_commission2;
            $commission->created_time = time();
            $commission->openid = $pre_member2->openid;
            $commission->jy_openid = $model->member['openid'];
            $commission->msg = '统计数据';
            $commission->p_name = $dai_record->p_name;
            $commission->save('false');
            if (!$commission->validate()) {
                $tmp_earr = $commission->getFirstErrors();
                foreach ($commission->activeAttributes() as $ati) {
                    if (isset($tmp_earr[$ati]) && !empty($tmp_earr[$ati]))
                        echo $tmp_earr[$ati];
                }
            }
        }

        if ($pre_member3 && $pre_commission3 != 0) {
            $pre_member3->dai_commission += $pre_commission3;
            $pre_member3->available_money += $pre_commission3;
            if (!$pre_member3->validate()) {
                $tmp_earr = $pre_member3->getFirstErrors();
                foreach ($pre_member3->activeAttributes() as $ati) {
                    if (isset($tmp_earr[$ati]) && !empty($tmp_earr[$ati]))
                        echo $tmp_earr[$ati];
                }
            }
            $pre_member3->save('false');
            //记录返佣明细
            $commission = new AdminCommission();
            $commission->user_id = $pre_member3->id;
            $commission->jy_user_id = $model->user_id;
            $commission->jy_user_info = $list['name'] . ' | 3';
            $commission->type = 2;
            $commission->money = $list['money'];
            $commission->commission_money = $pre_commission3;
            $commission->created_time = time();
            $commission->openid = $pre_member3->openid;
            $commission->jy_openid = $model->member['openid'];
            $commission->msg = '统计数据';
            $commission->p_name = $dai_record->p_name;
            $commission->save('false');
            if (!$commission->validate()) {
                $tmp_earr = $commission->getFirstErrors();
                foreach ($commission->activeAttributes() as $ati) {
                    if (isset($tmp_earr[$ati]) && !empty($tmp_earr[$ati]))
                        echo $tmp_earr[$ati];
                }
            }
        }

        /***************推送模板消息*******************/
        $temp = Yii::$app->params['wx']['sms']['income_receive'];
        //推送模板消息
        if ($pre_member->is_open) {
            $title = "收益到账";
            $content = "{$list['p_name']}收益到账啦。收益金额：{$pre_commission}元；到账时间：" . date('Y-m-d H:i');
            mBaseController::writeNotice($pre_member->id, $title, $content);

            $url = Yii::$app->request->getHostInfo() . '/index.php?r=member%2Faccount';
            $data['first'] = ['value' => '您有一个收益到账通知', 'color' => '#173177'];
            $data['income_amount'] = ['value' => $pre_commission, 'color' => '#173177'];
            $data['income_time'] = ['value' => date('Y-m-d H:i'), 'color' => '#173177'];
            $data['remark'] = ['value' => $list['p_name'] . '收益到账啦', 'color' => '#173177'];
            PublicController::sendTempMsg($temp, $pre_member->openid, $data, $url);
        }
        if ($pre_member2->is_open && $pre_commission2 != 0) {
            $title = "收益到账";
            $content = "{$list['p_name']}收益到账啦。收益金额：{$pre_commission2}元；到账时间：" . date('Y-m-d H:i');
            mBaseController::writeNotice($pre_member2->id, $title, $content);

            $url = Yii::$app->request->getHostInfo() . '/index.php?r=member%2Faccount';
            $data['first'] = ['value' => '您有一个收益到账通知', 'color' => '#173177'];
            $data['income_amount'] = ['value' => $pre_commission2, 'color' => '#173177'];
            $data['income_time'] = ['value' => date('Y-m-d H:i'), 'color' => '#173177'];
            $data['remark'] = ['value' => $list['p_name'] . '收益到账啦', 'color' => '#173177'];
            PublicController::sendTempMsg($temp, $pre_member2->openid, $data, $url);
        }
        if ($pre_member3->is_open && $pre_commission3 != 0) {
            $title = "收益到账";
            $content = "{$list['p_name']}收益到账啦。收益金额：{$pre_commission3}元；到账时间：" . date('Y-m-d H:i');
            mBaseController::writeNotice($pre_member3->id, $title, $content);

            $url = Yii::$app->request->getHostInfo() . '/index.php?r=member%2Faccount';
            $data['first'] = ['value' => '您有一个收益到账通知', 'color' => '#173177'];
            $data['income_amount'] = ['value' => $pre_commission3, 'color' => '#173177'];
            $data['income_time'] = ['value' => date('Y-m-d H:i'), 'color' => '#173177'];
            $data['remark'] = ['value' => $list['p_name'] . '收益到账啦', 'color' => '#173177'];
            PublicController::sendTempMsg($temp, $pre_member3->openid, $data, $url);
        }
        /***************推送模板消息*******************/
    }


}
