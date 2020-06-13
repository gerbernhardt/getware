<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel/dialog.upload.save.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

if(!is_dir('..'.$_SERVER['PATH'] . 'uploads/' . $_GET['admin']))
  mkdir('..' . $_SERVER['PATH'] . 'uploads/' . $_GET['admin']);

$sync = 'No se envio ningun archivo!';

foreach($_FILES as $name => $array) {
  if(isset($_FILES[$name]['name']) && $_FILES[$name]['name'] != '') {
    ini_set('memory_limit','22M');
    set_time_limit(120);

    if(is_uploaded_file($_FILES[$name]['tmp_name'])) {
      // MOVE TO UPLOAD DIRECTORY
      $file = '..' . $_SERVER['PATH'] . 'uploads/' . $_GET['admin'] . '/' . date('Y-m-d-H.i.s') . '_' . $_USER['id'] . '_' . $_FILES[$name]['name'];
      if(move_uploaded_file($_FILES[$name]['tmp_name'], $file)) {
        // READ CONTENT OF FILE
        if(eval_upload_file($file))
          $sync = 'El archivo a sido subido exitosamente!';
        else $sync='Error al interpetar el archivo!';
      } else $sync='Error al guardar el archivo!';
    } else $sync='Error al subir el archivo!';
  } else $sync='Archivo invalido!';
}

if(isset($_GET['reference']) && isset($_GET['save'])){
  $KERNEL->alert($sync, $_GET['reference']);
}

?>