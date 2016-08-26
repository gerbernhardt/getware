<?php
/*
 * Keep It Simple, Stupid!
 * Filename: security.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

# COVIERTE LA VARIABLES PRINCIPALES EN NUMEROS ENTEROS
$index=array('id','print','make');
$simple=array('remove'=>true);
for($i=0;$i<count($index);$i++) {
 # SI LA VARIABLE NO ES UN ARRAY EJ: ID[]=1&ID[]=2
 if(isset($_GET[$index[$i]])&&!is_array($_GET[$index[$i]])) {
  $_GET[$index[$i]]=explode(',',$_GET[$index[$i]]);
  for($j=0;$j<count($_GET[$index[$i]]);$j++)
   $_GET[$index[$i]][$j]=intval($_GET[$index[$i]][$j]);
  if(count($_GET[$index[$i]])==1||isset($simple[$index[$i]]))
   $_GET[$index[$i]]=$_GET[$index[$i]][0];
 }
}

function specialchars($x) {
 return htmlspecialchars($x,ENT_QUOTES);
}

# CONVIERTE TODOS LOS CARACTERES
# A HTML MENOS LOS ESPACIOS.-
function encodeHTML($x) {
 foreach($x as $i=>$j) {
  if(is_array($j))
   $x[$i]=encodeHTML($j);
  else $x[$i]=htmlspecialchars($j,ENT_QUOTES);
 }
 return $x;
}

$_GET=encodeHTML($_GET);
$_POST=encodeHTML($_POST);
$_SERVER=encodeHTML($_SERVER);
$_COOKIE=encodeHTML($_COOKIE);


?>