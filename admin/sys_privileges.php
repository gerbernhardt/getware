<?php
#
# Getware: Ultra-Secure Script
# Filename: admin/system/sys_privileges.php, 2011/06/09
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../../')&&exit();

$_TABLE['name']='sys_admin_privileges';
$KERNEL->table();
$_MODULE['title']='privileges';
if($KERNEL->privilege('add')) {
 $_MODULE['table']='100%';
 $_MODULE['width']='750';
 $_MODULE['size']=array(15,15,10,10,10,10,10);
 $KERNEL->dialog->axx->show();
} elseif($KERNEL->privilege('edit')) {
 $KERNEL->dialog->edit->show();
}
 $_MODULE['search']['size']=array(20,20);
 $_MODULE['search']['name']=array('user','module');
 $_MODULE['search']['field']=array('user','module');
 $_MODULE['grid']['size']=array(20,20,40);
 $_MODULE['grid']['name']=array('user','module','privileges');
 $_MODULE['grid']['field']=array('user','module','privileges');
 $_MODULE['grid']['menu']['field']=array('edit','add','remove');
 $KERNEL->search->autocomplete();
 $KERNEL->dialog->autocomplete();
 $KERNEL->query->make();
 $KERNEL->search->content();
 $KERNEL->grid->content();
 $KERNEL->json_print();

?>