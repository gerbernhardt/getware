<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel/query.cache.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();

// SAVE CACHE QUERY
$cache = 'DELETE FROM `sys~query`';
$cache .= ' WHERE (ip = \'' . $_SERVER['REMOTE_ADDR'] . '\'';
$cache .= ' AND module = \'' . $_GET['admin'] . '\'';
$cache .= ' AND user = ' . $_USER['id'];
$cache .= ') OR time < ' . time();
mysqli_query($_DB['session'], $cache);

# INSERT QUERY
$cache = 'INSERT INTO `sys~query` (`user`,`sql`,`module`,`ip`,`time`)';
$cache .= ' VALUES (';
$cache .= $_USER['id'] . ',';
$cache .= '\'' . base64_encode($sql) . '\',';
$cache .= '\'' . $_GET['admin'] . '\',';
$cache .= '\'' . $_SERVER['REMOTE_ADDR'].'\',';
$cache .= '\'' . (time() + (3600 * 24)) . '\'';
$cache .= ')';
mysqli_query($_DB['session'], $cache);

?>