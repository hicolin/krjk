<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%goods}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $price
 * @property integer $sold_num
 * @property string $detail
 * @property string $info
 * @property string $create_time
 */
class AdminGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','sold_num','price','create_time'],'required'],
            [['sold_num'], 'integer'],
            [['detail','info'], 'string'],
            [['title'],'string', 'max' => 255],
            [['price'], 'number'],
            [['create_time','grade_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'grade_id'=>'会员等级',
            'title' => '标题',
            'price' => '价格',
            'sold_num' => '已售数量',
            'detail' => '详细介绍',
            'info' => '购买须知',
            'pic' =>'banner图片',
            'bei_pic'=>'背景图片',
            'create_time' => '创建时间',
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
