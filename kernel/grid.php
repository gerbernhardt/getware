<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel/grid.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

class kernel_grid {
  function module_vars() {
    global $_MODULE, $_GRID;
    $i = 0;
    foreach($_GRID as $key => $value) {
      $key = preg_replace('/\./', '', $key);
      if(is_array($value)) {
        $_MODULE['grid']['size'][$i] = $value[0];
        $_MODULE['grid']['name'][$i] = $value[1];
      } else {
        $_MODULE['grid']['size'][$i] = $value;
        $_MODULE['grid']['name'][$i] = $key;
      }
      $_MODULE['grid']['field'][$i] = $key;
      $i++;
    }
  }
  
  function autocomplete() {
    global $_DB,$_ADMIN,$_TABLE,$_MODULE,$CORE,$KERNEL;
      
    if(!isset($_MODULE['grid']['name']))
      $this->module_vars();

    if(isset($_MODULE['grid']['edit']) && isset($_GET['row']) && isset($_GET['term'])) {
      include('kernel/grid.autocomplete.php');
    }
  }

  function make_array($x) {
    $j = array();
    for($i = 0; $i < count($x); $i++) $j[$x[$i]] = true;
    return $j;
  }

 # MENU GENERATOR
 function menu() {
  global $_ADMIN,$_TABLE,$_MODULE,$CORE;
  if(!isset($_MODULE['grid']['menu'])) return false;
  include('kernel/grid.menu.php');
  return '{cmd:['.$cmd.'],data:['.$data.'],img:['.$img.'],type:['.$type.'],blank:['.$blank.'],module:['.$module.']}';
 }

 # NAVPAGE GENERATOR
 function navbar() {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE,$CORE;
  if(!isset($_MODULE['grid']['menu'])) return false;
  include('kernel/grid.navbar.php');
  return htmlspecialchars('{rows:'.$rows.',start:'.$start.',limit:'.$limit.'}');
 }

 # GRID BODY
 function content() {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE,$sql,$KERNEL,$CORE;

  if(!isset($_MODULE['grid']['name'])) $this->module_vars();
  
  include('kernel/grid.content.php');
 }

 # GRID BODY DROPABLE
 function content_dropable() {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE,$sql,$CORE;
  include('kernel/grid.content.dropable.php');
 }

 function save() {
  global $_DB,$CORE,$_ADMIN,$_MODULE,$_TABLE,$KERNEL,$CORE;
  include('kernel/grid.edit.save.php');
 }

}

$KERNEL->grid=new kernel_grid;

?>