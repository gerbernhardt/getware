<?php

#
# Getware: Ultra-Secure Script
# Filename: dialog.remove.php, 2012/04/03
# Copyright (c) 2010 - 2012 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

class kernel_dialog_remove{
 
 function save(){
  global $_DB,$_ADMIN,$_TABLE,$_MODULE,$CORE,$KERNEL;
  if(function_exists('start')) start();
  if(isset($_GET['remove'])) {
   if(!is_array($_GET['remove'])) $CORE->secure_get('remove');
   //$KERNEL->alert('Desea eliminar '.count($_GET['remove']).' registro/s?');
   $sql='DELETE x.* FROM '.$_TABLE['name'].' AS x WHERE x.id IN('.implode(',',$_GET['remove']).')';
   if(mysqli_query($_DB['session'],$sql)){
    $_MODULE['next']=true;
    $KERNEL->alert('Los registros han sido eliminados');
   }else $KERNEL->alert(mysqli_error());

  }
 }
 
 function show(){
  global $_DB,$_ADMIN,$_TABLE,$_MODULE,$CORE,$KERNEL;
  $CORE->secure_get('remove');$secure_index=implode(',',$_GET['remove']);
  if(!isset($_MODULE['restrict'][_REMOVE])) $_MODULE['restrict'][_REMOVE]=true;
  $sql='SELECT NULL FROM '.$_TABLE['name'].' AS x WHERE x.id IN('.$secure_index.') AND '.$_MODULE['restrict'][_REMOVE];
  if($result=mysqli_query($_DB['session'],$sql)){
   if(mysqli_num_rows($result)==count($_GET['remove'])){
    unset($result);
    $this->save();
   }else $KERNEL->alert('NO TIENE PRIVILEGIOS PARA ELIMINAR ESTE REGISTRO!');
  }else $KERNEL->alert('ERROR EN LA CONSULTA SQL dialog.remove.php!');
 }

}

$KERNEL->dialog->remove=new kernel_dialog_remove;

?>