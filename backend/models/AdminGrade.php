<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%grade}}".
 *
 * @property integer $id
 * @property string $grade_name
 * @property integer $created_time
 */
class AdminGrade extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%grade}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grade_name','price'], 'string', 'max' => 100],
            
            [['created_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'grade_name' => '会员等级',
            'created_time' => '创建时间',
            'price' =>'会员价格',
        ];
    }


    public function createDir($str)
    {
        $arr = explode('/', $str);
        if(!empty($arr))
        {
            $path = '';
            foreach($arr as $k=>$v)
            {
                $path .= $v.'/';
                if (!file_exists($path)) {
                    mkdir($path, 0777);
                    chmod($path, 0777);
                }
            }
        }
    }
}
