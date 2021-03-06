<?php
/*
 * Keep It Simple, Stupid!
 * Filename: auth.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

setcookie('ajax', 'on', (time() + (3600 * 24 * 30)), $_SERVER['PATH'], '', 0);
$_USER = true;

$sql = 'DELETE FROM `sys~sessions` WHERE time <= ' . time();
mysqli_query($_DB['session'], $sql);

function login() {
    global $_DB, $_USER;
    $_TIME = time() + (3600 * 24);
    $_KEY = md5($_POST['password'] . $_TIME);
    $sql = 'SELECT x.* FROM `sys~users` AS x';
    $sql .= ' WHERE x.username = \'' . $_POST['username'] . '\'';
    $sql .= ' AND x.password = \'' . md5($_POST['password']) . '\' AND x.access = 1';
    if($result = mysqli_query($_DB['session'], $sql)) {
        if($_USER = $result->fetch_array()) {
            # DELETE OLD SESSION
            $sql = 'DELETE FROM `sys~session`';
            $sql .= ' WHERE ip = \'' . $_SERVER['REMOTE_ADDR'] . '\'';
            $sql .= ' AND user = ' . $_USER['id'];
            mysqli_query($_DB['session'], $sql);
            
            # INSERT NEW SESSION
            $sql='INSERT INTO `sys~sessions` (`user`,`key`,`time`,`ip`)';
            $sql.=' VALUES (' . $_USER['id'] . ', \'' . $_KEY . '\', \'' . $_TIME . '\', \'' . $_SERVER['REMOTE_ADDR'] . '\')';
            mysqli_query($_DB['session'], $sql);
            setcookie('session', urlencode($_KEY), time() + (3600 * 24 * 365), $_SERVER['PATH'], '', 0);
            if(isset($_GET['logout'])) unset($_GET['logout']);
            return true;
        } else return false;
    } else return false;
}

function session() {
    global $_DB, $_USER;
    $sql = 'SELECT x.* FROM `sys~sessions` AS x';
    $sql .= ' WHERE x.time > ' . time();
    $sql .= ' AND x.key = \'' . $_COOKIE['session'] . '\'';
    $sql .= ' AND ip = \'' . $_SERVER['REMOTE_ADDR'] . '\'';
    if($result = mysqli_query($_DB['session'], $sql)) {
        if($fetch = $result->fetch_array()) {
            $sql = 'SELECT x.* FROM `sys~users` AS x';
            $sql .= ' WHERE x.id = ' . $fetch['user'];
            if($result = mysqli_query($_DB['session'], $sql)) {
                if($_USER = $result->fetch_array()) {
                    return true;
                } else return false;
            } else return false;
        } else return false;
    } else return false;
}

if(isset($_COOKIE['session'])) {
    if(!session()) {
        setcookie('session', false, 0, $_SERVER['PATH'], '', 0);
        unset($_USER);
    }
}

if(isset($_POST['username']) && isset($_POST['password'])) {
    if(!login()) {
        setcookie('session', false, 0,  $_SERVER['PATH'], '', 0);
        unset($_USER);
    } elseif(isset($_GET['module'])&&$_GET['module']!='admin') {
        exit('<script>document.location=\'./\';</script>');
    }
}

if(isset($_GET['logout']) && isset($_USER['id'])) {
    setcookie('session', false, 0, $_SERVER['PATH'], '', 0);
    $sql = 'DELETE FROM `sys~sessions`';
    $sql .= ' WHERE ip = \'' . $_SERVER['REMOTE_ADDR'] . '\'';
    $sql .= ' AND user = ' . $_USER['id'];
    mysqli_query($_DB['session'], $sql);
    unset($_USER);
}

if(!isset($_USER['id'])) {
    unset($_USER);
}else{
    mysqli_query($_DB['session'], 'SET @user = ' . $_USER['id']);
    mysqli_query($_DB['session'], 'SET @userLog = 1');
}

?>