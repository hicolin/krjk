<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%commemts}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $content
 * @property string $tocontent
 * @property integer $status
 * @property integer $recommend
 * recommends
 * @property string $create_time
 */
class AdminCommemts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%commemts}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status','recommend','grade_id'], 'integer'],
            [['content', 'tocontent'], 'string'],
            [['create_time'], 'string', 'max' => 11],
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
            'content' => '评论内容',
            /*'tocontent' => '回复内容',*/
            'grade_id'=>'会员等级',
            'status' => '审核状态',
            'recommend' => '是否推荐',
            'create_time' => '评论时间',
        ];
    }

    /**
     * 会员信息
     */
    public function getMember()
    {
        return $this->hasOne(AdminMember::className(),['id'=>'user_id']);
    }
    /*
        会员等级
    */
    public function getGrade()
    {
        return $this->hasOne(AdminGrade::className(),['id'=>'grade_id']);
    }

}
