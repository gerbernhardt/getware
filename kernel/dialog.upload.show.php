<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel/dialog.upload.show.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

$_MODULE['output'] = $KERNEL->dialog->header(_UPLOAD);
$_MODULE['output'] .= 'id:"'.$_GET['upload'].'",';
$_MODULE['output'] .= 'append:"uxx",';
$_MODULE['output'] .= 'buttom:"upload",';
$_MODULE['output'] .= 'name:["ARCHIVO"],';
$_MODULE['output'] .= 'type:["file"],';
$_MODULE['output'] .= 'accept:["' . $accept . '"],';
$_MODULE['output'] .= 'size:[300]}';

$KERNEL->json_print($_MODULE['output']);
exit();

?>