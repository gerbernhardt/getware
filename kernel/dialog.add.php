<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel/dialog.add.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

class kernel_dialog_add{

 function save() {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE,$CORE,$KERNEL;
  include('kernel/dialog.add.save.php');
 }

 function show(){
  global $_DB,$_ADMIN,$_TABLE,$_MODULE,$CORE,$KERNEL;
  if(function_exists('start')) start();
  if(!$KERNEL->restrict(_ADD)) $KERNEL->alert('NO TIENE PRIVILEGIOS PARA EDITAR ESTE REGISTRO!');
  $this->save();
  include('kernel/dialog.add.show.php');
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
    global $_TABLE, $_ADMIN;
    if(!isset($_POST['window'])) exit();

    $_MODULE['output'] = '[';
    $_MODULE['output'] .= '{';
    $_MODULE['output'] .= 'run:"getware.ui.content.info.add",';
    $_MODULE['output'] .= 'window:"'.$_POST['window'].'",';

    $rows = 'rows:[';
    $data = 'data:[';
    for($i = $_ADMIN['ini']; $i < $_ADMIN['end']; $i++) {
        # GENERA LOS POST QUE NO EXISTEN
        if($this->restrict($i))
            $value = 1;
        else $value = 0;

        $rows .= '"' . $i . '"';
        $data .= '"' . $value . '"';
        if($i < $_ADMIN['end'] - 1) {
            $rows .= ',';
            $data .= ',';
        }
    }
    $rows .= '],';
    $data .= ']';

    $_MODULE['output'] .= $rows;
    $_MODULE['output'] .= $data;

    $_MODULE['output'] .= '}';
    $_MODULE['output'] .= ']';
    //exit($_MODULE['output']);
    if(preg_match('/"0"/', $_MODULE['output']))
        exit($_MODULE['output']);
    else return true;
}
 
}

$KERNEL->dialog->add=new kernel_dialog_add;

?>