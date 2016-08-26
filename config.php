<?php
/*
 * Keep It Simple, Stupid!
 * Filename: config.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

# PHP DISPLAY ERRORS CONFIG
ini_set('display_errors','On');
date_default_timezone_set('America/Cordoba');
setlocale(LC_ALL,'es_ES');

$_SERVER['PATH']='/getware/';
$_DB=array('host'=>'127.0.0.1','user'=>'root','pass'=>'','name'=>'getware');

$_DB['session']=mysqli_connect($_DB['host'],$_DB['user'],$_DB['pass'],$_DB['name']);

if(mysqli_connect_errno($_DB['session']))
 exit(mysqli_connect_error($_DB['session']));

?>