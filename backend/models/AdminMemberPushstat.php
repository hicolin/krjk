<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_member_pushstat". 
 */

class AdminMemberPushstat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_member_pushstat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['openid'], 'required',],
            [['day','num'],'safe'],  
        ];
    } 

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => 'openid',
            'day' => '天',
            'num' => '已发送次数',
        ];
    }
}
