<?php

#
# Getware: Ultra-Secure Script
# Filename: blocks/admin.php, 2004/08/18
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

$sql='SELECT x.id,x.name,x.maximize FROM '.$_DB['prefix'].'sys_admin_groups AS x ORDER BY x.name';
  //exit($sql);
if($result['group']=mysqli_query($_DB['session'],$sql)) {
 while($group=$result['group']->fetch_array()) {
  $output='<div class="block" thick="'.$group['maximize'].'">';
  $output.='<div class="block-header">'.$group['name'].'</div>';
  $output.='<div class="block-content">';
  $sql='SELECT x.name,x.file FROM '.$_DB['prefix'].'sys_admin AS x';
  $sql.=' INNER JOIN '.$_DB['prefix'].'sys_admin_access AS x0 ON x.access=x0.id AND x0.name=\'Activo\'';
  if($_USER['type']!=1) # SI NO ES SUPER USUARIO
   $sql.=' INNER JOIN '.$_DB['prefix'].'sys_admin_privileges AS x1 ON x1.module=x.id AND x1.user=\''.$_USER['id'].'\'';
  $sql.=' WHERE x.group='.$group['id'].' ORDER BY x.name';
  //exit($sql);
  $input='';
  if($result['admin']=mysqli_query($_DB['session'],$sql)) {
   while($admin=$result['admin']->fetch_array()) {
    $input.=$dot.'<a href="#" onclick="javascript:getware.get(\'module=admin&amp;admin='.$admin['file'].'\');">'.$admin['name'].'</a><br>';
   }
  }
  $output.=$input.'</div></div>';
  if($input!='') print $output;
 }
}

?>