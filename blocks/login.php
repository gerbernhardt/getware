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
$content.='<form enctype="multipart/form-data" method="post" action="?module=home" id="'.$_GET['module'].'_login_'.date('YmdHis').'">';
$content.='<input type="text" autocomplete="off" class="username_block" value="" maxlength="25" size="12" name="username" id="username">';
$content.='<br>';
$content.='<br>';
$content.='<input type="password" autocomplete="off" class="password_block" value="" maxlength="25" size="12" name="password" id="password">';
$content.='<br>';
$content.='<br>';
$content.='<input type="submit" autocomplete="off" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" value="Enviar">';
$content.='</form>';
$content.='</center>';

?>
