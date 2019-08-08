<?php
require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';
require_once 'Classes/PHPExcel/Reader/Excel5.php';
date_default_timezone_set('Asia/Shanghai');
ini_set('date.timezone','Asia/Shanghai');
$file = $_GET['file'];
define("ROOT_NAME",dirname(__DIR__));
//$con = mysqli_connect('211.149.233.13', 'daikfx', 'g7u6q8T2','daikfx');
$con=mysqli_connect('rm-2zef5p346l59b43mx741.mysql.rds.aliyuncs.com','daikfx','fs36fD056tTWMhm','daikfx');
if (!$con) {
    printf("Can't connect to MySQL Server. Errorcode: %s ", mysqli_connect_error());
    exit;
}
mysqli_query($con,"set names utf8"); //数据库输出编码 应该与你的数据库编码保持一致.南昌网站建设公司百恒网络PHP工程师建议用UTF-8 国际标准编码.
$objReader=PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format
$objPHPExcel=$objReader->load(ROOT_NAME.$file);//$file_url即Excel文件的路径
$sheet=$objPHPExcel->getSheet(0);//获取第一个工作表
$highestRow=$sheet->getHighestRow();//取得总行数
$highestColumn=$sheet->getHighestColumn(); //取得总列数
$times = time();
$i = 0;
//循环读取excel文件,读取一条,插入一条
for($j=2;$j<=$highestRow;$j++){//从第一行开始读取数据
 $str='';
 for($k='A';$k<=$highestColumn;$k++){            //从A列读取数据
 //这种方法简单，但有不妥，以'\\'合并为数组，再分割\\为字段值插入到数据库,实测在excel中，如果某单元格的值包含了\\导入的数据会为空
     if($k=='D'){//指定D列为时间所在列
         $str.= gmdate("Y-m-d H:i:s", PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue())).'\\';
     }else{
         $str.=$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue().'\\';//读取单元格
     }

 }
 //explode:函数把字符串分割为数组。
 $strs=explode("\\",$str);
    //信管家账号不存在的时候，跳出该层循环

    if(!$strs[0]) {
        continue;
    }
    $add_time = strtotime($strs[3]);
    $i++;
 $sql="INSERT INTO `admin_dai_list`(`tel`,`p_name`,`money`,`created_time`) VALUES (
                     '{$strs[0]}','{$strs[1]}','{$strs[2]}','{$add_time}')";
 mysqli_query($con,$sql);//这里执行的是插入数据库操作
}

echo 100;exit;
//echo 100;exit;
//unlink($file_url); //删除excel文件
?>