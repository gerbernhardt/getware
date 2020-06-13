<?php
/*
 * Keep It Simple, Stupid!
 * Filename: headers.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

if(!isset($_GET['module']))
  $_GET['module'] = $_SETTINGS['module'];

$sql = 'SELECT x.file, x.access FROM `sys~modules` AS x WHERE x.file = \'' . $_GET['module'] . '\'';
if($result = mysqli_query($_DB['session'], $sql)) {
  if($fetch = $result->fetch_array()) {
    if($CORE->access($fetch['access'])) {
      if(file_exists('headers/' . $_GET['module'].'.php'))
        include('headers/' . $_GET['module'] . '.php');
    }
  }
}

if(isset($_GET['ajax'])) {
  include('modules.php');
  exit();
}

?>