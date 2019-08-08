<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_setting".
 *
 * @property integer $id
 * @property string $key
 * @property string $val
 * @property integer $type
 */
class AdminSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'integer'],
            [['key'], 'string', 'max' => 50],
            [['val'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'val' => 'Val',
            'type' => 'Type',
        ];
    }
}
