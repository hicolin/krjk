<?php
namespace backend\models;

class BaseModel extends \yii\db\ActiveRecord
{
    /**
     * 返回数组信息
     * @param $status
     * @param $msg
     * @return array
     */
    public function arrData($status, $msg)
    {
        return ['status' => $status, 'msg' => $msg];
    }
   
}

?>