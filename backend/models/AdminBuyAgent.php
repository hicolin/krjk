<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_buy_agent".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $money
 * @property integer $created_time
 * @property integer $status
 * @property string $order_sn
 */
class AdminBuyAgent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_buy_agent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created_time','status'], 'integer'],
            [['money'], 'number'],
            [['order_sn'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '昵称',
            'money' => '金额',
            'created_time' => '购买时间',
            'status' => '状态',
            'order_sn' => '订单号',
        ];
    }

    /**
     *member
     */
    public function getMember()
    {
        return $this->hasOne(AdminMember::className(),['id'=>'user_id']);
    }
    /*
    支付方式
    */
    public function getPaytype()
    {
        return $this->hasOne(AdminPay::className(),['id'=>'type']);
    }

    public function getGrade()
    {
        return $this->hasOne(AdminGrade::className(),['id'=>'grade_id']);
    }
}
