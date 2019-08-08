<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_bank_card".
 *
 * @property integer $id
 * @property string $logo
 * @property string $title
 * @property string $price
 * @property string $beizhu
 * @property string $rate
 * @property string $interest
 * @property string $hk_way
 * @property string $range
 * @property string $time_limit
 * @property string $flow
 * @property string $condition
 * @property string $attention
 * @property integer $create_time
 * @property integer $type
 * @property integer $recommend
 */
class AdminBankCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_bank_card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'title'], 'required'],
            [['id', 'create_time','type'], 'integer'],
            [['price', 'rate', 'interest'], 'number'],
            [['time_limit','permission','links'], 'safe'],
            [['title', 'hk_way', 'range', 'flow'], 'string', 'max' => 255],
            [['beizhu','logo'], 'string', 'max' => 100],
            [['recommend'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'logo' => 'logo',
            'title' => '标题',
            'price' => '金额',
            'beizhu' => '备注',
            'rate' => '通过率',
            'interest' => '最低月利率',
            'hk_way' => '还款方式',
            'range' => '贷款范围',
            'time_limit' => '还款期限',
            'flow' => '申请流程',
            'condition' => '申请条件',
            'attention' => '注意事项',
            'create_time' => '创建时间',
            'create_time' => '类别',
            'recommend' => '是否推荐',
            'permission' =>'代理商是否可见',
            'links' =>'跳转链接'
        ];
    }
}
