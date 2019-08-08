<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_category".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property string $create_time
 */
class AdminCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '分类名称',
            'parent_id' => '分类ID',
            'create_time' => '创建时间',

        ];
    }
}
