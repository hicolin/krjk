<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_promo_code".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $p_id
 * @property string $promo_code
 * @property string $promo_url
 * @property string $money
 * @property integer $status
 * @property integer $created_time
 */
class AdminPromoCode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_promo_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'p_id', 'status', 'created_time'], 'integer'],
            [['money'], 'number'],
            [['promo_code'], 'string', 'max' => 20],
            [['promo_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户',
            'p_id' => '产品',
            'promo_code' => '推广码',
            'promo_url' => '推广链接',
            'money' => '所得总佣金',
            'status' => '状态',
            'created_time' => '创建时间',
        ];
    }

    /**
     * 会员表
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(AdminMember::className(),['id'=>'user_id']);
    }

    /**
     * 产品表
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(AdminDaiProduct::className(),['id'=>'p_id']);
    }
}
