<?php

#
# Getware: Ultra-Secure Script
# Filename: kernel/dialog.upload.show.php,2004/09/06
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

$_MODULE['output']=$KERNEL->dialog->header(_UPLOAD);
$_MODULE['output'].='id:"'.$_GET['upload'].'",';
$_MODULE['output'].='append:"uxx",';
$_MODULE['output'].='buttom:"upload",';
$name='name:["ARCHIVO"],';
$type='type:["file"],';
$size='size:[10]}';
$_MODULE['output'].=$name.$type.$size;
$KERNEL->json_print($_MODULE['output']);
exit();

?>