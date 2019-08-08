<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_member".
 *
 * @property integer $id
 * @property string $nickname
 * @property string $real_name
 * @property string $passwd
 * @property string $pic
 * @property string $openid
 * @property string $unionid
 * @property string $subscribe
 * @property string $tel
 * @property integer $is_open
 * @property integer $agent
 * @property string $promotion_commission
 * @property string $dai_commission
 * @property string $available_money
 * @property string $invitation
 * @property string $bei_invitation
 * @property integer $login_time
 * @property integer $created_time
 * @property integer $account_number
 * @property integer $account_name
 */
class AdminMember extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agent', 'login_time', 'created_time', 'update_time', 'subscribe','mp_push_daynum','grade','is_partner'], 'integer'],
            [['promotion_commission', 'dai_commission', 'available_money'], 'number'],
            [['nickname', 'real_name'], 'string', 'max' => 30],
            [['account_number', 'account_name'], 'string', 'max' => 50],
            [['passwd'], 'string', 'max' => 100],
            [['openid', 'unionid'], 'string', 'max' => 100],
            [['pic'], 'string', 'max' => 200],
            [['tel'], 'string', 'max' => 11],
            [['invitation', 'bei_invitation'], 'string', 'max' => 16],
        ];
    }
    public function getGrades()
    {
        return $this->hasOne(AdminGrade::className(),['id'=>'grade']);
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nickname' => '昵称',
            'grade' => '会员等级',
            'real_name' => '真实姓名',
            'passwd' => '密码',
            'pic' => '头像',
            'openid' => 'Openid',
            'unionid'=> 'unionid',
            'tel' => '手机号码',
            'agent' => '代理身份',
            'promotion_commission' => '代理推广佣金',
            'dai_commission' => '代呗推广佣金',
            'available_money' => '可提现金额',
            'invitation' => '邀请码',
            'bei_invitation' => '被邀请码',
            'login_time' => '上次登录时间',
            'created_time' => '注册时间',
            'update_time' => '更新时间',
            'account_number' => '收款账户',
            'account_name' => '账户姓名',
            'mp_push_daynum'=>'每日公号推送次数',
            'is_block'=>'状态',
        ];
    }
}
