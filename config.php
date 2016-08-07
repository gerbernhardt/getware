<?php

#
# Getware: Ultra-Secure Script
# Filename: config.php, 2004/08/06
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

# PHP DISPLAY ERRORS CONFIG
ini_set('display_errors','On');
date_default_timezone_set('America/Cordoba');
setlocale(LC_ALL,'es_ES');
$links='ajax';
$_SERVER['PATH']=preg_replace('/index.php/','',$_SERVER['PHP_SELF']);

$_DB=array('host'=>'127.0.0.1','user'=>'root','pass'=>'','name'=>'','prefix'=>'');
$_PREFIX=array('folder'=>'getware_','dbname'=>'getware_');

if(preg_match('/soft-test.com.ar/',$_SERVER['SERVER_NAME'])){
 $_DB['user']='wi550972';
 $_DB['pass']='mivoLIki55';
 $_PREFIX['dbname']='wi550972_';
}

$_SITES=array(
 'getware',
 'keblar',
 'medicos',
 'consorcios',
 'gomas_gaspar',
 'novarumpharma',
 'movies'
);

for($i=0;$i<count($_SITES);$i++){
 if(preg_match('/^\/'.$_PREFIX['folder'].$_SITES[$i].'\//',$_SERVER['REQUEST_URI'])){
  # PREFIJO BASE DE DATOS
  if($_DB['name']=='')
   $_DB['name']=$_PREFIX['dbname'].$_SITES[$i];
  else $_DB['name']=$_PREFIX['dbname'].$_DB['name'];
  # PREFIJO CARPETAS
  $_SITES[$i]=$_PREFIX['folder'].$_SITES[$i];
  # COOKIE
  $_SERVER['PATH']='/'.$_SITES[$i].'/';
 }
}

$_DB['session']=mysqli_connect($_DB['host'],$_DB['user'],$_DB['pass'],$_DB['name']);
if(mysqli_connect_errno($_DB['session'])) exit(mysqli_connect_error($_DB['session']));

?>