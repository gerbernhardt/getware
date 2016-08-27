<?php
/*
 * Keep It Simple, Stupid!
 * Filename: admin/sys_groups.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../../')&&exit();

$_TABLE['name']='sys_admin_groups';
$KERNEL->table();
$_MODULE['title']='groups';
if($KERNEL->privilege('add')) {
 $_MODULE['table']='100%';
 $_MODULE['size']=array(20);
 $KERNEL->dialog->add->show();
} elseif($KERNEL->privilege('edit')) {
 $KERNEL->dialog->edit->show();
} elseif($KERNEL->privilege('remove')) {
 $KERNEL->dialog->remove->show();
}
 $_MODULE['search']['size']=array(20,10);
 $_MODULE['search']['name']=array('name','maximize');
 $_MODULE['search']['field']=array('name','maximize');
 $_MODULE['grid']['size']=array(20,10);
 $_MODULE['grid']['name']=array('name','maximize');
 $_MODULE['grid']['field']=array('name','maximize');
 $_MODULE['grid']['menu']['field']=array('edit','add','remove','save');
 $_MODULE['grid']['edit']=array('maximize');
 $KERNEL->search->autocomplete();
 $KERNEL->dialog->autocomplete();
 $KERNEL->grid->autocomplete();
 $KERNEL->grid->save();
 $KERNEL->query->make();
 $KERNEL->search->content();
 $KERNEL->grid->content();
 $KERNEL->json_print();

?>