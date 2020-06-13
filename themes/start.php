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
  $_SETTINGS['width'] = $_SETTINGS['content_left'] + $_SETTINGS['content_center'];
  print '<table width="' . $_SETTINGS['width'] . '" cellspacing="0" cellpadding="0" border="0">';
  print '<tr>';
  print '<td valign="top" align="right" style="padding-right: 10px;">';
  if(!isset($_USER))
   print ' [ <a href="#" onclick="javascript:getware.get(\'module=login&amp;ajax\');return false;">'._LOGIN.'</a> ]';
  else print '<a href="#" onclick="javascript:getware.get(\'module=login&amp;ajax\');return false;">'.$_USER['username'].'</a> [ <a title="'._EXIT.'" href="?logout">'._LOGOUT.'</a> ]';
  print '</td>';
  print '</tr>';
  print '</table>';

  # INI TABLE LOGO
  print '<table width="'.$_SETTINGS['width'].'" cellspacing="0" cellpadding="0" border="0">';
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
  print '<table id="main_center" width="' . $_SETTINGS['width'] . '" cellspacing="0" cellpadding="0" border="0">';

  # INI CONTENT LEFT
  print '<td id="content_left" width="' . $_SETTINGS['content_left'] . '" valign="top">';
  $CORE->blocks('left');
  print '<div id="progressbar"></div>';
  ?>
<script>


</script>
  <?php
  print '</td>';

  # INI CONTENT CENTER
  print '<td id="content_center" width="' . $_SETTINGS['content_center'] . '" align="center" valign="top">';
 }

 # FUNCTION FOOTER()
 function footer() {
  global $_SETTINGS,$_TIME;
  print '</td>';

  # INI CONTENT RIGHT
  if(isset($index)) {
   print '<td id="content_right" width="171" valign="top">';
   $CORE->blocks('right');
   print '</td>';
  }
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