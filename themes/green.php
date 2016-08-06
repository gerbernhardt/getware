<?php

#
# Getware: Ultra-Secure Script
# Filename: themes/green.php, 2010/08/14
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

class theme {

 # FUNCTION HEADER()
 function header() {
  global $CORE,$HTML,$_SETTINGS,$header,$_USER,$links;
  print $CORE->header($_SETTINGS['sitename'].' - '.$_SETTINGS['slogan']);
  print '<body>';
  print '<div id="fade">';
  print '<div id="window-dialog"></div>';
  print '<div id="window-message"></div>';
  print '<div id="window-menu" class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all"></div>';

  print $HTML->table(970,'','',1);
  $content=$_USER['username'].' [ '.$HTML->a('?logout','','title="'._EXIT.'!"',_LOGOUT,'normal').' ]';
  if(!isset($_USER)) $content=' [ <a href="#" onclick="javascript:$getware.get(\'module=login&amp;ajax\');return false;">'._LOGIN.'</a> ]';
  print $HTML->td('right','top','','','',$content);
  print $HTML->table_close(1);

  # INI TABLE LOGO
  print $HTML->table(970,'','',1);
  print $HTML->td('center','middle',300,'slogan','',$_SETTINGS['slogan']);
  print $HTML->td('center','middle','','','',$HTML->img('images/logos/'.$_SETTINGS['logo'].'.png',$_SETTINGS['sitename'].' '.$_SETTINGS['slogan']));
  print $HTML->table_close(1);
  # END TABLE LOGO

  print '<br>';
  # INI TABLE CONTENT
  print $HTML->table(900,'','',1);
  # INI CONTENT LEFT
  print '<td id="content_left" width="171" valign="top">';
  $CORE->blocks('left');
  print '<div id="progressbar"></div>';
  print '</td>';
  # INI CONTENT CENTER
  print '<td id="content_center" align="center" valign="top">';
 }

 # FUNCTION FOOTER()
 function footer() {
  global $HTML,$index,$_SETTINGS,$_TIME;
  print '</td>';
  # INI CONTENT RIGHT
  if($index==2) {
   print '<td id="content_right" width="171" valign="top">';
   $CORE->blocks('right');
   print '</td>';
  }
  print $HTML->table_close(1);
  # END TABLE CONTENT
  print '<br>';
  # INI TABLE FOOTER
  print $HTML->table(1000,'','',1);
  print $HTML->td('left','middle','50%','','','&nbsp;');
  $search=array('/{br}/','/{year}/');
  $replace=array('<br>',date('Y'));
  print $HTML->td('right','middle','50%','','',preg_replace($search,$replace,$_SETTINGS['footer']).'<br>'._PAGEGENERATION_TIME.' '.$_TIME['total'].' '._SECONDS);
  print $HTML->table_close(1);
  # END TABLE FOOTER
  print '</div>';
  print '<div id="print"></div>';
  if(!isset($_GET['ajax']))
   print '<script>$getware.data($(\'#content_center\').html());</script>';
  print '</body>';
  print '</html>';
 }

 # FUNCTION OPENTABLE()
 function opentable() {
  print '<table width="98%" class="block ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"><tr><td>';
 }

 # FUNCTION CLOSETABLE()
 function closetable() {
  print '</td></tr></table><br>';
 }
}

?>