<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_dai_list".
 *
 * @property integer $id
 * @property string $logo
 * @property string $tel
 * @property string $p_name
 * @property string $money
 * @property integer $status
 * @property integer $created_time
 */
class AdminDaiList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_dai_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['money'], 'number'],
            [['status', 'created_time'], 'integer'],
            [['logo'], 'string', 'max' => 100],
            [['tel','p_name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'logo' => 'Logo',
            'tel' => '手机号码',
            'p_name' => '产品名称',
            'money' => '金额',
            'status' => '状态',
            'created_time' => '时间',
        ];
    }
}
