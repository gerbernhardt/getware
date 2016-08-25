<?php

#
# Getware: Ultra-Secure Script
# Filename: kernel/dialog.submenu.php,2004/09/06
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();

$_MODULE['output']='{run:"getware.ui.submenu.make",window:"'.$_GET['window'].'",id:"'.$_GET['submenu'].'",title:"x"';
$row['value']='';
$row['label']='';
$row['info']='';
$submenu=intval($_GET['submenu']);
# NORMAL LIST
if(!isset($_TABLE['column']['comment'][$x])) {
 $sql='SELECT DISTINCT '.$_TABLE['column']['name'][$x].' FROM '.$_TABLE['name'];
 $sql.=' WHERE ('.$_TABLE['column']['name'][$x].' LIKE \''.$_GET['term'].'%\') LIMIT 20';
 //$this->alert($sql);
 $result=mysqli_query($_DB['session'],$sql);
 for($i=0;$fetch=$result->fetch_array();$i++) {
  $row['value'].='"'.$fetch[0].'"';
  $row['label'].='"'.str_replace('\n','',$fetch[0]).'"';
  if($i<mysqli_num_rows($result)-1) {
   $row['value'].=',';
   $row['label'].=',';
  }
 }
# FILE
}elseif($_TABLE['column']['function'][$x]=='FILE') {
 $path=array('/admin|images\/logos|custom/i');
// if(preg_match($path,$_TABLE['column']['comment'][$x][0]))
  $dir='..'.$_SERVER['PATH'].$_TABLE['column']['comment'][$x][0];
// else $dir='..'.$_SERVER['PATH'].$_TABLE['column']['comment'][$x][0];
 $filter=$_TABLE['column']['comment'][$x][1];
 $open=opendir($dir);
 unset($files);
 while($data=readdir($open)) {
  if(preg_match('/'.$filter.'/',$data)&&preg_match('/'.$_GET['term'].'/',$data))
   $files[]=utf8_encode($data);
 }
 closedir($open);
 for($i=0;isset($files)&&$i<count($files);$i++) {
  $row['value'].='"'.substr($files[$i],0,strlen($files[$i])-4).'"';
  $row['label'].='"'.substr($files[$i],0,strlen($files[$i])-4).'"';
  $row['info'].='""';
  if($i<count($files)-1) {
   $row['value'].=',';
   $row['label'].=',';
   $row['info'].=',';
  }
 }
} else {
 #
 # MULTIPLEX  SELECT
 #
 $table=$_TABLE['column']['comment'][$x][0];

 $field='CONCAT(';
 for($j=1;$j<count($_TABLE['column']['comment'][$x]);$j++) {
  // SUB REGISTROS
  if(preg_match('/\[*\]/',$_TABLE['column']['comment'][$x][$j])){
   $reg=preg_replace('#(.*?)\[.*#si','\1',$_TABLE['column']['comment'][$x][$j]);
   $subreg=explode(':',preg_replace('#.*\[(.*?)\]#si','\1',$_TABLE['column']['comment'][$x][$j]));
   $field.='(SELECT xx.'.$subreg[1].' FROM '.$subreg[0].' AS xx WHERE x.'.$reg.'=xx.id)';
  }else $field.='x.'.$_TABLE['column']['comment'][$x][$j];

  $field.=',\''.$_TABLE['column']['separator'][$x][$j].'\'';
  if($j<count($_TABLE['column']['comment'][$x])-1) $field.=',';
 }
 $field.=')';
 $sql='SELECT DISTINCT '.$field.' FROM '.$table.' AS x';
 if(isset($_MODULE['submenu']['filter'][$_TABLE['column']['name'][$x]])){
  $aux=$_MODULE['submenu']['filter'][$_TABLE['column']['name'][$x]];
  $aux=$_TABLE['column']['eman'][$x];
  $sql.=' INNER JOIN '.$_TABLE['column']['comment'][$aux][0].' AS f';
  $sql.=' ON f.'.$_TABLE['column']['comment'][$aux][1].'=\''.$_GET['filter'].'\'';
 }
 if(isset($_MODULE['submenu']['join'][$x]))
  $sql.=$_MODULE['submenu']['join'][$x];
 $sql.=' WHERE '.$field.' LIKE \'%'.$_GET['term'].'%\'';

 if(isset($_MODULE['submenu'][$x])){
  for($i=0;$i<count($_MODULE['submenu'][$x]);$i++){
   $sql.=$_MODULE['submenu'][$x][$i];
  }
 }
 $sql.=' ORDER BY '.$field.' LIMIT 20';
 //exit($sql);
 if($result=mysqli_query($_DB['session'],$sql)) {
  for($i=0;$fetch=$result->fetch_array();$i++) {
   $value=$fetch[0];
   $label=$fetch[0];
   $info='';
   if(function_exists('submenu_list')) {
    $sublist=submenu_list($submenu,$fetch[0],$table,$field);
    if(is_array($sublist)){
     if(isset($sublist['value'])) $value=$sublist['value'];
     if(isset($sublist['label'])) $label=$sublist['label'];
     if(isset($sublist['info'])) $info=$sublist['info'];
    }
   }
   $row['value'].='"'.str_replace('\n','',$value).'"';
   $row['label'].='"'.substr($label,0,500).'"';
   $row['info'].='"'.substr($info,0,500).'"';
   if($i<mysqli_num_rows($result)-1) {
    $row['value'].=',';
    $row['label'].=',';
    $row['info'].=',';
   }
  }
 } else $CORE->alert(mysqli_error($_DB['session']));
}
$_MODULE['output'].=',value:['.$row['value'].']';
$_MODULE['output'].=',label:['.$row['label'].']';
$_MODULE['output'].=',info:['.$row['info'].']';
$_MODULE['output'].='}';
exit('['.$_MODULE['output'].']');

?>