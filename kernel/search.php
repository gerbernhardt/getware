<?php

#
# Getware: Ultra-Secure Script
# Filename: search.php, 2012/04/03
# Copyright (c) 2010 - 2012 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

class kernel_search{
 function autocomplete() {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE,$KERNEL;
  if(isset($_GET['search'])&&isset($_GET['term'])) {
   $x=$_MODULE['search']['field'][$_GET['search']];
   $x=$_TABLE['column']['eman'][$x];
   if($_TABLE['column']['function'][$x]=='REFERENCE') {
    #
    # MULTIPLEX  SELECT
    #
    // CAMBIA TEMPORALMENTE EL REFERENCE PARA EL WHERE
    if(isset($_MODULE['search']['reference'][$_MODULE['search']['name'][$_GET['search']]])){
     $_AUX['comment']=$_TABLE['column']['comment'][$x];
     $_AUX['separator']=$_TABLE['column']['separator'][$x];

     if(isset($_MODULE['search']['separator'][$_MODULE['search']['name'][$x]])){
      $_TABLE['column']['separator'][$x]=$_MODULE['search']['separator'][$_MODULE['search']['name'][$x]];
     }else{
      for($j=1;$j<count($_TABLE['column']['comment'][$x]);$j++)
       $_TABLE['column']['separator'][$x][$j]='';
     }
     $_TABLE['column']['comment'][$x]=$_MODULE['search']['reference'][$_MODULE['search']['name'][$_GET['search']]];
    }

    $table=$_TABLE['column']['comment'][$x][0];
    $field='CONCAT(';
    for($j=1;$j<count($_TABLE['column']['comment'][$x]);$j++) {
     // SUB REGISTROS
     if(preg_match('/\[*\]/',$_TABLE['column']['comment'][$x][$j])){
      $field.=$KERNEL->query->subquery($_TABLE['column']['comment'][$x][$j]);
     }else $field.='x.'.$_TABLE['column']['comment'][$x][$j];
     $field.=',\''.$_TABLE['column']['separator'][$x][$j].'\'';
     if($j<count($_TABLE['column']['comment'][$x])-1) $field.=',';
    }
    $field.=')';
    // VUELVE EL REFERENCE ANTERIOR
    if(isset($_AUX)){$_TABLE['column']['comment'][$x]=$_AUX;unset($_AUX);}


   #
   # END OF REFERENCE
   #
   } elseif(isset($_MODULE['search']['sql'][$_MODULE['search']['name'][$_GET['search']]])) {
    $table=$_TABLE['name'];
    $field=$_MODULE['search']['sql'][$_MODULE['search']['name'][$_GET['search']]];
   } else {
    $table=$_TABLE['name'];
    $field=$_TABLE['column']['name'][$x];
   }
   $sql='SELECT DISTINCT '.$field.' FROM '.$table.' AS x';
   $sql.=' WHERE '.$field.' LIKE \'%'.$_GET['term'].'%\' ORDER BY '.$field.' LIMIT 20';
   $values='';
   //exit($sql);
   if(!$result=mysqli_query($_DB['session'],$sql)) exit(mysqli_error($_DB['session']));
   for($i=0;$fetch=$result->fetch_array();$i++) {
    $values.='"'.$KERNEL->set_autocomplete($fetch[0]).'"';
    if($i<mysqli_num_rows($result)-1) $values.=',';
   }
   $_MODULE['output']='['.$values.']';
   exit($_MODULE['output']);
   exit(preg_replace('/(?:\s|&quot;)+/','\\"',$_MODULE['output']));
  }
 }
 function content() {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE;
  $name='name:[';
  $size='size:[';
  $data='data:[';
  $condition='condition:{';
  for($i=0;$i<count($_MODULE['search']['name']);$i++){
   $name.='"'.utf8_encode(strtoupper(str_replace('_',' ',$_MODULE['search']['name'][$i]))).'"';
   $size.='"'.$_MODULE['search']['size'][$i].'"';
   isset($_POST['search'][$i])?$data.='"'.$_POST['search'][$i].'"':$data.='""';
   if(isset($_MODULE['search']['condition'][$_MODULE['search']['name'][$i]])){   	$condition.=$_MODULE['search']['name'][$i].':[';
   	for($j=0;$j<count($_MODULE['search']['condition'][$_MODULE['search']['name'][$i]]);$j++){
   	 $condition.='"'.$_MODULE['search']['condition'][$_MODULE['search']['name'][$i]][$j].'"';
   	 if($j<count($_MODULE['search']['condition'][$_MODULE['search']['name'][$i]])-1) $condition.=',';
   	}
   	$condition.=']';
   }
   if($i<count($_MODULE['search']['name'])-1) {
    $name.=',';
    $size.=',';
    $data.=',';
   }
  }
  if(isset($_MODULE['next']))
   $_MODULE['output'].=',';
  else $_MODULE['output']='';
  $_MODULE['output'].='{run:"$getware.ui.search.make",module:"'.$_GET['admin'].'",window:"td[id=content_center]",'.$name.'],'.$size.'],'.$data.'],'.$condition.'}}';
 }
}
$KERNEL->search=new kernel_search;

?>