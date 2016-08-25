<?php

#
# Getware: Ultra-Secure Script
# Filename: themes/start.php, 2010/08/14
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

  print '<table width="970" cellspacing="0" cellpadding="0" border="0">';
  print '<tr>';
  print '<td valign="top" align="right">';
  if(!isset($_USER))
   print ' [ <a href="#" onclick="javascript:getware.get(\'module=login&amp;ajax\');return false;">'._LOGIN.'</a> ]';
  else print $_USER['username'].' [ <a title="'._EXIT.'" href="?logout">'._LOGOUT.'</a> ]';
  print '</td>';
  print '</tr>';
  print '</table>';

  # INI TABLE LOGO
  print '<table width="970" cellspacing="0" cellpadding="0" border="0">';
  print '<tr>';
  print '<td width="300" valign="middle" align="center" class="slogan">';
  print $_SETTINGS['slogan'];
  print '</td>';
  print '<td valign="middle" align="center">';
  print '<img title="'.$_SETTINGS['slogan'].'" src="images/logos/'.$_SETTINGS['logo'].'.png">';
  print '</td>';
  print '</tr>';
  print '</table>';
  # END TABLE LOGO

  print '<br>';
  
  # INI TABLE CONTENT
  print '<table width="900" cellspacing="0" cellpadding="0" border="0">';
  
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
  print '</tr>';
  print '</table>';
  # END TABLE CONTENT
  
  print '<br>';

  # INI TABLE FOOTER
  print '<table width="1000" cellspacing="0" cellpadding="0" border="0">';
  print '<tr>';
  print '<td width="50%" valign="middle" align="left">&nbsp;</td>';
  print '<td width="50%" valign="middle" align="right">';
  $search=array('/{br}/','/{year}/','/{/','/}/');
  $replace=array('<br>',date('Y'),'<','>');
  print preg_replace($search,$replace,$_SETTINGS['footer']);
  print '<br>';
  print _PAGEGENERATION_TIME.' '.$_TIME['total'].' '._SECONDS;  
  print '</td>';
  print '</tr>';
  print '</table>';
  # END TABLE FOOTER
  
  print '<div id="print"></div>';
  if(!isset($_GET['ajax']))
   print '<script>getware.data($(\'#content_center\').html());</script>';
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