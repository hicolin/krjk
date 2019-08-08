<?php

namespace backend\models;

/**
 * This is the model class for table "admin_notice".
 */
class AdminNotice extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_notice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'content', 'create_time'], 'required'],
            [['user_id', 'create_time'], 'integer'],
            [['content'], 'string', 'max' => 500]
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
            'content' => 'Content',
            'create_time' => 'Create Time',
        ];
    }

    // 关联用户表
    public function getMember()
    {
        return $this->hasOne(AdminMember::className(),['id'=>'user_id']);
    }
}