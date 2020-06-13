<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel/dialog.axx.save.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();
if($_GET['add'] == 'save') {
  
  if(!isset($_ADMIN['head'])) $_ADMIN['head'] = $_ADMIN['ini'];
  if(count($_POST) < $_ADMIN['end']) $KERNEL->alert('DATOS ENVIADOS INSUFICIENTES!');
  
  $before = 0;
  if(function_exists('dialog_before_add')) $before = dialog_before_add();
  if($this->check->info()) { # CONTROL INTERNO ANTES GUARDAR
    if(function_exists('axx_check')) axx_check();
    
    $sql = 'INSERT INTO `' . $_TABLE['name'] . '` (';
    for($i = $_ADMIN['ini']; $i < $_ADMIN['end']; $i++) {
      $sql .= '`' . $_TABLE['column']['name'][$i] . '`';
      if($i < $_ADMIN['end'] - 1) $sql .= ',';
    }
    $sql .= ') VALUES ';
    
    if(!isset($_POST[$_ADMIN['head']])) $KERNEL->alert('PARAMETROS INVALIDOS!');

    $count = count($_POST[$_ADMIN['head']]);
    for($j = 0; $j < $count; $j++) {
      $sql.='(';

      for($i = $_ADMIN['ini']; $i < $_ADMIN['end']; $i++) {

        # ESTA RUTINA SE ENCUENTRA EN SAVE_AXX_INFO()
        if(!isset($_POST[$i][$j])) {
          if($i < $_ADMIN['head'] && isset($_POST[$i][$j - 1]))
            $_POST[$i][$j] = $_POST[$i][$j - 1];
          else $_POST[$i][$j] = '';
        }

        if($_TABLE['column']['function'][$i] == 'REFERENCE') {
          #
          # MULTIPLEX  SELECT
          #
          $count_c = count($_TABLE['column']['comment'][$i]);
          $comment =  $_TABLE['column']['comment'][$i];
          $separator = $_TABLE['column']['separator'][$i];

          $field = 'CONCAT(';
          for($k = 1; $k < $count_c; $k++) {
            // SUB REGISTROS
            if(preg_match('/\[*\]/', $comment[$k])) {
              $reg = preg_replace('#(.*?)\[.*#si','\1', $comment[$k]);
              $subreg = explode(':', preg_replace('#.*\[(.*?)\]#si','\1', $comment[$k]));
              $field .= '(SELECT xx.' . $subreg[1] . ' FROM `' . $subreg[0] . '` AS xx WHERE x.' . $reg . '=xx.id)';
            } else $field .= 'x.' . $comment[$k];

            if($separator[$k] != '')
              $field .= ',\'' . $separator[$k] . '\'';

            if($k < $count_c - 1) $field .= ',';
          }

          $field .= ')';
          
          $sql .= '(SELECT x.id FROM `' . $comment[0] . '` AS x WHERE ' . $field . '=\'' . $_POST[$i][$j] . '\' LIMIT 1)';

        } elseif($_TABLE['column']['function'][$i] == 'REFERENCES') {
          #
          # MULTIPLEX  SELECT DIMENSIONAL
          #
          $sql .= 0;
        } else $sql.='\'' . $_POST[$i][$j] . '\'';

        if($i < $_ADMIN['end'] - 1) $sql.=',';
      }

      $sql .= ')';
      if($j < $count - 1) $sql .= ',';
    }

    # INSERT INTO TABLE VAUES(AA,BB),(YY,ZZ);
    if($result = mysqli_query($_DB['session'], $sql)) {
      $ids = [];
      $affected_rows = mysqli_affected_rows($_DB['session']);
      // $before = `AUTO_INCREMENT`
      for($i = 0; $i < $affected_rows; $i++) {
        $ids[] = $before;
        $before++;
      }
      $ids = implode(',', $ids);
      if(function_exists('dialog_after_add')) dialog_after_add($ids);
      $_MODULE['next'] = true;
      $_MODULE['output'] = '{';
      $_MODULE['output'] .= 'run:"getware.ui.alert.make",';
      $_MODULE['output'] .= 'reference:"' . $_POST['window'] . '",';
      $_MODULE['output'] .= 'data:"DATOS GUARDADOS!"';
      $_MODULE['output'] .= '}';

    } else $KERNEL->alert(mysqli_error($_DB['session']));
  } # NO PASO EL INFO_ADD()
}

?>