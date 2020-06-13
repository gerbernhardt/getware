<?php
/*
 * Keep It Simple, Stupid!
 * Filename: blocks/admin.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

$sqlx = 'SELECT x.id,x.name,x.maximize FROM `sys~admin~groups` AS x ORDER BY x.priority, x.name';
if($result_group = mysqli_query($_DB['session'], $sqlx)) {
  while($group = $result_group->fetch_array()) {
    $output = '<div class="block" thick="' . $group['maximize'] . '">';
    $output .= '<div class="block-header">' . $group['name'] . '</div>';
    $output .= '<div class="block-content">';
    $sql = 'SELECT x.name, x.file, x.sub_order FROM `sys~admin` AS x';
    $sql .= ' INNER JOIN `sys~admin~access` AS x0 ON x.access=x0.id AND x0.name=\'Activo\'';

    if($_MOBILE)
    $sql .= ' INNER JOIN `sys~admin~access` AS x1 ON x.mobile=x1.id AND x1.name=\'Activo\'';

    if($_USER['type'] != 1) # SI NO ES SUPER USUARIO
    $sql .= ' INNER JOIN `sys~admin~privileges` AS x2 ON x2.module=x.id AND x2.user=\'' . $_USER['id'] . '\'';

    $sql .= ' WHERE x.group=' . $group['id'] . ' ORDER BY x.sub_order, x.name';

    $input = '';
    if($result_admin = mysqli_query($_DB['session'], $sql)) {
      while($admin = $result_admin->fetch_array()) {
        
        $add = '';
        if($_MOBILE) $add = '&add';
        
        $style = '';
        if(substr($admin['sub_order'], 2) == 2) $style = ' style="position:relative; top:-2px"';
        
        //if(substr($admin['sub_order'], 2) == 1) $style = ' style="position:relative; top:-2px; button:-10px"';
        $input .= '<img src="images/menu-' . substr($admin['sub_order'], -1, 1) . '.png"' . $style .'>';
        if($admin['file'] == '')
        $input .= $add;
        else $input .= '<a href="#" onclick="javascript:getware.get(\'module=admin&amp;admin=' . $admin['file'] . '' . $add . '\');">';

        $input .= str_replace(' ', '&nbsp;', $admin['name']).'</a><br>';
      }
    }
    $output .= $input.'</div></div>';
    if($input != '') print $output;
  }
}

?>