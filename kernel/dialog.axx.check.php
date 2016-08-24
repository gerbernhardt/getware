<?php

#
# Getware: Ultra-Secure Script
# Filename: dialog.axx.check.php, 2012/04/03
# Copyright (c) 2010 - 2012 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

class kernel_dialog_axx_check{
 function restrict($i,$j) {
  global $_DB,$_USER,$_ADMIN,$_TABLE,$_MODULE;
  if(isset($_MODULE['restrict'][_ADD][$_TABLE['column']['name'][$i]])) {
   $row['name']=$_TABLE['column']['name'][$i];
   $row['data']=$_MODULE['restrict'][_ADD][$_TABLE['column']['name'][$i]];
   $row['operator']='==';
   if(isset($_MODULE['restrict'][_OPERATOR][_ADD][$_TABLE['column']['name'][$i]]))
    $row['operator']=$_MODULE['restrict'][_OPERATOR][_ADD][$row['name']];
   $e='if($row[\'data\']'.$row['operator'].'$_POST[$i][$j])';
   $e.=' return true;';
   $e.='else return false;';
   return eval($e);
  } else return true;
 }
 #
 # EL REGISTRO RELACIONAL TIENE QUE EXISTIR SI O SI
 #
 function exists($i,$j) {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE;
  if(isset($_MODULE['exists'][$_TABLE['column']['name'][$i]])) {

   #
   # MULTIPLEX  SELECT
   #
   $field='CONCAT(';
   for($k=1;$k<count($_TABLE['column']['comment'][$i]);$k++) {
    // SUB REGISTROS
    if(preg_match('/\[*\]/',$_TABLE['column']['comment'][$i][$k])){
     $reg=preg_replace('#(.*?)\[.*#si','\1',$_TABLE['column']['comment'][$i][$k]);
     $subreg=explode(':',preg_replace('#.*\[(.*?)\]#si','\1',$_TABLE['column']['comment'][$i][$k]));
     $field.='(SELECT xx.'.$subreg[1].' FROM '.$subreg[0].' AS xx WHERE x.'.$reg.'=xx.id)';
    }else $field.='x.'.$_TABLE['column']['comment'][$i][$k];
    $field.=',\''.$_TABLE['column']['separator'][$i][$k].'\'';
    if($k<count($_TABLE['column']['comment'][$i])-1) $field.=',';
   }
   $field.=')';

   $table=$_TABLE['column']['comment'][$i][0];
   # REGISTRO RELACIONAL
   if(isset($_TABLE['column']['comment'][$i])) {
    $sql='SELECT NULL FROM '.$table.' AS x';
    $sql.=' WHERE '.$field.'=\''.$_POST[$i][$j].'\'';
//exit($sql);
   } else return true;
   if($result=mysqli_query($_DB['session'],$sql)){
    if(!mysqli_num_rows($result))
     return false;
    else return true;
   } return false;
  } else return true;
 }
 #
 # EL REGISTRO TIENE CONDICIONES EN LOS CARACTERES
 # NO ADMITE REGISTROS EN BLANCO
 #
 function blank($i,$j) {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE;
  if(isset($_MODULE['blank'][$_TABLE['column']['name'][$i]])) {
   if(trim($_POST[$i][$j])=='')
    return false;
   else return true;
  } else return true;
 }
 #
 # EL REGISTRO DEBE SER UN ENTERO
 #
 function int($i,$j) {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE;
  if(isset($_MODULE['int'][$_TABLE['column']['name'][$i]])) {
   if(intval($_POST[$i][$j])!=$_POST[$i][$j])
    return false;
   else return true;
  } else return true;
 }
 #
 # EL REGISTRO ES UNICO
 # CHEQUEA QUE EL REGISTRO INSERTADO NO ESTE DUPLICADO
 #
 function unique($i,$j) {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE;
  if(isset($_MODULE['unique'][$_TABLE['column']['name'][$i]])) {
   #
   # MULTIPLEX  SELECT
   #
   $reference=$_TABLE['column']['comment'][$i];
   if(count($reference)>2) {
    $field='CONCAT(';
    for($j=1;$j<count($reference);$j++) {
     $field.='x0.'.$reference[$j];
     if($j<count($reference)-1) $field.=',';
    }
    $field.=')';
   } else $field='x.'.$reference[1];
   # REGISTRO RELACIONAL
   if(isset($_TABLE['column']['comment'][$i])&&count($reference)>=2) {
    $sql='SELECT NULL FROM '.$_TABLE['name'].' AS x';
    $sql.=' INNER JOIN '.$reference[0].' AS x0 ON x.'.$_TABLE['column']['name'][$i].'=x0.id';
    $sql.=' WHERE '.$field.'=\''.$_POST[$i][$j].'\'';
   # REGISTRO NORMAL
   } else {
    $sql='SELECT NULL FROM '.$_TABLE['name'].' AS x';
    $sql.=' WHERE x.'.$_TABLE['column']['name'][$i].'=\''.$_POST[$i][$j].'\'';
   }
   $result=mysqli_query($_DB['session'],$sql);
   if(mysqli_num_rows($result))
    return false;
   else return true;
  } else return true;
 }
 function make_array($x) {
  global $_DB,$_MODULE;
  if(isset($_MODULE[$x])) {
   $c=count($_MODULE[$x]);
   for($i=0;$i<$c;$i++) {
    $_MODULE[$x][$_MODULE[$x][$i]]=true;
    unset($_MODULE[$x][$i]);
   }
  }
 }

 #
 # ENVIA EL JSON CON LOS DATOS INCORRECTOS
 #
 function info() {
  global $_DB,$_TABLE,$_ADMIN;
  if(!isset($_POST['window'])) exit();
  $_MODULE['output']='[{run:"getware.ui.content.info.add",window:"'.$_POST['window'].'",';
  $rows='';
  $data='';
  $this->make_array('int');
  $this->make_array('blank');
  $this->make_array('exists');
  $this->make_array('unique');
  for($j=0;$j<count($_POST[$_ADMIN['head']]);$j++) {
   for($i=$_ADMIN['ini'];$i<$_ADMIN['end'];$i++) {
    # GENERA LOS POST QUE NO EXISTEN
    if(!isset($_POST[$i][$j])) {
     if($i<$_ADMIN['head']&&isset($_POST[$i][$j-1]))
      $_POST[$i][$j]=$_POST[$i][$j-1];
     else $_POST[$i][$j]='';
    }
    $row=$i.'x';
    if($i>=$_ADMIN['head'])
     $row.=($j+1);
    if(isset($_POST[$i][$j]))
     if($this->unique($i,$j))
      if($this->int($i,$j))
       if($this->blank($i,$j))
        if($this->exists($i,$j))
         if($this->restrict($i,$j))
          $value=1;
         else $value=0;
        else $value=0;
       else $value=0;
      else $value=0;
     else $value=0;
    else $value=5;
    $rows.='"'.$row.'"';
    $data.='"'.$value.'"';
    if($i<count($_TABLE['column']['name'])-1) {
     $rows.=',';
     $data.=',';
    }
  }
   if($j<count($_POST[$_ADMIN['head']])-1) {
    $rows.=',';$data.=',';
   }
  }
  $_MODULE['output'].='rows:['.$rows.'],data:['.$data.']}]';
  if(preg_match('/"0"/',$_MODULE['output']))
   exit($_MODULE['output']);
  else return true;
 }
}
$KERNEL->dialog->axx->check=new kernel_dialog_axx_check;
?>