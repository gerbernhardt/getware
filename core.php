<?php

#
# Getware: Ultra-Secure Script
# Filename: core.php, 2004/11/03
# Copyright (c) 2004 - 2012 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();
class core {
 # FUNCTION SECURE_GET()
 function secure_get($index,$type='int',$separator=',',$zerofill=false){
  $_GET[$index]=explode($separator,$_GET[$index]);
  for($i=0;$i<count($_GET[$index]);$i++) {
   if($type=='double')
    $_GET[$index][$i]=doubleval($_GET[$index][$i]);
   else $_GET[$index][$i]=intval($_GET[$index][$i]);
   # NO ME COLOREABA EL GRID AL GUARDAR LOS ID CON ZEROFILL EN LA BASE DE DATOS
   if($zerofill) $_GET[$index][$i]=str_pad((string)$_GET[$index][$i],$zerofill,'0',STR_PAD_LEFT);
  }
 }
 # FUNCTION SECURE_POST()
 function secure_post($index,$type='int',$separator=',') {
  $_POST[$index]=explode($separator,$_POST[$index]);
  for($i=0;$i<count($_POST[$index]);$i++) {
   if($type=='double')
    $_POST[$index][$i]=doubleval($_POST[$index][$i]);
   else $_POST[$index][$i]=intval($_POST[$index][$i]);
  }
 }
 # FUNCTION GETDAYS
 function getdays($x) {
  $x=explode('-',$x);
  return date('d',mktime(0,0,0,$x[1]+1,0,$x[0]));
 }
 # FUNCTION GET_MONTHS
 function get_months($x,$y) {
  $x=explode('-',$x);
  $y=explode('-',$y);
  $years=$x[0]-$y[0];
  $months=$x[1]-$y[1];
  return ($years*12)+$months;
 }
 # INCLUDE_ENC
 function include_enc() {
  $x='for($fc=0;$fc<count($files);$fc++){';
  $x.=' if(file_exists($path.$files[$fc].\'.obj\')){';
//  $x.='  if($content=file($path.$files[$fc].\'.obj\',FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES)){';
  $x.='  if($content=file_get_content($path.$files[$fc].\'.obj\')){';
  $x.='   eval(base64_decode($content));';
  $x.='  }';
  $x.=' }else{';
  $x.='  include($path.$files[$fc].\'.php\');';
//  $x.='  print $files[$fc].\'<br>\';';
  $x.=' }';
  $x.='}';
  return $x;
 }

