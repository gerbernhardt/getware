<?php

#
# Getware: Ultra-Secure Script
# Filename: kernel/dialog.add.show.php,2004/09/06
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();

if($_GET['add']!='save') {
 $_MODULE['output']=$KERNEL->dialog->header(_ADD);
 $_MODULE['output'].='append:"add",';
 $name='name:[';
 $type='type:[';
 $data='data:[';
 for($i=$_ADMIN['ini'];$i<$_ADMIN['end'];$i++) {
  $name.='"'.utf8_encode(strtoupper(str_replace('_',' ',$_TABLE['column']['name'][$i]))).'"';
  $type.='"'.$KERNEL->row_type($i).'"';

  if(isset($_MODULE['data'][$_TABLE['column']['name'][$i]])){
   $data.='"'.$_MODULE['data'][$_TABLE['column']['name'][$i]].'"';
  }elseif($_TABLE['column']['function'][$i]=='REFERENCES'){
     $field=$_TABLE['column']['comment'][$i][1];
     $table=explode('#',$_TABLE['column']['comment'][$i][0]);
     // NO SELECT
     $sql='SELECT '.$field.' FROM '.$table[3].' AS x';
     if($result['i']=mysqli_query($_DB['session'],$sql)) {
      while($fetch['i']=mysqli_fetch_array($result['i'])){
       $data_not_select[]='"'.$fetch['i'][0].'"';
      }
     } else $KERNEL->alert(mysqli_error($_DB['session']));
     $data.='[[],['.implode(',',$data_not_select).']]';
  }else $data.='""';

  if($i<$_ADMIN['end']-1) {
   $name.=',';
   $type.=',';
   $data.=',';
  }
 }
 $_MODULE['output'].=$name.'],'.$type.'],'.$data.']}';
 $KERNEL->json_print($_MODULE['output']);
 exit();
}

?>