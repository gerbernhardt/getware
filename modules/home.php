<?php

#
# Getware: Ultra-Secure Script
# Filename: modules/login.php, 2010/08/05
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

$THEME->opentable();
print '<div align="center" valign="top"><img src="images/home.png"></div>';
$THEME->closetable();

?>