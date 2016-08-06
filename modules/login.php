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

if(!isset($_USER)) {
 if(isset($_GET['ajax']))
  $CORE->login(utf8_encode('Usuario y contraseña'),true);

 $THEME->opentable();
 print $HTML->table('350','','align="center"',2);
 print $HTML->font(_LOGIN,'title');
 print $HTML->form($_SETTINGS['module'],'','login','noajax');
 print $HTML->input('username','text',25,25,'','','class="username"');
 print $HTML->input('password','password',25,25,'','','class="password"');
 print $HTML->input('','submit','','',_SEND,'','class="submit"');
 print $HTML->form_close();
 print $HTML->table_close(2);
 $THEME->closetable();
} else {
 $THEME->opentable();
 print $HTML->table('500','','align="center"',2);
 print $HTML->font(_CHANGEPASSWORD,'title');
 print $HTML->form($_GET['module'],'content_center','login',$links);
 $title=array(0=>_PASSWORD,1=>_PASSNEW,2=>_PASSCONFIRM);
 for($i=0; $i<3; $i++) {
  print $HTML->table('500','','align="center"',1);
  print $HTML->td('left','center','200','subtitle','',$title[$i]);
  print $HTML->td('left','center','150','','',$HTML->input('password[]','password',25,$_TABLE['column']['size'][2],'','','class="password"'));
  print $HTML->table_close(1);
 }
 print $HTML->input('','submit','','',_SAVE,'','class="submit"');
 print $HTML->form_close();
 print $HTML->table_close(2);
 $THEME->closetable();
}

?>
