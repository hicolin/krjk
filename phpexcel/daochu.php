<?php
error_reporting(E_ALL);
date_default_timezone_set('Asia/Shanghai');
require_once './Classes/PHPExcel.php';

$data=array(
 0=>array(
  'id'=>1001,
  'username'=>'�ŷ�',
  'password'=>'123456',
  'address'=>'����ʱ����ׯ250��101��'
 ),
 1=>array(
  'id'=>1002,
  'username'=>'����',
  'password'=>'123456',
  'address'=>'����ʱ����ɽ'
 ),
 2=>array(
  'id'=>1003,
  'username'=>'�ܲ�',
  'password'=>'123456',
  'address'=>'�Ӱ���·2055Ū3��'
 ),
 3=>array(
  'id'=>1004,
  'username'=>'����',
  'password'=>'654321',
  'address'=>'��԰·188��3309��'
 )
);

$objPHPExcel=new PHPExcel();
$objPHPExcel->getProperties()->setCreator('http://www.jb51.net')
        ->setLastModifiedBy('http://www.jb51.net')
        ->setTitle('Office 2007 XLSX Document')
        ->setSubject('Office 2007 XLSX Document')
        ->setDescription('Document for Office 2007 XLSX, generated using PHP classes.')
        ->setKeywords('office 2007 openxml php')
        ->setCategory('Result file');
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1','ID')
            ->setCellValue('B1','�û���')
            ->setCellValue('C1','����')
            ->setCellValue('D1','��ַ');

$i=2;   
foreach($data as $k=>$v){
 $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,$v['id'])
            ->setCellValue('B'.$i,$v['username'])
            ->setCellValue('C'.$i,$v['password'])
            ->setCellValue('D'.$i,$v['address']);
 $i++;
}
$objPHPExcel->getActiveSheet()->setTitle('���꼶2��');
$objPHPExcel->setActiveSheetIndex(0);
$filename=urlencode('ѧ����Ϣͳ�Ʊ�').'_'.date('Y-m-dHis');


/*
*����xlsx�ļ�
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
*/

/*
*����xls�ļ�
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
*/

$objWriter->save('php://output');
exit;
?>