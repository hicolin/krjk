<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%region}}".
 *
 * @property string $id
 * @property string $name
 * @property integer $level
 * @property integer $parent_id
 */
class AdminRegiones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%regions}}';
    }

    public static function getProvince()
    {
        $list=self::find()->where(array('level'=>1,'parent_id'=>0))->all();
        return $list;
    }


    public static function getRegion($id)
    {
        $list=self::find()->where(array('parent_id'=>$id))->all();
        return $list;
    }

    public static function getRegionName($id)
    {
        $result=self::find()->where(array('id'=>$id))->one();
        return $result->name;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['region_name','parent_id','region_id','region_type','agency_id'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'region_name' => 'region_name',
            'region_id' => 'region_id',
            'parent_id' => 'Parent ID',
        ];
    }
}
