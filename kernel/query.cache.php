<?php

#
# Getware: Ultra-Secure Script
# Filename: kernel/query.cache.php,2004/09/06
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();

// SAVE CACHE QUERY
$cache='DELETE FROM '.$_DB['prefix'].'sys_query';
$cache.=' WHERE (ip=\''.$_SERVER['REMOTE_ADDR'].'\'';
$cache.=' AND module=\''.$_GET['admin'].'\'';
$cache.=' AND user='.$_USER['id'];
$cache.=') OR time<'.time();
mysqli_query($_DB['session'],$cache);
# INSERT QUERY
$cache='INSERT INTO '.$_DB['prefix'].'sys_query (`user`,`sql`,`module`,`ip`,`time`)';
$cache.=' VALUES ('.$_USER['id'].',\''.base64_encode($cache).'\',\''.$_GET['admin'].'\',\''.$_SERVER['REMOTE_ADDR'].'\',\''.(time()+(3600*24)).'\')';
mysqli_query($_DB['session'],$cache);
//if(!mysqli_query($cache)) exit('no lo guardo');

?>