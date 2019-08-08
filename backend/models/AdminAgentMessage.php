<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%agent_message}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $p_id
 * @property string $promo_code
 * @property string $promo_url
 * @property string $money
 * @property integer $status
 * @property integer $type
 * @property integer $create_time
 */
class AdminAgentMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%agent_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'p_id', 'create_time', 'status', 'type'], 'integer'],
            [['promo_code'], 'string', 'max' => 30],
            [['promo_url'], 'string', 'max' => 255],
            [['money'], 'number'],
        ];
    }

    public function getMember(){

        return $this->hasOne(AdminMember::className(),['id'=>'user_id']);

    }

    public function getProduct(){
        return $this->hasOne(AdminDaiProduct::className(),['id'=>'p_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '代理人',
            'p_id' => '产品名称',
            'promo_code' => '推广码',
            'promo_url' => '推广链接',
            'money' => '总佣金',
            'status' => '状态',
            'create_time' => '创建时间',
        ];
    }

    public static function getData($id)
    {
        return self::findOne($id);
    }
}
