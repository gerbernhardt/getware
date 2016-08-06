<?php

#
# Getware: Ultra-Secure Script
# Filename: demons.php, 2005/06/27
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

$sql='SELECT x.file FROM '.$_DB['prefix'].'sys_demons AS x WHERE x.access=1';
$result=mysqli_query($_DB['session'],$sql);
while($fetch=$result->fetch_array())
 if(file_exists('demons/'.$fetch['file'].'.php'))
  include('demons/'.$fetch['file'].'.php');

?>