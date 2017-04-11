<?php
/*
 * Keep It Simple, Stupid!
 * Filename: blocks/admin.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

$sqlx='SELECT x.id,x.name,x.maximize FROM sys_admin_groups AS x ORDER BY x.name';
  //exit($sql);
if($result_group=mysqli_query($_DB['session'],$sqlx)) {
 while($group=$result_group->fetch_array()) {
  $output='<div class="block" thick="'.$group['maximize'].'">';
  $output.='<div class="block-header">'.$group['name'].'</div>';
  $output.='<div class="block-content">';
  $sql='SELECT x.name,x.file FROM sys_admin AS x';
  $sql.=' INNER JOIN sys_admin_access AS x0 ON x.access=x0.id AND x0.name=\'Activo\'';
  if($_USER['type']!=1) # SI NO ES SUPER USUARIO
   $sql.=' INNER JOIN sys_admin_privileges AS x1 ON x1.module=x.id AND x1.user=\''.$_USER['id'].'\'';
  $sql.=' WHERE x.group='.$group['id'].' ORDER BY x.name';
  //exit($sql);
  $input='';
  if($result_admin=mysqli_query($_DB['session'],$sql)) {
   while($admin=$result_admin->fetch_array()) {
    $input.=$dot.'<a href="#" onclick="javascript:getware.get(\'module=admin&amp;admin='.$admin['file'].'\');">'.$admin['name'].'</a><br>';
   }
  }
  $output.=$input.'</div></div>';
  if($input!='') print $output;
 }
}

?>