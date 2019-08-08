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
class AdminGrage extends \yii\db\ActiveRecord
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
            [['created_time'], 'integer'],
            [['grade_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'grade_name' => 'Grade Name',
            'created_time' => 'Created Time',
        ];
    }
}