 function getdate($x) {
  $_DATE=array();
  $x=explode('-',$x);
  $_DATE['now']['now']=implode('-',$x);
  $_DATE['now']['year']=$x[0];
  $_DATE['now']['month']=$x[1];
  $_DATE['now']['ini']=substr($_DATE['now']['now'],0,8).'01';
  $_DATE['now']['end']=substr($_DATE['now']['ini'],0,8).$this->getdays($_DATE['now']['ini']);
  # NEXT DATE
  if($x[1]<12)
   {$y=$x[0];$m=$x[1]+1;}
  else {$y=$x[0]+1;$m=1;}
  if($m<10) $m='0'.$m;

  $_DATE['next']['year']=$y;
  $_DATE['next']['month']=$m;
  $_DATE['next']['ini']=$y.'-'.$m.'-01';
  $_DATE['next']['end']=substr($_DATE['next']['ini'],0,8).$this->getdays($_DATE['next']['ini']);

  # BACK DATE
  if($x[1]>1)
   {$y=$x[0];$m=$x[1]-1;}
  else {$y=$x[0]-1;$m=12;}
  if($m<10) $m='0'.$m;
  $_DATE['back']['year']=$y;
  $_DATE['back']['month']=$m;
  $_DATE['back']['ini']=$y.'-'.$m.'-01';
  $_DATE['back']['end']=substr($_DATE['back']['ini'],0,8).$this->getdays($_DATE['back']['ini']);
  return $_DATE;
 }
 function setint($x) {
  if(strlen($x)<1)
   return '00';
  elseif(strlen($x)==1)
   return '0'.$x;
  else return $x;
 }
 function setdate($x) {
  if(preg_match('/\//',$x))
   $x=preg_replace('/\//','-',$x);
  $x=explode('-',$x);
  for($i=0;$i<count($x);$i++)
   $x[$i]=$this->setint($x[$i]);
  if(count($x)==3) {
   if(strlen($x[0])==4&&$x[1]>0&&$x[1]<13)
    return $x[0].'-'.$x[1].'-'.$x[2];
   elseif(strlen($x[0])==4&&$x[2]>0&&$x[2]<13)
    return $x[0].'-'.$x[2].'-'.$x[1];
   elseif(strlen($x[2])==4&&$x[1]>0&&$x[1]<13)
    return $x[2].'-'.$x[1].'-'.$x[0];
   elseif(strlen($x[2])==4&&$x[0]>0&&$x[0]<13)
    return $x[2].'-'.$x[0].'-'.$x[1];
   else return '0000-00-00';
  } elseif(count($x)==2) {
   if(strlen($x[0])==4&&$x[1]>0&&$x[1]<13) # 2015-12->2015-12-00
    return $x[0].'-'.$x[1].'-00';
   elseif(strlen($x[1])==4&&$x[0]>0&&$x[0]<13) # 12-2015->2015-12-00
    return $x[1].'-'.$x[0].'-00';
   elseif($x[1]>0&&$x[1]<13) # 15-12->15-12-00
    return $x[0].'-'.$x[1].'-00';
   elseif($x[0]>0&&$x[0]<13) # 12-15->15-12-00
    return $x[1].'-'.$x[0].'-00';
   else return '0000-00-00';
  } else return $x[0].'-00-00';
 }
 # FUNCTION ACCESS()
 function access($access) {
  global $_USER;
  if(isset($_USER)&&$access==1)
   return true;
  elseif(!isset($_USER)&&$access==2)
   return true;
  elseif($access==3)
   return true;
  else return false;
 }
 # FUNCTION BLOCKS()
 function blocks($type) {
  global $_DB,$THEME,$HTML,$_SETTINGS,$_USER;
  if($type=='left') $type=1;
  elseif($type=='top') $type=2;
  elseif($type=='right') $type=3;
  $in='2,3';
  if(isset($_USER)) $in='1,3';
  $dot='&nbsp;<b>&middot;</b> ';
  if(file_exists('themes/'.$_SETTINGS['theme'].'/dot.gif'))
   $dot='&nbsp;<img src="themes/'.$_SETTINGS['theme'].'/dot.gif"> ';
  $sql='SELECT * FROM '.$_DB['prefix'].'sys_blocks WHERE access IN('.$in.') ORDER BY id';
  if($r=mysqli_query($_DB['session'],$sql)) {
   while($f=$r->fetch_array()) {
    if(file_exists('blocks/'.$f['file'].'.php')) {
     include('blocks/'.$f['file'].'.php');
     if(isset($content)) {
      print '<div class="block" thick="1">';
      print '<div class="block-header">'.$f['name'].'</div>';
      print '<div class="block-content">'.$content.'</div>';
      print '</div>';
      unset($content);
     }
    }
   }
  }
 }
 # ALERT JSON GENERATOR
 function alert($data,$reference=false,$module=false,$action=false,$blank=false) {
  $_MODULE['output']='[{run:"getware.ui.alert.make",';
  if($reference) $_MODULE['output'].='reference:"'.$reference.'",';
  if($module) $_MODULE['output'].='module:"'.$module.'",';
  if($action) $_MODULE['output'].='action:"'.$action.'",';
  if($blank) $_MODULE['output'].='blank:true,';
  $_MODULE['output'].='data:"'.$data.'"}]';
  exit($_MODULE['output']);
 }
 # CONVIERTE EL ARRAY POST EN UN STRING
 # GET['POST'] X=NULL&Y=SKULL&
 # IGNORA EL POST USERNAME&&PASSWORD
 function post($x) {
  foreach($x as $i=>$j) {
   if(is_array($j))
    $x[$i]=$this->post($j);
   elseif($i!='username'&&$i!='password')
    $_GET['post'].=$i.'='.$j.'&';
  }
 }
 # LOGIN JSON GENERATOR
 function login($data) {
  $_MODULE['output']='[{run:"getware.ui.login.make",';
  $_MODULE['output'].='get:"'.html_entity_decode($_SERVER['PATH'].'index.php'.'?'.$_SERVER['QUERY_STRING']).'",';
  $_GET['post']='';
  $this->post($_POST);
  $_MODULE['output'].='post:"'.$_GET['post'].'",';
  $_MODULE['output'].='data:"'.$data.'"}]';
  exit($_MODULE['output']);
 }
 #FUNCTION HEADER()
 function header($title) {
  global $_SETTINGS;
  $output='<!doctype html>';
  $output.='<html lang="'.$_SETTINGS['language'].'">';
  $output.='<head>';
  $output.='<title>'.$title.'</title>';
  $output.='<meta http-equiv="content-type" content="text/html;charset=utf-8" />';
  $output.='<meta http-equiv="expires" content="0" />';
  $output.='<meta name="resource-type" content="document" />';
  $output.='<meta name="distribution" content="global" />';
  $output.='<meta name="author" content="'.$_SETTINGS['email'].'" />';
  $output.='<meta name="copyright" content="copyright (c) '.$_SETTINGS['sitename'].'" />';
  $output.='<meta name="keywords" content="'.$_SETTINGS['keywords'].' />';
  $output.='<meta name="description" content="'.$_SETTINGS['slogan'].'" />';
  $output.='<meta name="robots" content="index,follow" />';
  $output.='<meta name="revisit-after" content="1 days" />';
  $output.='<meta name="rating" content="general" />';
  $output.='<meta name="generator" content="getware" />';
  $output.='<link rel="shortcut icon" href="images/getware.png" />';
  # JQUERY
  $output.='<script src="includes/jquery-1.7.1.min.js"></script>';
  # JQUERY UI
  $output.='<script src="includes/jquery-ui-1.8.17.custom.min.js"></script>';
  $output.='<script src="includes/jquery-ui-datepicker-es.js"></script>';
  $output.='<link href="includes/start/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" type="text/css" />';
  # JQUERY FOR MOBILE ANDROID USERS
  $output.='<script src="includes/jquery.ui.touch-punch.min.js"></script>';
  # JQUERY PROGRESS BAR
  $output.='<script src="includes/jquery.ajax-progress.js"></script>';
  # JQUERY BARCODE
  $output.='<script src="includes/jquery-barcode-2.0.2.min.js"></script>';
  # JQUERY UI MULTISELECT
  $output.='<script src="includes/jquery-ui-multiselect.min.js"></script>';
  $output.='<link href="includes/jquery-ui-multiselect.css" rel="stylesheet" type="text/css" />';
  $output.='<script src="includes/pdf/html2canvas.min.js"></script>';
  $output.='<script src="includes/pdf/jspdf.min.js"></script>';
  $output.='<script src="includes/pdf/script.js"></script>';
  # GETWARE
  $output.='<link href="themes/'.$_SETTINGS['theme'].'.css" rel="stylesheet" type="text/css" />';
  $output.='<script src="includes/getware.js"></script>';
  $output.='<script src="includes/getware.ui.alert.js"></script>';
  $output.='<script src="includes/getware.ui.login.js"></script>';
  $output.='<script src="includes/getware.ui.dialog.js"></script>';
  $output.='<script src="includes/getware.ui.search.js"></script>';
  $output.='<script src="includes/getware.ui.grid.js"></script>';
  $output.='<script src="includes/getware.ui.menu.js"></script>';
  $output.='<script src="includes/getware.ui.submenu.js"></script>';
  $output.='<script src="includes/getware.ui.content.js"></script>';
  $output.='<link href="includes/getware.ui.grid.css" rel="stylesheet" type="text/css" />';
  # CUSTOM DIRECTORY
  if(is_dir('../'.$_SERVER['PATH'].'custom/')){
   if($open=opendir('../'.$_SERVER['PATH'].'custom/')){
    while($data=readdir($open)) {
     if(substr($data,strlen($data)-3,3)=='.js')
      $output.='<script src="custom/'.utf8_encode($data).'"></script>';
     if(substr($data,strlen($data)-4,4)=='.css')
      $output.='<link href="custom/'.utf8_encode($data).'" rel="stylesheet" type="text/css" />';
    }
    closedir($open);
   }
  }
  $output.='</head>';
  return $output;
 }
}

?>