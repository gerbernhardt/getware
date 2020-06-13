<?php
/*
 * Keep It Simple, Stupid!
 * Filename: blocks/home.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

$content = $dot.'<a href="index.php">' . _HOME . '</a><br>';
$sql = 'SELECT x.* FROM `sys~modules` AS x ORDER BY x.id';
if($result = mysqli_query($_DB['session'], $sql)) {
  while($fetch = $result->fetch_array()) {
  if($fetch['shadow'] > 0 && $fetch['access'] > 0)
    $content .= $dot . '<a href="#" onclick="javascript:getware.get(\'module=' . $fetch['file'] . '\');">Login</a><br>';
  }
}

?>