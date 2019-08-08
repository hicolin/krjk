<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_loan_report".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $tel
 * @property string $product
 * @property string $agent_tel
 * @property string $apply_time
 * @property string $loan_pic
 * @property string $user_center_pic
 * @property integer $status
 * @property integer $create_time
 */
class AdminLoanReport extends BaseModel
{
    public static $statusArr = [1 => '待审核', 2 =>'审核通过', 3 => '审核未通过'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_loan_report';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'create_time', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['tel', 'agent_tel'], 'string', 'max' => 15],
            [['product'], 'string', 'max' => 100],
            [['apply_time'], 'string', 'max' => 30],
            [['loan_pic', 'user_center_pic'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户ID',
            'name' => '客户姓名',
            'tel' => '客户电话',
            'product' => '产品名称',
            'agent_tel' => '代理电话',
            'apply_time' => '申请时间',
            'loan_pic' => '下款页面截图',
            'user_center_pic' => '下款用户中心截图',
            'status' => '状态',
            'create_time' => '创建时间',
        ];
    }

    // 关联用户表
    public function getMember()
    {
        return $this->hasOne(AdminMember::className(), ['id' => 'user_id']);
    }

    /**
     * 添加（前台）
     * @param $data
     * @param $userId
     * @return array
     */
    public function create($data, $userId)
    {
        $this->user_id = $userId;
        $this->name = $data['name'];
        $this->tel = $data['tel'];
        $this->product = $data['product'];
        $this->agent_tel = $data['agent_tel'];
        $this->apply_time = $data['apply_time'];
        $this->loan_pic = $data['loan_pic'];
        $this->user_center_pic = $data['user_center_pic'];
        $this->status = 1;
        $this->create_time = time();
        if (!$this->save()) {
            $error = array_values($this->getFirstErrors())[0];
            return $this->arrData(100, $error);
        }
        return $this->arrData(200, '添加成功');
    }


}
