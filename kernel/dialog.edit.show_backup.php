<?php

#
# Getware: Ultra-Secure Script
# Filename: dialog.edit.show.php,2004/09/06
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();
if(!isset($_GET['save'])) {
 if(!is_array($_GET['edit'])) $CORE->secure_get('edit');
 $sql='SELECT x.* FROM '.$_TABLE['name'].' AS x WHERE x.id IN('.implode(',',$_GET['edit']).')';
 if($result['j']=mysqli_query($_DB['session'],$sql)) {
  $_MODULE['output']='';
  for($j=0;$fetch['j']=mysqli_fetch_array($result['j']);$j++) {
   $_MODULE['output'].=$KERNEL->dialog->header(_EDIT);
   $_MODULE['output'].='append:"edit",id:"'.$fetch['j']['id'].'",';
   if($upload) $_MODULE['output'].='buttom:"addfile",';
   $name='name:[';
   $type='type:[';
   $data='data:[';
   for($i=$_ADMIN['ini'];$i<$_ADMIN['end'];$i++) {
    $name.='"'.utf8_encode(strtoupper(str_replace('_',' ',$_TABLE['column']['name'][$i]))).'"';
    $type.='"'.$KERNEL->row_type($i).'"';

    if($_TABLE['column']['function'][$i]=='REFERENCE') {
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
     $sql='SELECT '.$field.' FROM '.$_TABLE['column']['comment'][$i][0].' AS x WHERE x.id=\''.$fetch['j'][$i].'\'';
     if($result['i']=mysqli_query($_DB['session'],$sql)) {
      if($fetch['i']=mysqli_fetch_array($result['i']))
       $data.='"'.$fetch['i'][0].'"';
      else $data.='"NULL"';
     } else $KERNEL->alert(mysqli_error());
    } else $_TABLE['column']['name'][$i]=='password'?$data.='""':$data.='"'.$fetch['j'][$i].'"';
    if($i<$_ADMIN['end']-1) {
     $name.=',';
     $type.=',';
     $data.=',';
    }
   }
   $_MODULE['output'].=$name.'],'.$type.'],'.$data.']}';
   if($j<mysqli_num_rows($result['j'])-1) $_MODULE['output'].=',';
  }
 }
 $KERNEL->json_print();
 exit();
}
?>