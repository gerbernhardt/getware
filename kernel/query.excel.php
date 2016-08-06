<?php

#
# Getware: Ultra-Secure Script
# Filename: excel.php,2004/09/06
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();

if(isset($_GET['excel'])){
 //include('../PHPExcel');
 $sql='SELECT x.sql FROM '.$_DB['prefix'].'sys_query AS x';
 $sql.=' WHERE x.user=\''.$_USER['id'].'\' AND x.module=\''.$_GET['admin'].'\' AND x.ip=\''.$_SERVER['REMOTE_ADDR'].'\'';
 if($result=mysqli_query($_DB['session'],$sql)){
  if($fetch=$result->fetch_array()){

   if(PHP_SAPI=='cli') die('This example should only be run from a Web Browser');
   require_once '../PHPExcel/PHPExcel.php';
   $objPHPExcel=new PHPExcel();
   $objPHPExcel->getProperties()->setCreator('German Bernhardt <german.bernhardt@gmail.com>')
   							 ->setLastModifiedBy('German Bernhardt <german.bernhardt@gmail.com>')
   							 ->setTitle('Excel by Getware')
   							 ->setSubject('Excel by Getware')
   							 ->setDescription('Document for Office 2007 XLSX, generated using Getware and PHP classes.')
   							 ->setKeywords('office 2007 openxml php getware')
   							 ->setCategory('Result file');
   $col=array('A','B','C','D','E','F','G','H','I','J','K','M','N','L','O','P','Q','R','S','T','U','V','W','X','Y','Z');
   $objPHPExcel->setActiveSheetIndex(0);

   for($i=0;$i<count($_MODULE['grid']['name']);$i++){
    $objPHPExcel->getActiveSheet()->setCellValue($col[$i].'1',$_MODULE['grid']['name'][$i]);
    $headersStyle=new PHPExcel_Style();
    //$contentStyle=new PHPExcel_Style();
    $headersStyle->applyFromArray(array(
     'font'=>array(
      'bold'=>true,
      'color'=>array('rgb' =>'000000')
     ),
     'fill'=>array(
      'type'=>PHPExcel_Style_Fill::FILL_SOLID,
      'color'=>array('rgb' =>'a0a0a0')
     )//,'borders'=>array('bottom'=> array('style'=>PHPExcel_Style_Border::BORDER_THIN),'right'=>array('style'=>PHPExcel_Style_Border::BORDER_MEDIUM))
    ));
    $objPHPExcel->getActiveSheet()->setSharedStyle($headersStyle,'A1:'.$col[count($_MODULE['grid']['name'])-1].'1');
    $objPHPExcel->getActiveSheet()->getColumnDimension($col[$i])->setWidth($_MODULE['grid']['size'][$i]);
   }

   if($result=mysqli_query($_DB['session'],base64_decode($fetch[0]))){
    for($i=2;$fetch=$result->fetch_array();$i++){
     for($j=0;$j<count($_MODULE['grid']['name']);$j++){
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$j].$i,$fetch[$j+1]);
     }
    }
   }

   $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
   $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

   //$objPHPExcel->getActiveSheet()->setTitle('Simple');
   header('Content-Type: application/vnd.ms-excel');
   header('Content-Disposition: attachment;filename="'.$_GET['admin'].'-'.$_USER['username'].'-'.date('Y-m-d-H-i-s').'.xls"');
   header('Cache-Control: max-age=0');
   // If you're serving to IE 9, then the following may be needed
   header('Cache-Control: max-age=1');
   // If you're serving to IE over SSL, then the following may be needed
   header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
   header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
   header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
   header ('Pragma: public'); // HTTP/1.0
   $objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
   $objWriter->save('php://output');
   exit();
  }
 }
}

?>