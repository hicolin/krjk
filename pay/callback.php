
<?php
/**
	异步更新账单状态
**/  

		//回调匹配密钥
		$keystr="1647fb77e855759d2378f2215ab030b1";//商户密钥
		//异步成功通知返回的resquest域参数，只需要根据参数账单ID：Sjt_TransID，金额：Sjt_factMoney传入更新账单成功状态
		$md5keysign= md5($_REQUEST["Sjt_MerchantID"]. $_REQUEST["Sjt_Username"] . $_REQUEST["Sjt_TransID"]  . $_REQUEST["Sjt_Return"] . $_REQUEST["Sjt_Error"] . $_REQUEST["Sjt_factMoney"]  . $_REQUEST["Sjt_SuccTime"] . $_REQUEST["Sjt_BType"] . $keystr);
		
		
		/*
if(!empty($_REQUEST["Sjt_SuccTime"])&&!empty($_REQUEST["Sjt_Sign"])){

				$this->notify_ok_dopay($_REQUEST['Sjt_TransID'],$_REQUEST['Sjt_factMoney']);

			}
*/
	//回调验证签名，匹配支付成功
     if($md5keysign==$_REQUEST["Sjt_Sign"]){
	      //在这里写处理账单成功逻辑
	     echo "ok";
     }
     //数据被改
     else{
	      echo "验签失败";
     }
	

?>