<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

class kernel{

 # TABLE ANALYZER
 function table() {
  global $_ADMIN,$_TABLE,$_DB;
  $sql='SELECT DISTINCT XC.column_name,XC.column_type,XC.column_comment';
  $sql.=' FROM INFORMATION_SCHEMA.COLUMNS AS XC';
  $sql.=' WHERE XC.TABLE_NAME=\''.$_TABLE['name'].'\' AND XC.TABLE_SCHEMA=\''.$_DB['name'].'\'';
  if($result=mysqli_query($_DB['session'],$sql)) {
   for($i=0;$fetch=$result->fetch_array();$i++) {
    if($fetch['column_type']=='datetime')
     $fetch['column_type']='datetime(10)';
    $_TABLE['column']['name'][$i]=$fetch['column_name'];
    $_TABLE['column']['eman'][$_TABLE['column']['name'][$i]]=$i;
    $_TABLE['column']['type'][$i]=$fetch['column_type'];
    $_TABLE['column']['length'][$i]=preg_replace('#.*\((.*?)\).*#si','\1',$fetch['column_type']);
    if(substr($_TABLE['column']['type'][0],strlen($_TABLE['column']['type'][0])-8,10)=='zerofill')
     $_TABLE['column']['zerofill'][$i]=true;
    $_TABLE['column']['size'][$i]=preg_replace('#.*\((.*?)\)#si','\1',$fetch['column_type']);

    $_TABLE['column']['function'][$i]=preg_replace('/\(.*\)/','',$fetch['column_comment']);
    if($_TABLE['column']['function'][$i]!=''){
     $_TABLE['column']['comment'][$i]=explode('.',preg_replace('#.*\((.*?)\)#si','\1',$fetch['column_comment']));
     for($j=1;$j<count($_TABLE['column']['comment'][$i]);$j++){
      $aux=explode('|',$_TABLE['column']['comment'][$i][$j]);
      $_TABLE['column']['comment'][$i][$j]=$aux[0];
      if(isset($aux[1]))
       $_TABLE['column']['separator'][$i][$j]=$aux[1];
      else $_TABLE['column']['separator'][$i][$j]='';
     }
    }

   } if($i<1) $this->alert('TABLA INEXISTENTE!');
   $_ADMIN['ini']=1;
   $_ADMIN['end']=count($_TABLE['column']['name']);
   //print_r($_TABLE);
  } else $this->alert('ERROR EN LA CONSULTA SQL!');
 }

 # PRIVILEGES ANALYZER
 function privilege($type) {
  global $_ADMIN,$_TABLE,$KERNEL;
  if(isset($_ADMIN[$type])&&isset($_GET[$type])){
   if($_ADMIN[$type]==true){
    return true;
   }else $this->alert('NO TIENES PRIVILEGIOS!');
  }else return false;
 }

 function restrict($type) {
  global $_USER,$CORE,$_ADMIN,$_TABLE,$_MODULE;
  $return=true;
  if(isset($_MODULE['restrict'][$type])){
   foreach($_MODULE['restrict'][$type] as $x=>$y) {
    $op='=';
    if(isset($_MODULE['restrict'][_OPERATOR][$type][$x]))
     $op=$_MODULE['restrict'][_OPERATOR][$type][$x];
   }
  } else $return=true;
  return $return;
 }

 # ROW ANALYZER
 function row_type($i) {
  global $_ADMIN,$_TABLE;
  $x=$_TABLE['column']['function'][$i];
  $x=$_TABLE['column']['function'][$i];
  if($x=='REFERENCE'||$x=='FILE'||$x=='FILE_CUSTOM')
   return 'view';
  elseif($x=='REFERENCES')
   return 'select';
  elseif($_TABLE['column']['name'][$i]=='password')
   return 'pass';
  elseif($_TABLE['column']['type'][$i]=='date')
   return 'date';
  elseif($_TABLE['column']['type'][$i]=='year')
   return 'year';
  elseif(preg_match('/descrip/',$_TABLE['column']['name'][$i]))
   return 'text';
  else return 'varchar';
 }

function set_autocomplete($str){
 $str=str_replace('\n','',$str);
 $str=str_replace('&quot;','\\"',$str);
 $str=str_replace('&amp;','&',$str);
 return $str;
}
 # JSON PRINT
 function json_print() {
  global $_MODULE;
  if(is_array($_MODULE['output'])) $_MODULE['output']=implode(',',$_MODULE['output']);
  $_MODULE['output']=preg_replace('/\r/','',$_MODULE['output']);
  $_MODULE['output']=preg_replace('/\n/','\\n',$_MODULE['output']);
  $_MODULE['output']='['.$_MODULE['output'].']';
  if(isset($_GET['ajax'])) {
   header('Content-Type: text/plain');
   header('Accept-Ranges: bytes');
   header('Content-Length: '.strlen($_MODULE['output']));
  }

  if(!isset($_GET['ajax']))
   print '<script>getware.data(\''.$_MODULE['output'].'\');</script>';
  else print $_MODULE['output'];
 }
 # ALERT JSON GENERATOR
 function alert($data,$reference=false,$module=false,$action=false,$blank=false,$button='Ok',$exec=false) {
  global $_ADMIN,$_TABLE,$_MODULE;
  $_MODULE['output']='{run:"getware.ui.alert.make",';
  if($reference) $_MODULE['output'].='reference:"'.$reference.'",';
  if($module) $_MODULE['output'].='module:"'.$module.'",';
  if($action) $_MODULE['output'].='action:"'.$action.'",';
  if($action) $_MODULE['output'].='button:"'.$button.'",';
  if($blank) $_MODULE['output'].='blank:true,';
  if($exec) $_MODULE['output'].='exec:"'.$exec.'",';
  $_MODULE['output'].='data:"'.$data.'"}';
  if(!isset($_MODULE['next'])) exit('['.$_MODULE['output'].']');
 }

}

$KERNEL=new kernel;
include('kernel/query.php');
include('kernel/search.php');
include('kernel/grid.php');
include('kernel/dialog.php'); 

?>