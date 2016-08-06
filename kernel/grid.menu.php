<?php

#
# Getware: Ultra-Secure Script
# Filename: grid.menu.php,2004/09/06
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();

$cmd='';
$data='';
$img='';
$type='';  # true ENVIA LOS ID'S DEL GRID
$blank=''; # true EJECUTA GETOPEN()
$module=''; # ENVIA LA INFORMACION A UN MODULO DIFERENTE
for($i=0;$i<count($_MODULE['grid']['menu']['field']);$i++) {
 $options=$_MODULE['grid']['menu']['field'][$i];
  # si es un comando clon 'certificar#mo','certificar#ma' //modulo keblar contratistas_contratos
  if(preg_match('/#/',$options)){   $explode=explode('#',$options);   $cmd.='"'.$explode[0].'"';
  }else $cmd.='"'.$options.'"';
  # SI EXISTE EL TITLE
  if(isset($_MODULE['grid']['menu']['title'][$options]))
   $data.='"'.$_MODULE['grid']['menu']['title'][$options].'"';
  else $data.='"'.constant('_'.strtoupper($options)).'"';
  # SI EXISTE EL IMAGE
  if(isset($_MODULE['grid']['menu']['image'][$options]))
   $img.='"'.$_MODULE['grid']['menu']['image'][$options].'"';
  else $img.='"'.$options.'"';
  # SI EXISTE EL TYPE
  if(isset($_MODULE['grid']['menu']['type'][$options]))
   $type.='true';
  else $type.='false';
  # SI EXISTE EL BLANK
  if(isset($_MODULE['grid']['menu']['blank'][$options]))
   $blank.='true';
  else $blank.='false';

  # SI EXISTE EL MODULO
  if(isset($_MODULE['grid']['menu']['module'][$options]))
   $module.='"'.$_MODULE['grid']['menu']['module'][$options].'"';
  else $module.='false';

  if($i<count($_MODULE['grid']['menu']['field'])-1) {
   $cmd.=',';$data.=',';$img.=',';$type.=',';$blank.=',';$module.=',';
  }

}

?>