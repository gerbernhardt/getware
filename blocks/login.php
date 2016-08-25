<?php

#
# Getware: Ultra-Secure Script
# Filename: blocks/login.php, 2004/08/18
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/', $_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

$content='<center>';
$url=$_SETTINGS['module'];
$id='';
$form=$_GET['module'].'_loginform_'.date('YmdHis');
$content.='<br>';
$content.=$HTML->form($url,$id,$form,'noajax').'';
$content.=$HTML->input('username','text',12,25,'','','class=\'username_block\'');
$content.='<br><br>';
$content.=$HTML->input('password','password',12,25,'','','class=\'password_block\'');
$content.='<br><br>';
$content.=$HTML->input('','submit','','',_SEND,'','class=\'ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only\'');
$content.=$HTML->form_close();
$content.='</center>';

?>
