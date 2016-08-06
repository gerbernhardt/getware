<?php

#
# Getware: Ultra-Secure Script
# Filename: cache.php,2004/09/06
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();

// SAVE CACHE QUERY
$cache_sql='DELETE FROM '.$_DB['prefix'].'sys_query';
$cache_sql.=' WHERE (ip=\''.$_SERVER['REMOTE_ADDR'].'\'';
$cache_sql.=' AND module=\''.$_GET['admin'].'\'';
$cache_sql.=' AND user='.$_USER['id'];
$cache_sql.=') OR time<'.time();
mysqli_query($_DB['session'],$cache_sql);
# INSERT QUERY
$cache_sql='INSERT INTO '.$_DB['prefix'].'sys_query (`user`,`sql`,`module`,`ip`,`time`)';
$cache_sql.=' VALUES ('.$_USER['id'].',\''.base64_encode($sql).'\',\''.$_GET['admin'].'\',\''.$_SERVER['REMOTE_ADDR'].'\',\''.(time()+(3600*24)).'\')';
mysqli_query($_DB['session'],$cache_sql);
//if(!mysqli_query($cache_sql)) exit('no lo guardo');

?>