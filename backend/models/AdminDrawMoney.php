<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_draw_money".
 *
 * @property integer $id
 * @property string $pic
 * @property string $name
 * @property string $url
 * @property integer $permission
 * @property integer $type
 * @property integer $created_time
 */
class AdminDrawMoney extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_draw_money';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['permission', 'type', 'created_time','grade'], 'integer'],
            [['pic'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 20],
            [['url'], 'string', 'max' => 255],
        ];
    }

        public static function dropDownList($column,$value=null)
    {
        $dropDownList=[
            'permission'=>['1'=>'可见','0'=>'不可见'],
           
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
            'pic' => '图标',
            'name' => '名称',
            'url' => '链接地址',
            'permission' => '是否开启代理商可见',
            'type' => '类型',
            'created_time' => '申请时间',
            'grade' =>'会员权限',
        ];
    }
}
