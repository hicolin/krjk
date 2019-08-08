<?php

namespace backend\models;


/**
 * This is the model class for table "admin_award".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $money
 * @property string $remark
 * @property integer $create_time
 */
class AdminAward extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_award';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'create_time'], 'required'],
            [['user_id', 'create_time'], 'integer'],
            [['money'], 'number'],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    // 关联用户表
    public function getMember()
    {
        return $this->hasOne(AdminMember::className(),['id'=>'user_id']);
    }

    /**
     * 添加
     * @param $userId
     * @param $money
     * @param $remark
     * @return bool
     */
    public function add($userId,$money,$remark)
    {
        $this->user_id = $userId;
        $this->money = $money;
        $this->remark = $remark;
        $this->create_time = time();
        if($this->save(false)){
            return true;
        }
        return false;
    }


}