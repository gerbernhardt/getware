<?php
/*
 * Keep It Simple, Stupid!
 * Filename: index.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */

# INIT TIMER COUNT
$_TIME['init']=explode(' ',microtime());
$_TIME['init']=$_TIME['init'][0]+$_TIME['init'][1];

# INCLUDE DATABASE CONFIGURATION
include('config.php');

# INCLUDE SECURITY OPTIONS
include('security.php');

# AUTH FOR USERS
include('auth.php');

# SELECT WEBPAGE SETTINGS
$sql='SELECT x.* FROM sys_settings AS x ORDER BY x.id DESC LIMIT 1';
if($result=mysqli_query($_DB['session'],$sql))
 $_SETTINGS=$result->fetch_array();
else exit(mysqli_error($_DB['session']));

# SELECT LANGUAGE
include($_SETTINGS['language'].'.php');

# SELECT THEME
include('themes/'.$_SETTINGS['theme'].'.php');
$THEME=new theme;

# INCLUDE GENERAL FUNCTIONS
include('core.php');
$CORE=new core;

# RUN SERVICES
include('services.php');

# SELECT HEADER MODULE
include('headers.php');

# HEADER
$THEME->header();

# SELECT MAIN MODULE
include('modules.php');

# END TIMER COUNT
$_TIME['end']=explode(' ',microtime());
$_TIME['end']=$_TIME['end'][0]+$_TIME['end'][1];
$_TIME['total']=substr($_TIME['end']-$_TIME['init'],0,5);

# FOOTER
$THEME->footer();

# CLOSE DATABASE CONNECTION
mysqli_close($_DB['session']);

?>
