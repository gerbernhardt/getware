<?php

#
# Getware: Ultra-Secure Script
# Filename: grid.php, 2012/04/03
# Copyright (c) 2010 - 2012 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

class kernel_grid{
 function autocomplete() {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE,$CORE,$KERNEL;
  if(isset($_MODULE['grid']['edit'])&&isset($_GET['row'])&&isset($_GET['term'])) {
   $path='kernel/grid.';$files=array('autocomplete');eval($CORE->include_enc());
  }
 }
 #
 function make_array($x) {
  $c=array();
  for($i=0;$i<count($x);$i++) $c[$x[$i]]=true;
  return $c;
 }
 # MENU GENERATOR
 function menu() {
  global $_ADMIN,$_TABLE,$_MODULE,$CORE;
  if(!isset($_MODULE['grid']['menu'])) return false;
  $path='kernel/grid.';$files=array('menu');eval($CORE->include_enc());
  return '"'.htmlspecialchars('{cmd:['.$cmd.'],data:['.$data.'],img:['.$img.'],type:['.$type.'],blank:['.$blank.'],module:['.$module.']}').'"';
 }
 # NAVPAGE GENERATOR
 function navbar() {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE,$CORE;
  if(!isset($_MODULE['grid']['menu'])) return false;
  $path='kernel/grid.';$files=array('navbar');eval($CORE->include_enc());
  return htmlspecialchars('{rows:'.$rows.',start:'.$start.',limit:'.$limit.'}');
 }
 # GRID BODY
 function content() {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE,$sql,$KERNEL,$CORE;$path='kernel/grid.';$files=array('content');eval($CORE->include_enc());
 }
 # GRID BODY DROPABLE
 function content_dropable() {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE,$sql,$CORE;$path='kernel/grid.';$files=array('content.dropable');eval($CORE->include_enc());
 }
 function save() {
  global $_DB,$CORE,$_ADMIN,$_MODULE,$_TABLE,$KERNEL,$CORE;$path='kernel/grid.';$files=array('edit.save');eval($CORE->include_enc());
 }

}
$KERNEL->grid=new kernel_grid;
?>