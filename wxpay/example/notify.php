<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once(dirname(__FILE__)."/../lib/WxPay.Api.php");
require_once(dirname(__FILE__)."/../lib/WxPay.Notify.php");
require_once(dirname(__FILE__)."/log.php");

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		Log::DEBUG("call back:" . json_encode($data));
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		return true;
	}
}

Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
$postXml = $GLOBALS["HTTP_RAW_POST_DATA"];
$postArr = xmlToArray($postXml);
//执行处理订单操作
$r = $notify->Queryorder($postArr['transaction_id']);
if($r){
	$order_sn=$postArr['out_trade_no'];
    $file= "abcd.txt";
    file_put_contents($file, $order_sn);
	$con = mysqli_connect('127.0.0.1', 'kaxsd_com', 'CmD5G7YQrFyGExfw','kaxsd_com');
	if (!$con)
	{
		die("连接错误: " . mysqli_connect_error());
	}
	$sql = "select * from admin_buy_agent where order_sn='".$order_sn."'";
    $result=$con->query($sql);
    $row=mysqli_fetch_array($result);
    if($row){

        $sql1="update admin_buy_agent set status=1 where order_sn='$order_sn'";
        $result=$con->query($sql1);
        $user_id = $row['user_id'];
        $sql2 = "update admin_member set grade={$row['grade_id']} where id=$user_id";
        $result=$con->query($sql2);

//        $sql1="update admin_buy_agent set status=1 where order_sn='$order_sn'";
//        $result=$con->query($sql1);
//        $user_id = $row['user_id'];
        // if($result){
        // 	header('Location: http://wx.dainishuang.com/index.php?r=pay%2Falipay'.'&order_id='.$order_sn);
        // }
        //$sql2 = "update admin_member set agent=1 where id=$user_id";
        //$result=$con->query($sql2);
    }
}
function xmlToArray($xml)
{
	//将XML转为array
	$array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
	return $array_data;
}
