<?php
/*
 * Keep It Simple, Stupid!
 * Filename: modules/admin.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

if($_ADMIN['file']==true&&file_exists($_ADMIN['path'].$_GET['admin'].'.php')){
    include($_ADMIN['path'].$_GET['admin'].'.php');
} else {
    $CORE->alert(_ACCESSDENIED);
}

?>