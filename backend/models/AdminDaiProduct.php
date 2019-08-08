<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_dai_product".
 *
 * @property integer $id
 * @property string $logo
 * @property string $title
 * @property string $fy_info
 * @property string $title_info
 * @property string $detail
 * @property string $pic
 * @property string $join_pic
 * @property string $sub_pic
 * @property integer $type
 */
class AdminDaiProduct extends \yii\db\ActiveRecord
{
    public static $style = [1 => '大额产品', 2 => '最新口子'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_dai_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['detail','apply_detail'], 'string'],
            [['type','grade','fy_type','style','cate_id'], 'integer'],
            [['time_limit','rate','interest','hk_way','range','links','apply_detail'], 'safe'],
            [['logo', 'pic', 'join_pic', 'sub_pic'], 'string', 'max' => 100],
            [['title', 'fy_info','js_method', 'title_info','share_info','fy_one','fy_two',
                'fy_three', 'label_one', 'label_two'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'logo' => 'Logo',
            'title' => '标题',
            'fy_info' => '返佣描述',
          	'js_method' => '结算方式',
            'fy_type'=>'返佣模式',
            'title_info' => '小标题',
            'label_one' => '标签一',
            'label_two' => '标签二',
            'detail' => '详情',
            'pic' => '背景图片',
            'grade' =>'会员权限',
            'join_pic' => '推广背景图片',
            'sub_pic' => '提交背景图片',
            'share_pic' =>'一键分享banner',
            'share_info'=>'一键分享标题',
            'type' => '类型',
            'rate' => '通过率',
            'interest' => '最低月利率',
            'hk_way' => '还款方式',
            'range' => '贷款范围',
            'time_limit' => '还款期限',
            'links' =>'跳转链接', 
            'apply_detail' =>'申请详情'           
        ];
    }

    public function getAgent()
    {
        return $this->hasMany(AdminAgentMessage::className(),['p_id'=>'id']);
    }

    // 关联产品分类表
    public function getCategory()
    {
        return $this->hasOne(AdminProductCategory::className(),['id'=>'cate_id']);
    }
}
