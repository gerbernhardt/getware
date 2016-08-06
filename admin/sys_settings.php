<?php
#
# Getware: Ultra-Secure Script
# Filename: admin/system/sys_settings.php, 2011/06/09
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../../')&&exit();

$_TABLE['name']='sys_settings';
$KERNEL->table();
$_MODULE['title']='settings';
if($KERNEL->privilege('edit')) {
 $KERNEL->dialog->edit->show();
}
 $_MODULE['grid']['size']=array(10,10,10,10,10,10,10,10,10,10,10);
 $_MODULE['grid']['name']=array('sitename','keywords','url','slogan','email','logo','date','language','theme','footer','module');
 $_MODULE['grid']['field']=array('sitename','keywords','url','slogan','email','logo','date','language','theme','footer','module');
 $_MODULE['grid']['menu']['field']=array('edit');
 $KERNEL->dialog->autocomplete();
 $KERNEL->query->make();
 $KERNEL->grid->content();
 $KERNEL->json_print();

?>