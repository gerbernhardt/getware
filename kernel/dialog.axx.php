<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel/dialog.axx.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

class kernel_dialog_axx{

 function save() {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE,$CORE,$KERNEL;
  include('kernel/dialog.axx.save.php');
 }

 function show(){
  global $_DB,$_ADMIN,$_TABLE,$_MODULE,$CORE,$KERNEL;
  if(function_exists('start')) start();
  $this->save();
  include('kernel/dialog.axx.show.php');
 }

}

$KERNEL->dialog->axx=new kernel_dialog_axx;
include('kernel/dialog.axx.check.php');

?>