<?php
/**
 * User: Colin
 * Time: 2018/11/16 13:48
 */

namespace backend\models;


/**
 * This is the model class for table "admin_product_category".
 *
 * @property string $id
 * @property string $name
 * @property integer $sort
 * @property integer $create_time
 */
class AdminProductCategory extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_product_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'create_time'], 'required'],
            [['sort', 'create_time'], 'integer'],
            [['name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'sort' => 'Sort',
            'create_time' => 'Create Time',
        ];
    }
}