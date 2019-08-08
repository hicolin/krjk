<?php
/**
 * User: Colin
 * Time: 2018/11/15 17:57
 */

namespace backend\controllers;

use backend\models\AdminAward;
use backend\models\AdminDaiRecord;
use backend\models\AdminMember;
use yii\helpers\VarDumper;
use yii\web\Controller;
use mobile\controllers\PublicController as mPublicController;
use mobile\controllers\BaseController as mBaseController;
use common\controllers\PublicController;
use Yii;

class CrontabController extends Controller
{
    // 处理贷款记录
    public function actionDealDaiRecord()
    {
        $day_3_ago = time() - 3600 * 24 * 3; // 3天前
        $attributes = ['match_num' => 2];
        $condition = "match_num = 0 and created_time < {$day_3_ago}";
        $res = AdminDaiRecord::updateAll($attributes, $condition);
        if ($res || $res === 0) {
            return $res;
        } else {
            return 'fail';
        }
    }

    // 处理合伙人订单奖励
    // *** 此方法不可随意访问，切记！***
    public function actionDealOrderAward()
    {
        $hour = date('H');
        if ($hour > 1){
            echo '不在执行时间范围内';exit;
        }
        echo 'test';
        exit;
        $partners = AdminMember::find()->where(['is_partner' => 2])->andWhere(['is_block' => 1])->asArray()->all();
        $now = time();
        $b_day = strtotime(date('Y-m-d') . ' 00:00:00');
        // 本周的开始时间
        $b_week = $b_day - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600;
        $cpaPrice = trim(PublicController::getSysInfo(37));
        $cpsPrice = trim(PublicController::getSysInfo(38));
        foreach ($partners as $partner) {
            $userId = $partner['id'];
//            $userId = 6566;
            $member = AdminMember::findOne($userId);
            $members = AdminMember::findAll(['bei_invitation' => $member->invitation]);
            $ids = mPublicController::getSonIds($members);
            array_unshift($ids, $userId);  // 自身id添加进数组
            $allWeekCpaOrdersNum = AdminDaiRecord::find()->joinWith('product')->where(['in', 'tid', $ids])
                ->andWhere(['match_num' => 1])
                ->andWhere(['between', 'created_time', $b_week, $now])
                ->andWhere(['admin_dai_product.fy_type' => 1])
                ->count();
            $allWeekCpsOrdersNum = AdminDaiRecord::find()->joinWith('product')->where(['in', 'tid', $ids])
                ->andWhere(['match_num' => 1])
                ->andWhere(['between', 'created_time', $b_week, $now])
                ->andWhere(['admin_dai_product.fy_type' => 2])
                ->count();
            if ($allWeekCpaOrdersNum > 0) {
                $trans = Yii::$app->db->beginTransaction();
                try{
                    $this->award($userId, $allWeekCpaOrdersNum, $cpaPrice, true);
                    $trans->commit();
                }catch (\Exception $e) {
                    $trans->rollBack();
                    echo $e->getMessage();
                }
            }
            if ($allWeekCpsOrdersNum > 0) {
                $trans = Yii::$app->db->beginTransaction();
                try{
                    $this->award($userId, $allWeekCpsOrdersNum, $cpsPrice, false);
                    $trans->commit();
                }catch (\Exception $e) {
                    $trans->rollBack();
                    echo $e->getMessage();
                }
            }
        }
        echo '处理完成';
    }

    /**
     * 发放奖励
     * @param $userId
     * @param $num
     * @param $price
     * @param bool $isCpa
     */
    public function award($userId, $num, $price, $isCpa = true)
    {
        // 奖励表
        $member = AdminMember::findOne($userId);
        $award = new AdminAward();
        $money = $num * $price;
        if ($isCpa) {
            $prefix = 'CPA';
        } else {
            $prefix = 'CPS';
        }
        $remark = $prefix . '产品奖励（' . $num . ' * ' . $price . '）';
        $award->add($userId, $money, $remark);
        // 用户表
        $member->available_money += $money;
        $member->save(false);
        // 私信表
        $time = date('Y-m-d H:i');
        $title = '奖励到账';
        $content = "奖励到账: {$money}元; 备注：{$remark}; 时间：{$time}";
        mBaseController::writeNotice($userId, $title, $content);
    }

    /**
     * 调试函数
     * @param $param
     */
    public static function dd($param)
    {
        VarDumper::dump($param, 10, true);
        exit;
    }
}