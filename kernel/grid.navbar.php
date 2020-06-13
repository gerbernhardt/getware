<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel/grid.navbar.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();

$rows = 0;
$start = 0;
$limit = 20;

$sql = 'SELECT COUNT(*) AS total';
$sql .= $_MODULE['grid']['navbar'];
if($result = mysqli_query($_DB['session'], $sql)) {
    if(isset($_GET['start'])) $start = $_GET['start'];
    if($fetch = $result->fetch_array(MYSQLI_ASSOC))
        $rows = $fetch['total'];
    else $rows = 0;
}

?>