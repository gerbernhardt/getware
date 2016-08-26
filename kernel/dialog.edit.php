<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel/dialog.edit.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

class kernel_dialog_edit{
 
 function save() {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE,$CORE,$KERNEL;
  mysqli_query($_DB['session'],'SET @clone=\'\'');  # @PARENT NOMBRE DEL REGISTRO PARENT='remito'
  mysqli_query($_DB['session'],'SET @clone_val=0'); # @PARENT_ID REGISTRO PADRE INSERTADO PARENT_ID=33
  include('kernel/dialog.edit.save.php');
 }
 
 function show($upload=false){
  global $_DB,$_ADMIN,$_TABLE,$_MODULE,$CORE,$KERNEL;
  if(function_exists('start')) start();
  if(isset($_GET['save'])){// si es save &edit=7
   $secure_index=intval($_GET['edit']);
  }else{                   // si es show &edit=7,14,6,18
   $CORE->secure_get('edit');$secure_index=implode(',',$_GET['edit']);
  }
  if(!isset($_MODULE['restrict'][_EDIT])) $_MODULE['restrict'][_EDIT]=true;
  $sql='SELECT NULL FROM '.$_TABLE['name'].' AS x WHERE x.id IN('.$secure_index.') AND '.$_MODULE['restrict'][_EDIT];
  if($result=mysqli_query($_DB['session'],$sql)){
   if(mysqli_num_rows($result)==count($_GET['edit'])){
    unset($result);
    $this->save();
    include('kernel/dialog.edit.show.php');
   }else $KERNEL->alert('NO TIENE PRIVILEGIOS PARA EDITAR ESTE REGISTRO!');
  }else $KERNEL->alert('ERROR EN LA CONSULTA SQL dialog.edit.php!');
 }
 
}

$KERNEL->dialog->edit=new kernel_dialog_edit;

?>