<?php
/*
 * Keep It Simple, Stupid!
 * Filename: themes/start.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

$_SETTINGS['width'] = '100%';

class theme {

 # FUNCTION HEADER()
 function header() {
  global $CORE,$_SETTINGS,$_USER;
  print $CORE->header($_SETTINGS['sitename'].' - '.$_SETTINGS['slogan']);

  print '<body>';
  print '<div id="fade">';
  print '<div id="window-dialog"></div>';
  print '<div id="window-message"></div>';
  print '<div id="window-menu" class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all"></div>';

  # INI TABLE LOGO
  print '<table width="'.$_SETTINGS['width'].'" cellspacing="0" cellpadding="0" border="0">';
  print '<tr>';

  print '<td valign="bottom" align="center">';
  print '<img title="MENU" id="menu" src="images/menu-button.png">';
  print '</td>';

  print '<td valign="middle" align="center">';
  print '<img title="'.$_SETTINGS['slogan'].'" src="images/logos/'.$_SETTINGS['logo'].'.png">';
  print '</td>';
  print '<td valign="middle" align="right" class="login">';
  if(!isset($_USER))
   print ' [ <a href="#" onclick="javascript:toggleMenu(false);getware.get(\'module=login\');return false;">'._LOGIN.'</a> ]';
  else print $_USER['username'].' [ <a title="'._EXIT.'" href="?logout">'._LOGOUT.'</a> ]';
  print '</td>';
  print '</tr>';
  print '</table>';
  # END TABLE LOGO

  print '<br>';

  # INI TABLE CONTENT
  print '<table width="'.$_SETTINGS['width'].'" cellspacing="0" cellpadding="0" border="0">';

  # INI CONTENT LEFT
  print '<td id="content_left">';
  $CORE->blocks('left');
  print '</td>';

  # INI CONTENT CENTER
  print '<td id="content_center" border="1">';
 }

 # FUNCTION FOOTER()
 function footer() {
  global $_SETTINGS,$_TIME;
  print '</td>';

  print '</tr>';
  print '</table>';
  # END TABLE CONTENT

  print '<br>';

  # INI TABLE FOOTER
  print '<table width="'.$_SETTINGS['width'].'" cellspacing="0" cellpadding="0" border="0">';
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