<?php
#
# Getware: Ultra-Secure Script
# Filename: admin/system/sys_admin.php, 2011/06/09
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../../')&&exit();

$_TABLE['name']='sys_admin_claims';
$KERNEL->table();
$_MODULE['title']='modules admin claims';
if($KERNEL->privilege('add')) {
 $KERNEL->dialog->add->show();
} elseif($KERNEL->privilege('edit')) {
 $KERNEL->dialog->edit->show();
} elseif($KERNEL->privilege('remove')) {
 $KERNEL->dialog->remove->show();
}
 $_MODULE['search']['size']=array(20,40);
 $_MODULE['search']['name']=array('module','description');
 $_MODULE['search']['field']=array('module','description');
 $_MODULE['grid']['size']=array(20,40);
 $_MODULE['grid']['name']=array('module','description');
 $_MODULE['grid']['field']=array('module','description');
 $_MODULE['grid']['menu']['field']=array('edit','add');
 $KERNEL->search->autocomplete();
 $KERNEL->dialog->autocomplete();
 $KERNEL->query->make();
 $KERNEL->search->content();
 $KERNEL->grid->content();
 $KERNEL->json_print();

?>