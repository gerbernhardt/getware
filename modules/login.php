<?php
/*
 * Keep It Simple, Stupid!
 * Filename: modules/login.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

if(!isset($_USER)) {
 $CORE->login('Usuario y contraseña',true);
} else {
 $THEME->opentable();

 print '<table width="500" cellspacing="0" cellpadding="0" align="center" border="0">';
 print '<tr>';
 print '<td align="center">';
 print '<font class="title">Cambiar Contraseña</font>';
 print '<form method="post" id="login">';
 
 $title=array(0=>_PASSWORD,1=>_PASSNEW,2=>_PASSCONFIRM);
 for($i=0; $i<3; $i++){
  print '<table width="500" cellspacing="0" cellpadding="0" align="center" border="0">';
  print '<tr>';
  print '<td width="200" valign="center" align="left" class="subtitle">'.$title[$i].'</td>';
  print '<td width="150" valign="center" align="left">';
  print '<input type="password" autocomplete="off" class="password" value="" maxlength="40" size="25" name="password[]" id="password[]">';
  print '</td>';
  print '</tr>';
  print '</table>';
 }
 
 print '<input type="submit" autocomplete="off" class="submit" value="Guardar" maxlength="" size="" name="" id="">';
 print '</form>';
 print '</td>';
 print '</tr>';
 print '</table>';

 $THEME->closetable();
}

?>
