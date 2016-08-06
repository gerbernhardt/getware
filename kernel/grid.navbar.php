<?php

#
# Getware: Ultra-Secure Script
# Filename: grid.navbar.php,2004/09/06
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();

 $rows=0;
 $start=0;
 $limit=20;
 $sql='SELECT NULL';
 $sql.=$_MODULE['query']['from'];
 $sql.=$_MODULE['query']['join'];
 $sql.=$_MODULE['query']['where'];
 $sql.=$_MODULE['query']['order'];
 if($result=mysqli_query($_DB['session'],$sql)) {
  if(isset($_GET['start']))
   $start=$_GET['start'];
  $rows=mysqli_num_rows($result);
 }

?>