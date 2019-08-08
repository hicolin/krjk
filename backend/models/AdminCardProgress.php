<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%card_progress}}".
 *
 * @property integer $id
 * @property string $img
 * @property string $name
 * @property string $links
 * @property integer $type
 * @property string $create_tim
 */
class AdminCardProgress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%card_progress}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type','grade'], 'integer'],
            [['img', 'links','permission'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 8],
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

    // public static function dropDownList($column,$value=null)
    // {
    //     $dropDownList=[
    //         'type'=>['1'=>'是是是','2'=>'号好好好好哦啊'],
           
    //     ];
    //     if ($value !== null)
    //         return array_key_exists($column, $dropDownList) ? $dropDownList[$column][$value] : false;

    //     else
    //         return array_key_exists($column, $dropDownList) ? $dropDownList[$column] : false;
    // }
    


    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'img' => '图片',
            'name' => '名称',
            'links' => '跳转链接',
            'type' => '类型',
            'permission'=>'代理商是否可见',
            'create_time' => 'Create Time',
            'grade' =>'会员权限',
        ];
    }
}
