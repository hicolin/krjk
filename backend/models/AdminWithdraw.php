<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_withdraw".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $money
 * @property integer $status
 * @property integer $update_time
 * @property integer $created_time
 */
class AdminWithdraw extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_withdraw';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'update_time', 'created_time'], 'integer'],
            [['money'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户名',
            'money' => '金额',
            'status' => '状态',
            'update_time' => '更新时间',
            'created_time' => '申请时间',
        ];
    }

    /**
     *member
     */
    public function getMember()
    {
        return $this->hasOne(AdminMember::className(),['id'=>'user_id']);
    }
}
