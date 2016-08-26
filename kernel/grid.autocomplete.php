<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel/grid.autocomplete.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();
#$_GET['row']=4-1 ROW:4 ID:1
$x=explode('-',$_GET['row']);
$row=intval($x[0])-1;
$_MODULE['grid']['edit']=$this->make_array($_MODULE['grid']['edit']);
if(count($x)>1&&isset($_MODULE['grid']['field'][$row])) {
 $field=$_MODULE['grid']['field'][$row];
 if(isset($_MODULE['grid']['edit'][$field])) {
  $x[0]=$_TABLE['column']['eman'][$field];
  $id=$x[1];$x=$x[0];

  $table=$_TABLE['name'];
  $field='x.'.$_TABLE['column']['name'][$x];
  if($_TABLE['column']['function'][$x]=='REFERENCE') {
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
     $field.='(SELECT xx.'.$subreg[1].' FROM '.$subreg[0].' AS xx WHERE x0.'.$reg.'=xx.id)';
    }else $field.='x0.'.$_TABLE['column']['comment'][$x][$j];

    $field.=',\''.$_TABLE['column']['separator'][$x][$j].'\'';
    if($j<count($_TABLE['column']['comment'][$x])-1) $field.=',';
   }
   $field.=')';
   # REGISTRO CONDICIONAL
   if(isset($_MODULE['condition'][$_TABLE['column']['name'][$x]])) {
    $cond=$_MODULE['condition'][$_TABLE['column']['name'][$x]];
    $in='';
    $sql='SELECT '.$field.' FROM '.$_TABLE['name'].' AS x';
    $sql.=' INNER JOIN '.$_TABLE['column']['comment'][$x][0].' AS x0 ON x.'.$_TABLE['column']['name'][$x].'=x0.id';
    $sql.=' WHERE x.id=\''.$id.'\'';
    //exit($sql);
    $result=mysqli_query($_DB['session'],$sql);
    if($fetch=$result->fetch_array()) {
     $cond=explode(';',$cond);
     for($x=0;$x<count($cond);$x++) {
      $aux=explode(':',$cond[$x]);
      $rows[$aux[0]]=explode(',',$aux[1]);
     }
     if(isset($rows[$fetch[0]])) {
      for($x=0;$x<count($rows[$fetch[0]]);$x++) {
       $in.='\''.$rows[$fetch[0]][$x].'\'';
       if($x<count($rows[$fetch[0]])-1) $in.=',';
      }
     }
    }
   }
  }

  $sql='SELECT DISTINCT '.$field.' FROM '.$table.' AS x0 WHERE';
  if(isset($in))
   $sql.=' '.$field.' IN('.$in.')';
  else $sql.=' '.$field.' LIKE \'%'.$_GET['term'].'%\'';
  $sql.=' ORDER BY '.$field.' LIMIT 20';
  //exit($sql);
  $values='';
  if($result=mysqli_query($_DB['session'],$sql)){
   for($i=0;$fetch=$result->fetch_array();$i++) {
    $values.='"'.$KERNEL->set_autocomplete($fetch[0]).'"';
    if($i<mysqli_num_rows($result)-1) $values.=',';
   }
  }
  $_MODULE['output']='['.$values.']';
  exit($_MODULE['output']);
 }
}
?>