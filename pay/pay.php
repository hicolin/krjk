<?php

	header("Content-type:text/html;charset=utf-8");

	// echo "<pre>";
	// print_r($_POST);
	// die();

	include_once("config.php");

	//以下参数，只需传递POST参数订单号：orderId，订单金额：orderAmt，系统用户唯一ID：uid，其他参数读取config或默认值
	$_POST['uid'] =1;
	$P_SysUserId=$_POST['uid'];
	// $UserId =10472;
	$P_UserId=10472;//商户ID
	$P_CardId="商户";//不用传
	$P_CardPass=2;//不用传
	$_POST['orderAmt'] = 0.01;
	$P_FaceValue=$_POST['orderAmt'];//金额，必须传
	$P_ChannelId=2;//不用传
	$P_Subject="商户平台充值";//不用传
	$P_Price=1;//不用传
	$P_Quantity=2;//不用传
	$P_Description="商户平台充值";//不用传
	$P_Notic="商户";//不用传
	$P_Result_url='http://daikfx.cn?r=pay%notify';//config里面，必传用于返回系统更新账单链接，携带GET参数账单号：Sjt_TransID，金额：Sjt_factMoney
	$P_Notify_url='http://daikfx.cn?r=pay%2Fpay';//config里面
	$_POST['orderId'] = 52751120180403175;
	$P_OrderId=$_POST['orderId'];//生成订单号，必传
	// echo "22";exit
;
	$preEncodeStr=$P_UserId."|".$P_OrderId."|".$P_CardId."|".$P_CardPass."|".$P_FaceValue."|".$P_ChannelId."|".$SalfStr;

	$P_PostKey=md5($preEncodeStr);



	$params="P_UserId=".$P_UserId;
	$params.="&P_OrderId=".$P_OrderId;
	$params.="&P_CardId=".$P_CardId;
	$params.="&P_CardPass=".$P_CardPass;
	$params.="&P_FaceValue=".$P_FaceValue;
	$params.="&P_ChannelId=".$P_ChannelId;
	$params.="&P_Subject=".$P_Subject;
	$params.="&P_Price=".$P_Price;
	$params.="&P_Quantity=".$P_Quantity;
	$params.="&P_Description=".$P_Description;
	$params.="&P_Notic=".$P_Notic;
	$params.="&P_Result_url=".$P_Result_url;
	$params.="&P_Notify_url=".$P_Notify_url;
	$params.="&P_PostKey=".$P_PostKey."&uid=asys".$P_UserId.$P_SysUserId;


	//在这里对订单进行入库保存

	//下面这句是提交到API
	header("location:$gateWary?$params");
	// $sHtml.= "<script>window.location.href='$gateWary?$params';</script>";
	// echo $sHtml;
	//

?>