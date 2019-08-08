<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_dai_record".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $tid
 * @property string $name
 * @property string $tel
 * @property string $id_card
 * @property string $ip
 * @property integer $type
 * @property integer $phone_system
 * @property integer $match_num
 * @property integer $update_time
 * @property integer $created_time
 * @property integer $match_time
 * @property string $api_data

 */
class AdminDaiRecord extends \yii\db\ActiveRecord
{
    public static $matchNumArr = [0 => '待匹配', 1 => '匹配成功', 2 => '已失效'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_dai_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'phone_system','pid', 'update_time', 'created_time', 'match_time', 'match_num', 'type'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['tel'], 'string', 'max' => 11],
            [['id_card'], 'string', 'max' => 18],
            [['ip'], 'string', 'max' => 50],
            [['api_data'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户名',
            'name' => '姓名',
            'tel' => '手机号码',
            'id_card' => '身份证号码',
            'ip' => 'IP地址',
            'type' => '匹配状态',
            'phone_system' => '设备系统',
            'match_num' => '匹配次数',
            'update_time' => '更新时间',
            'created_time' => '申请时间',
            'match_time' => '匹配时间',
            'tid' =>'推荐人',
            'pid' =>'产品名称',
            'api_data' =>'接口返回数据',
        ];
    }

    public function getMember()
    {
        return $this->hasOne(AdminMember::className(), ['id'=>'user_id']);
    }

    public function getMembers()
    {
        return $this->hasOne(AdminMember::className(), ['id'=>'tid']);
    }

    public function getProduct(){

        return $this->hasOne(AdminDaiProduct::className(),['id'=>'pid']);
    }

    public function getCount()
    {
        return $this->hasOne(AdminCount::className(),['tel'=>'tel']);
    }
}
