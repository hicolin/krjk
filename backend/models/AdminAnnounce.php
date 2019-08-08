<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_article".
 *
 * @property integer $art_id
 * @property string $title
 * @property string $img
 * @property integer $permission
 * @property integer $is_recom
 * @property string $detail
 * @property string $source
 * @property integer $create_time
 */
class AdminAnnounce extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_announce';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required',],
            [['img','create_time','detail'],'safe'],
            [['title'], 'string', 'max' => 255],

        ];
    }

    public function upload()
    {
        if($this->validate()) {
            $this->img->saveAs('backend/web/plugins/uploads/'.$this->img->baseNames.'.'.$this->img->extension);
            return true;
        }else{
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'art_id' => 'ID',
            'title' => '标题',
            'detail' => '详细内容',
            'create_time' => '创建时间',
        ];
    }
}
