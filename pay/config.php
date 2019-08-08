<?php

	error_reporting(0);
	header("Content-type:text/html;charset=utf-8");
	date_default_timezone_set(PRC);
	//平台商户ID，需要更换成自己的商户ID
	//接入商户ID
	$UserId='10472';
	//接口密钥，需要更换成你自己的密钥，要跟后台设置的一致
	$SalfStr='1647fb77e855759d2378f2215ab030b1';
	//网关地址
	$gateWary="http://zf.hr76.com/Payapi_Index_Pay.html";
	//配置你自己的充值结果后台通知地址，必须传，更新订单的到账状态
	$result_url="http://daikfx.cn?r=pay%2Fpay";

	//充值结果用户在网站上的转向地址，不用传
	$notify_url="http://daikfx.cn?r=pay%notify";
?>