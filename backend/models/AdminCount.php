<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_count".
 *
 * @property integer $id
 * @property string $tel
 * @property string $name
 * @property string $p_name
 * @property string $money
 * @property string $commission_money
 * @property integer $apply_rate
 * @property string $turn_reason
 * @property string $is_match
 * @property integer $apply_time
 * @property integer $loan_time
 * @property integer $turn_time
 * @property integer $created_time
 * @property integer $order_sn
 * @property integer $commission_rate
 */
class AdminCount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_count';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['money', 'commission_money'], 'number'],
            [['apply_rate', 'apply_time', 'loan_time', 'turn_time', 'created_time', 'is_match', 'commission_rate'], 'integer'],
            [['tel'], 'string', 'max' => 11],
            [['name'], 'string', 'max' => 10],
            [['order_sn'], 'string', 'max' => 40],
            [['p_name', 'turn_reason'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tel' => '手机号码',
            'name' => '姓名',
            'p_name' => '产品名称',
            'money' => '返佣金额',
            'commission_money' => '返佣金额', //z暂时不用
            'apply_rate' => '申请进度',
            'turn_reason' => '驳回原因',
            'is_match' => '是否匹配',
            'apply_time' => '申请时间',
            'loan_time' => '放款时间',
            'turn_time' => '驳回时间',
            'created_time' => '导入时间',
            'order_sn' => '唯一单号',
            'commission_rate' => '返佣比例',
        ];
    }

    public function getMember()
    {
        return $this->hasOne(AdminMember::className(),['tel'=>'tel']);
    }
}
