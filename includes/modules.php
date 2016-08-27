<?php
/*
 * Keep It Simple, Stupid!
 * Filename: modules.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();
$index=1;
if(file_exists('modules/'.$_GET['module'].'.php')) {
 $sql='SELECT x.file,x.access FROM sys_modules AS x WHERE x.file=\''.$_GET['module'].'\'';
 if($result=mysqli_query($_DB['session'],$sql)) {
  if($fetch=$result->fetch_array()) {
   if($CORE->access($fetch['access'])) {
    include('modules/'.$_GET['module'].'.php');
   } else {
    if($fetch['access']==1)
     $CORE->login(_MODULEUSERS);
    else $CORE->alert(_MODULEGUEST);
   }
  } else $CORE->alert(_MODULENOFOUND);
 } else $CORE->alert(_MODULENOFOUND);
} else $CORE->alert(_MODULENOFOUND);

?>