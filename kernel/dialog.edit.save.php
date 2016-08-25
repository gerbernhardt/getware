<?php

#
# Getware: Ultra-Secure Script
# Filename: kernel/dialog.edit.save.php,2004/09/06
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();
if(isset($_GET['save'])) {
 mysqli_query($_DB['session'],'SET @clone=\'\'');  # @PARENT NOMBRE DEL REGISTRO PARENT='remito'
 mysqli_query($_DB['session'],'SET @clone_val=0'); # @PARENT_ID REGISTRO PADRE INSERTADO PARENT_ID=33
 if(!is_array($_GET['edit'])) intval($_GET['edit']);

 $_MODULE['output']='[{run:"getware.ui.content.info.edit",window:"'.$_POST['window'].'",';
 $rows='';
 $data='';
 for($i=$_ADMIN['ini'];$i<$_ADMIN['end'];$i++) {
  if(!isset($_POST[$i])) $_POST[$i]='';
  #
  # REGISTRO REFERENCIAL
  #
  if($_TABLE['column']['function'][$i]=='REFERENCE') {
   #
   # MULTIPLEX  SELECT
   #
   $field='CONCAT(';
   for($j=1;$j<count($_TABLE['column']['comment'][$i]);$j++) {
    // SUB REGISTROS
    if(preg_match('/\[*\]/',$_TABLE['column']['comment'][$i][$j])){
     $reg=preg_replace('#(.*?)\[.*#si','\1',$_TABLE['column']['comment'][$i][$j]);
     $subreg=explode(':',preg_replace('#.*\[(.*?)\]#si','\1',$_TABLE['column']['comment'][$i][$j]));
     $field.='(SELECT x.'.$subreg[1].' FROM '.$subreg[0].' AS x WHERE x0.'.$reg.'=x.id)';
    }else $field.='x0.'.$_TABLE['column']['comment'][$i][$j];

    $field.=',\''.$_TABLE['column']['separator'][$i][$j].'\'';
    if($j<count($_TABLE['column']['comment'][$i])-1) $field.=',';
   }
   $field.=')';

   # REGISTRO CONDICIONAL
   if(isset($_MODULE['condition'][$_TABLE['column']['name'][$i]])) {
    $cond=$_MODULE['condition'][$_TABLE['column']['name'][$i]];
    $in='';
    $sql='SELECT '.$field.' FROM '.$_TABLE['name'].' AS x';
    $sql.=' INNER JOIN '.$_TABLE['column']['comment'][$i][0].' AS x0 ON x.'.$_TABLE['column']['name'][$i].'=x0.id';
    $sql.=' WHERE x.id='.$_GET['edit'];
    //$KERNEL->alert($sql);
    $result=mysqli_query($_DB['session'],$sql);
    if($fetch=$result->fetch_array()) {
     $cond=explode(';',$cond);
     for($x=0;$x<count($cond);$x++) {
      $aux=explode(':',$cond[$x]);
      $row[$aux[0]]=explode(',',$aux[1]);
     }
     if(isset($row[$fetch[0]])) {
      for($x=0;$x<count($row[$fetch[0]]);$x++) {
       $in.='\''.$row[$fetch[0]][$x].'\'';
       if($x<count($row[$fetch[0]])-1) $in.=',';
      }
     }
    }
   }
   $sql='UPDATE '.$_TABLE['name'].' AS x';
   $sql.=' INNER JOIN '.$_TABLE['column']['comment'][$i][0].' AS x0 ON '.$field.'=\''.$_POST[$i].'\'';
   if(isset($in)) $sql.=' AND '.$field.' IN('.$in.')';
   $sql.=' SET x.'.$_TABLE['column']['name'][$i].'=x0.id';
   $sql.=' WHERE x.id='.$_GET['edit'];
   //$KERNEL->alert($sql);
  }elseif($_TABLE['column']['function'][$i]=='REFERENCES') {
  #
  # MULTIPLEX  SELECT DIMENSIONAL
  #
   #afiliados_sexos#afiliado#sexo#sexos
   $table=explode('#',$_TABLE['column']['comment'][$i][0]);
   $sql='DELETE FROM '.$table[0].' WHERE '.$table[1].'='.$_GET['edit'];
   mysqli_query($_DB['session'],$sql);
   if(is_array($_POST[$i])){
    $sql='INSERT INTO '.$table[0].' (`'.$table[1].'`,`'.$table[2].'`) VALUES ';
    for($j=0;$j<count($_POST[$i]);$j++) {
     $sql.='('.$_GET['edit'].',(SELECT xx.id FROM '.$table[3].' AS xx WHERE xx.'.$_TABLE['column']['comment'][$i][1].'="'.$_POST[$i][$j].'"))';
     if($j<count($_POST[$i])-1) $sql.=',';
    }
   }
  } else {
   #
   # END OF REFERENCE
   #
   $sql='UPDATE '.$_TABLE['name'].' AS x';
   $sql.=' SET x.'.$_TABLE['column']['name'][$i].'=\''.$_POST[$i].'\'';
   $sql.=' WHERE x.id='.$_GET['edit'];
  }
  $rows.=$i;
  if(!mysqli_query($_DB['session'],$sql)){
   $data.='-1';/*$KERNEL->alert(mysqli_error().'<br>'.$sql);*/ // NO CUMPLE LA CONDICION
  }else $data.=mysqli_affected_rows($_DB['session']);
  if($i<count($_TABLE['column']['name'])-1) {
   $rows.=',';$data.=',';
  }
 }
 $_MODULE['output'].='rows:['.$rows.'],data:['.$data.']},';
 $_MODULE['output'].='{run:"getware.ui.alert.make",reference:"'.$_POST['window'].'",';
 if(function_exists('dialog_after_edit'))
  $_MODULE['output'].=dialog_after_edit().',';
 $_MODULE['output'].='data:"DATOS GUARDADOS!"}]';

 # INSERT BROTHER SET @CLONE=@CLONE_VAL
 $result=mysqli_query($_DB['session'],'SELECT @id,@clone,@clone_val');
 if($clone=$result->fetch_array()) {
  $sql='SELECT x.* FROM '.$_TABLE['name'].' AS x WHERE x.id='.$_GET['edit'];
  if($clone['@clone']!=''&&$result=mysqli_query($_DB['session'],$sql)) {
   if($fetch=$result->fetch_array()) {
    $sql='INSERT INTO '.$_TABLE['name'];
    $name='';
    $value='';
    for($i=$_ADMIN['ini'];$i<count($_TABLE['column']['name']);$i++) {
     $name.='`'.$_TABLE['column']['name'][$i].'`';
     $value.='\''.$fetch[$i].'\'';
     if($i<count($_TABLE['column']['name'])-1) {$name.=',';$value.=',';}
    }
    $sql.=' ('.$name.') VALUES ('.$value.')';
    if(mysqli_query($_DB['session'],$sql)) {
     $id=mysqli_insert_id();
     $sql='UPDATE '.$_TABLE['name'].' AS x';
     $sql.=' SET x.'.$clone['@clone'].'='.$clone['@clone_val'];
     $sql.=' WHERE x.id IN('.$id.')';
     mysqli_query($_DB['session'],$sql);
    }
   }
  }
 }
 // NO DEBE DEJAR QUE SIGA
 // TRAE CONFLICTO CON EL SAVE DEL GRID!!!!!
 #$_MODULE['next']=true;
 exit($_MODULE['output']);
 $KERNEL->alert('DATOS GUARDADOS!',$_GET['reference']);
}
?>