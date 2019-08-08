<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%key_enter}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $sex
 * @property string $mobile
 * @property integer $province
 * @property integer $city
 * @property integer $area
 * @property string $address
 * @property string $content
 * @property integer $created_time
 */
class AdminKey extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%key_enter}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sex', 'province', 'city', 'area', 'created_time'], 'integer'],
            [['name', 'mobile'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 100],
            [['content'], 'string', 'max' => 225]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '姓名',
            'sex' => '性别',
            'mobile' => '手机号',
            'province' => 'Province',
            'city' => 'City',
            'area' => 'Area',
            'address' => 'Address',
            'content' => '留言内容',
            'created_time' => '创建时间',
        ];
    }
}
