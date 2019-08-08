<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%pay}}".
 *
 * @property integer $id
 * @property integer $pay_name
 * @property integer $is_open
 * @property string $pay_site
 * @property integer $created_time
 */
class AdminPay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pay}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_open', 'created_time'], 'integer'],
            [['pay_site','pay_name'], 'string', 'max' => 255]
        ];
    }
    
    public static function dropDownList($column,$value=null)
    {
       
        $dropDownList=[
            'type'=>[1=>'官方',2=>'个人'],
            'status'=>[1=>'通&emsp;过',0=>'不通过',-1=>'未审核'],
            'is_open'=>[1=>'是',0=>'否'],
            'recommend'=>[1=>'是',0=>'否']

        ];
        if ($value !== null)
            return array_key_exists($column, $dropDownList) ? $dropDownList[$column][$value] : false;

        else
            return array_key_exists($column, $dropDownList) ? $dropDownList[$column] : false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pay_name' => '支付名称',
            'is_open' => '是否开启',
            'pay_site' => '配置参数',
            'created_time' =>'创建时间',
        ];
    }
}
