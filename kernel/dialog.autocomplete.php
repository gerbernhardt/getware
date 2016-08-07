<?php

#
# Getware: Ultra-Secure Script
# Filename: autocomplete.list.php,2004/09/06
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();

$values='';
$autocomplete=intval($_GET['autocomplete']);
# NORMAL LIST
if(!isset($_TABLE['column']['comment'][$x])) {
 $sql='SELECT DISTINCT '.$_TABLE['column']['name'][$x].' FROM '.$_TABLE['name'];
 $sql.=' WHERE ('.$_TABLE['column']['name'][$x].' LIKE \''.$_GET['term'].'%\') LIMIT 20';
 $result=mysqli_query($_DB['session'],$sql);
 for($i=0;$fetch=$result->fetch_array();$i++) {
  $values.='"'.$fetch[0].'"';
  if($i<mysqli_num_rows($result)-1) {
   $values.=',';
  }
 }
# FILE
}elseif($_TABLE['column']['function'][$x]=='FILE'||$_TABLE['column']['function'][$x]=='FILE_CUSTOM') {
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
  $values.='"'.substr($files[$i],0,strlen($files[$i])-4).'"';
  if($i<count($files)-1) {
   $values.=',';
  }
 }
} else {
 $inner='';
 if(isset($_MODULE['submenu']['join'][$x]))
  $inner=$_MODULE['submenu']['join'][$x];
 #
 # MULTIPLEX  SELECT
 #
 $table=$_TABLE['column']['comment'][$x][0];
 $field='CONCAT(';
 for($j=1;$j<count($_TABLE['column']['comment'][$x]);$j++) {
  // SUB REGISTROS
  if(preg_match('/\[*\]/',$_TABLE['column']['comment'][$x][$j])){
   $regXX=preg_replace('#(.*?)\[.*#si','\1',$_TABLE['column']['comment'][$x][$j]);
   $subregXX=explode(':',preg_replace('#.*\[(.*?)\]#si','\1',$_TABLE['column']['comment'][$x][$j]));
   if(preg_match('/\{*\}/',$_TABLE['column']['comment'][$x][$j])){
    $regXXX=preg_replace('#(.*?)\{.*#si','\1',$subregXX[1]);
    $subregXXX=explode(',',preg_replace('#.*\{(.*?)\}#si','\1',$subregXX[1]));
    $field.='(SELECT xxx.'.$subregXXX[1].' FROM '.$subregXX[0].' AS xx INNER JOIN '.$subregXXX[0].' AS xxx ON xx.'.$regXXX.'=xxx.id WHERE x.'.$regXX.'=xx.id)';
   }else $field.='(SELECT xx.'.$subregXX[1].' FROM '.$subregXX[0].' AS xx WHERE x.'.$regXX.'=xx.id)';
  }else $field.='x.'.$_TABLE['column']['comment'][$x][$j];

  $field.=',\''.$_TABLE['column']['separator'][$x][$j].'\'';
  if($j<count($_TABLE['column']['comment'][$x])-1) $field.=',';
 }
 $field.=')';
 $sql='SELECT DISTINCT '.$field.' FROM '.$table.' AS x';

 if(isset($_MODULE['submenu']['join'][$x]))
  $sql.=$_MODULE['submenu']['join'][$x];
 $sql.=' WHERE '.$field.' LIKE \'%'.$_GET['term'].'%\'';

 if(isset($_MODULE['submenu'][$autocomplete])){
  for($i=0;$i<count($_MODULE['submenu'][$autocomplete]);$i++){
   $sql.=' '.$_MODULE['submenu'][$autocomplete][$i];
  }
 }
 $sql.=' ORDER BY '.$field.' LIMIT 20';
 //exit($sql);
 if($result=mysqli_query($_DB['session'],$sql)) {
  for($i=0;$fetch=$result->fetch_array();$i++) {
   if(function_exists('submenu_list')) {
    $sublist=submenu_list($autocomplete,$fetch[0],$table,$field);
    if(is_array($sublist)){
   	 $values.='{';
   	 $values.='"value":"'.$KERNEL->set_autocomplete($sublist['value']).'"';
     if(isset($sublist['label'])) $values.=',"label":"'.$sublist['label'].'"';
     if(isset($sublist['info'])) $values.=',"info":"'.$sublist['info'].'"';
     $values.='}';
    } else $values.='"'.$KERNEL->set_autocomplete($fetch[0]).'"';
   } else $values.='"'.$KERNEL->set_autocomplete($fetch[0]).'"';
   if($i<mysqli_num_rows($result)-1) $values.=',';
  }
 } else exit(mysqli_error($_DB['session']));
}
$_MODULE['output']=$values;

header('Content-Type: text/plain');
header('Accept-Ranges: bytes');
header('Content-Length: '.strlen($_MODULE['output']));
$KERNEL->json_print();
exit();

?>