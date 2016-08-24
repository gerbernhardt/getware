<?php

#
# Getware: Ultra-Secure Script
# Filename: dialog.add.php, 2012/04/03
# Copyright (c) 2010 - 2012 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

class kernel_dialog_add{
 function save() {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE,$CORE,$KERNEL;
  $path='kernel/dialog.add.';$files=array('save');eval($CORE->include_enc());
 }
 function show(){
  global $_DB,$_ADMIN,$_TABLE,$_MODULE,$CORE,$KERNEL;
  if(function_exists('start')) start();
  if(!$KERNEL->restrict(_ADD)) $KERNEL->alert('NO TIENE PRIVILEGIOS PARA EDITAR ESTE REGISTRO!');
  $this->save();
  $path='kernel/dialog.add.';$files=array('show');eval($CORE->include_enc());
 }

 function restrict($i) {
  global $_TABLE,$_MODULE;
  # "fecha "!="      "0000-00-00"
  $op='==';
  if(isset($_MODULE['restrict'][_OPERATOR][_ADD][$_TABLE['column']['name'][$i]]))
   $op=$_MODULE['restrict'][_OPERATOR][_ADD][$_TABLE['column']['name'][$i]];
  if(isset($_MODULE['restrict'][_ADD][$_TABLE['column']['name'][$i]])) {
   $data=$_MODULE['restrict'][_ADD][$_TABLE['column']['name'][$i]];
   $e='if($data'.$op.'$_POST[$i])';
   $e.=' return true;';
   $e.='else return false;';
   return eval($e);
  } else return true;
 }
 #
 # ENVIA EL JSON CON LOS DATOS INCORRECTOS
 #
 function info() {
  global $_TABLE,$_ADMIN;
  if(!isset($_POST['window'])) exit();
  $_MODULE['output']='[{run:"getware.ui.content.info.add",window:"'.$_POST['window'].'",';
  $rows='';
  $data='';
  for($i=$_ADMIN['ini'];$i<$_ADMIN['end'];$i++) {
   # GENERA LOS POST QUE NO EXISTEN
   if($this->restrict($i))
    $value=1;
   else $value=0;
   $rows.='"'.$i.'"';$data.='"'.$value.'"';
   if($i<$_ADMIN['end']-1) {
    $rows.=',';$data.=',';
   }
  }
  $_MODULE['output'].='rows:['.$rows.'],data:['.$data.']}]';
  if(preg_match('/"0"/',$_MODULE['output']))
   exit($_MODULE['output']);
  else return true;
 }
}
$KERNEL->dialog->add=new kernel_dialog_add;
?>