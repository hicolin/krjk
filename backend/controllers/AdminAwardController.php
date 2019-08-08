<?php
/**
 * User: Colin
 * Time: 2018/11/16 13:52
 */

namespace backend\controllers;

use backend\models\AdminAward;
use backend\models\AdminMember;
use Yii;
use yii\data\Pagination;
use yii\db\Exception;
use mobile\controllers\BaseController as mBaseController;

class AdminAwardController extends BaseController
{
    public $layout = "lte_main";

    // 列表
    public function actionIndex()
    {
        $query = AdminAward::find()->joinWith('member');
        $querys = Yii::$app->request->get('query');
        if ($querys['tel']) {
            $query = $query->andWhere(['like', 'admin_member.tel', $querys['tel']]);
        }
        $pageSize = (int)abs(\common\controllers\PublicController::getSysInfo(36));
        $pagination = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => $pageSize,
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page']
        );
        $awards = $query
            ->orderby('admin_award.create_time desc')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()->all();
        return $this->render('index', [
            'model'=>$awards,
            'pages' => $pagination,
            'query'=>$querys,
        ]);
    }

    // 添加
    public function actionCreate()
    {
        if(Yii::$app->request->isAjax){
            $post = Yii::$app->request->post();
            $model = new AdminAward();
            $model->name = $post['name'];
            $model->sort = (int)$post['sort'];
            $model->create_time = time();
            if($model->save(false)){
                return $this->json(200,'添加成功');
            }
            return $this->json(100,'添加失败');
        }
    }

    // 获取用户信息
    public function actionGetUserInfo()
    {
        if(Yii::$app->request->isAjax){
            $tel = Yii::$app->request->get('tel');
            $member = AdminMember::find()->where(['tel'=>$tel])->asArray()->one();
            if(!$member){
                return $this->json(100,'用户不存在');
            }
            return $this->jsonData(200,$member);
        }
    }

    // 发放奖励
    public function actionAward()
    {
        if(Yii::$app->request->isAjax){
            $userId = Yii::$app->request->get('userId');
            $money = Yii::$app->request->get('money');
            $remark = Yii::$app->request->get('remark');
            $trans = Yii::$app->db->beginTransaction();
            try{
                // 奖励表
                $member = AdminMember::findOne($userId);
                $award = new AdminAward();
                $award->add($userId,$money,$remark);
                // 用户表
                $member->available_money += $money;
                $member->save(false);
                // 私信表
                $now = date('Y-m-d H:i');
                $title = '奖励到账';
                $content = "奖励金额：{$money}元; 备注：{$remark}; 时间：{$now}";
                mBaseController::writeNotice($userId,$title,$content);
                $trans->commit();
                return $this->json(200,'发放成功');
            }catch (\Exception $e){
                $trans->rollBack();
                return $this->json(100,'发放失败');
            }
        }
    }
    
    // 删除
    public function actionDelrecord()
    {
        if(Yii::$app->request->isAjax){
            $ids = Yii::$app->request->get('ids');
            $res = AdminAward::deleteAll(['in','id',$ids]);
            if($res){
                return $this->json(200,'删除成功');
            }
            return $this->json(100,'删除失败');
        }
    }


}