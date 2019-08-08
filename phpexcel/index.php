<?php

require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';
require_once 'Classes/PHPExcel/Reader/Excel5.php';;
$file = $_GET['file'];

// file_put_contents('file123.txt',$file);
define("ROOT_NAME",dirname(__DIR__));
ini_set('date.timezone','Asia/Shanghai');
//$con = mysqli_connect("211.149.233.13","dkdr","A2s3y8F8");
//$con = mysqli_connect("localhost","root","123456");

/* Connect to a MySQL server  连接数据库服务器 */
$con = mysqli_connect('localhost', 'krjk_kaxsd_cn', 'drCiCcZBBE5X7Ajc','krjk_kaxsd_cn');
if (!$con) {
    printf("Can't connect to MySQL Server. Errorcode: %s ", mysqli_connect_error());
    exit;
}

mysqli_query($con,"set names utf8"); //数据库输出编码 应该与你的数据库编码保持一致.南昌网站建设公司百恒网络PHP工程师建议用UTF-8 国际标准编码.
//mysqli_select_db($con,"dkdr"); //打开数据库

$objReader=PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format
$objPHPExcel=$objReader->load(ROOT_NAME.$file);//$file_url即Excel文件的路径
//$objPHPExcel=$objReader->load(ROOT_NAME."/uploads/file/2017091516081.csv");//$file_url即Excel文件的路径
$sheet=$objPHPExcel->getSheet(0);//获取第一个工作表
$highestRow=$sheet->getHighestRow();//取得总行数
$highestColumn=$sheet->getHighestColumn(); //取得总列数
$times = time();
$arr = [];
//循环读取excel文件,读取一条,插入一条
$i = 0;
for($j=2;$j<=$highestRow;$j++){//从第一行开始读取数据
 $str='';
 for($k='A';$k<=$highestColumn;$k++){            //从A列读取数据
 //这种方法简单，但有不妥，以'\\'合并为数组，再分割\\为字段值插入到数据库,实测在excel中，如果某单元格的值包含了\\导入的数据会为空
     //$str.=$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue().'\\';//读取单元格
     /*if(!$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue()) {
         continue;
     }*/
     if(in_array($k,['H','I'])){//指定D列为时间所在列
         $str.= gmdate("Y-m-d H:i:s", PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue())).'\\';
     }else{
         $str.=$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue().'\\';//读取单元格
     }
 }
 //explode:函数把字符串分割为数组。
    $strs=explode("\\",$str);
    $apply_time = $strs[6]?strtotime($strs[6]):'';
    $loan_time = $strs[7]?strtotime($strs[7]):'';
    $turn_time = $strs[8]?strtotime($strs[8]):'';
    $type_tel = $strs[9]?$strs[9]:''; //1：前三后四 2 前三后三
    $order_sn = date('YmdHis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    $created_time = time();
    $arr[$i]['tel'] = $strs[0];
    $arr[$i]['name'] = $strs[1];
    $arr[$i]['p_name'] = $strs[2];
    $arr[$i]['money'] = $strs[3];
    /* $arr[$i]['commission_money'] = $strs[4];*/
    $arr[$i]['apply_rate'] = $strs[4];
    $arr[$i]['turn_reason'] = $strs[5];
    $arr[$i]['commission_rate'] = $strs[10];
    $arr[$i]['order_sn'] = $order_sn;
    $arr[$i]['type_tel'] = $type_tel;
    $sql="INSERT INTO `admin_count`(`tel`,`name`,`p_name`,`money`,`apply_rate`,`turn_reason`,`apply_time`,`loan_time`,`turn_time`,`created_time`,`order_sn`, `commission_rate`) VALUES (
                     '{$strs[0]}','{$strs[1]}','{$strs[2]}','{$strs[3]}','{$strs[4]}','{$strs[5]}','{$apply_time}','{$loan_time}','{$turn_time}','{$created_time}','{$order_sn}', '{$strs[10]}')";
    mysqli_query($con,$sql);//这里执行的是插入数据库操作
    $i++;
}
echo json_encode($arr);exit;
//unlink($file_url); //删除excel文件
?>
