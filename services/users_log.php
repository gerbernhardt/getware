<?php

#
# Getware: Ultra-Secure Script
# Filename: services/users_log.php, 2005/06/27
# Copyright (c) 2004 - 2012 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();

$uid=0;
if(isset($_USER['id'])) $uid=$_USER['id'];
if(!isset($_SERVER['HTTP_REFERER'])) $_SERVER['HTTP_REFERER']='NULL';

$_LOG['table']=$_DB['prefix'].'sys_users_log-'.$uid.'-'.$_SERVER['REMOTE_ADDR'];

$sql='CREATE TABLE IF NOT EXISTS `'.$_LOG['table'].'` (';
$sql.='`datetime` datetime DEFAULT NULL,';
$sql.='`query` text,';
$sql.='`referer` varchar(255) NOT NULL DEFAULT \'NULL\',';
$sql.='`agent` varchar(255) NOT NULL DEFAULT \'NULL\',';
$sql.='`time` int(14) NOT NULL DEFAULT \'0\'';
$sql.=') ENGINE=MyISAM DEFAULT CHARSET=utf8;';
mysqli_query($_DB['session'],$sql);

$sql='DELETE FROM `'.$_LOG['table'].'` WHERE time<'.time();
mysqli_query($_DB['session'],$sql);

$sql='INSERT INTO `'.$_LOG['table'].'` VALUES (';
$sql.='\''.date('Y-m-d H:i:s').'\',';
$sql.='\''.preg_replace('/&amp;/','&',$_SERVER['QUERY_STRING']).'\',';
$sql.='\''.$_SERVER['HTTP_REFERER'].'\',';
$sql.='\''.$_SERVER['HTTP_USER_AGENT'].'\',';
$sql.='\''.(time()+(3600*24*30)).'\'';
$sql.=')';
mysqli_query($_DB['session'],$sql);

?>