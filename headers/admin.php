<?php

#
# Getware: Ultra-Secure Script
# Filename: headers/admin.php, 2004/08/06
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

if(!isset($_ADMIN['path']))
 $_ADMIN['path']='..'.$_SERVER['PATH'].'admin/';

include('./kernel.php');

# NO INCLUYE EL MODULO
$_ADMIN['file']=false;

# TODOS LO PRIVILEGIOS SON FALSE
$sql='SELECT x.name FROM '.$_DB['prefix'].'sys_admin_privileges_types AS x';
if($result=mysqli_query($_DB['session'],$sql)){
 while($fetch=$result->fetch_array()){
  if($_USER['type']==1)
   $_ADMIN[$fetch['name']]=true;
  else $_ADMIN[$fetch['name']]=false;
 }
} else $KERNEL->alert(mysql_error($_DB['session']));

if(isset($_GET['admin'])&&file_exists($_ADMIN['path'].$_GET['admin'].'.php')) {
 # VERIFICA QUE EL MODULO SE ENCUENTRE ACTIVO
 $sql='SELECT x.id FROM '.$_DB['prefix'].'sys_admin AS x WHERE x.file=\''.$_GET['admin'].'\' AND x.access=1';
 if($result=mysqli_query($_DB['session'],$sql)){
  if($fetch=$result->fetch_array()){
   $_ADMIN['file']=true;

   # INI NORMAL USER
   if($_USER['type']!=1){
    # REVISA LA TABLA DE PRIVILEGIOS
    $sql='SELECT x.* FROM '.$_DB['prefix'].'sys_admin_privileges AS x WHERE x.user='.$_USER['id'].' AND x.module=\''.$fetch['id'].'\'';
    if($result=mysqli_query($_DB['session'],$sql)){
     if($fetch=$result->fetch_array()){
      $_ADMIN['file']=true;//TIENE ACCESO AL MODULO
      $sql='SELECT DISTINCT x1.name FROM '.$_DB['prefix'].'sys_admin_privileges AS x';
      $sql.=' INNER JOIN '.$_DB['prefix'].'sys_admin_privileges_types_data AS x0 ON x0.privilege=x.id';
      $sql.=' INNER JOIN '.$_DB['prefix'].'sys_admin_privileges_types AS x1 ON x0.type=x1.id';
      $sql.=' WHERE x.user='.$_USER['id'].' AND x.module='.$fetch['module'];
      if($result=mysqli_query($_DB['session'],$sql)){
       while($fetch=$result->fetch_array()){
        $_ADMIN[$fetch['name']]=true;
       }
      } else $KERNEL->alert($sql);
     } else $KERNEL->alert(_ACCESSDENIED);
    } else $KERNEL->alert(mysql_error($_DB['session']));
   }
   # END NORMAL USER

  } else $KERNEL->alert(_MODULEINACTIVE);
 } else $KERNEL->alert(mysql_error($_DB['session']));
}
# INCLUYE LA FUNCIONES DEL MODULO
if($_ADMIN['file']==true&&file_exists($_ADMIN['path'].'functions/'.$_GET['admin'].'.php'))
 include($_ADMIN['path'].'functions/'.$_GET['admin'].'.php');
?>