<?php

#
# Getware: Ultra-Secure Script
# Filename: headers/login.php, 2010/08/05
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();
$_TABLE['column']['size']=array(0=>11,1=>25,2=>40,3=>1);
$_TABLE['column']['name']=array(0=>'id',1=>'username',2=>'password',3=>'privilege');
if(isset($_POST['password'])&&count($_POST['password'])==3) {
 if(md5($_POST['password'][0])==$_USER['password']) {
  if($_POST['password'][1]==$_POST['password'][2]) {
   if(strlen($_POST['password'][1])>5) {
    $sql='UPDATE '.$_DB['prefix'].'_users  AS x';
    $sql.=' SET x.password=\''.$_POST['password'][1].'\'';
    $sql.=' WHERE x.id='.$_USER['id'];
    if(mysqli_query($_DB['session'],$sql))
     $CORE->alert(_PASSCHANGED);
   } else $CORE->alert(_PASSMIN);
  } else $CORE->alert(_PASSDIFFERENT);
 } else $CORE->alert(_PASSDIFFERENT);
}
?>