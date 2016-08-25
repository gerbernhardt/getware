<?php

#
# Getware: Ultra-Secure Script
# Filename: dialog.upload.php, 2012/04/03
# Copyright (c) 2010 - 2012 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

class kernel_dialog_upload{

 function save() {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE,$CORE,$KERNEL,$_USER;
  include('kernel/dialog.upload.save.php');
 }
 
 function show(){
  global $_DB,$_ADMIN,$_TABLE,$_MODULE,$CORE,$KERNEL;
  $this->save();
  include('kernel/dialog.upload.show.php');
 }

}

$KERNEL->dialog->upload=new kernel_dialog_upload;

?>