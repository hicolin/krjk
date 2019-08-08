<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%hand_card}}".
 *
 * @property integer $id
 * @property string $img
 * @property string $name
 * @property string $remark
 * @property string $links
 * @property string $create_time
 */
class AdminHandCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%hand_card}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['img', 'remark', 'links','permission'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 8],
            [['grade'], 'integer'],
            [['create_time'], 'string', 'max' => 11],
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
            'img' => '图片',
            'name' => '名称',
            'remark' => '备注信息',
            'links' => '跳转链接',
            'permission' => '代理商是否可见',
            'create_time' => 'Create Time',
            'grade' =>'会员权限',
        ];
    }
}
