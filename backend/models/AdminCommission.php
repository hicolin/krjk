<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_commission".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $jy_user_id
 * @property integer $type
 * @property string $money
 * @property string $commission_money
 * @property integer $created_time
 * @property string $msg
 * @property string $openid
 * @property string $jy_openid
 * @property string $p_name
 */
class AdminCommission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_commission';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'jy_user_id', 'type', 'created_time'], 'integer'],
            [['money', 'commission_money'], 'number'],
            [['msg'], 'string', 'max' => 255],
            [['p_name'], 'string', 'max' => 25],
            [['openid', 'jy_openid'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'jy_user_id' => 'Jy User ID',
            'type' => 'Type',
            'money' => 'Money',
            'created_time' => 'Created Time',
            'msg' => 'Msg',
            'openid' => 'Openid',
            'jy_openid' => 'Jy Openid',
            'commission_money' => '返佣金额',
            'p_name' => '产品名称',
        ];
    }

    public function getMember()
    {
        return $this->hasOne(AdminMember::className(),['id'=>'jy_user_id']);
    }
}
