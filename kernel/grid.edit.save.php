<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel/grid.edit.save.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();

if(isset($_GET['save']) && ($_ADMIN['edit'] == true)) {
  mysqli_query($_DB['session'], 'SET @sql=\'\'');
  mysqli_query($_DB['session'], 'SET @breakUpdate = false');
  
  if(!isset($_MODULE['restrict'][_EDIT]))
    $_MODULE['restrict'][_EDIT] = true;
  
  if(isset($_TABLE['column']['zerofill'][0]))
    $CORE->secure_get('save', 'int', ',', $_TABLE['column']['length'][0]);
  else $CORE->secure_get('save');

  $rows = array();
  for($i = 0; $i < count($_MODULE['grid']['edit']); $i++) {
    if(isset($_TABLE['column']['eman'][$_MODULE['grid']['edit'][$i]])) {
      $x=$_TABLE['column']['eman'][$_MODULE['grid']['edit'][$i]];
      $row['eman'][$x] = $i;
      $row['name'][$i] = $_TABLE['column']['name'][$x];
      $row['function'][$i] = $_TABLE['column']['function'][$x];
      if($_TABLE['column']['function'][$x] != '') {
        $row['comment'][$i] = $_TABLE['column']['comment'][$x];
        $row['separator'][$i] = $_TABLE['column']['separator'][$x];
      }
    } else $this->alert(_ERROR);
  }

  $rows = '';
  $data = '';
  $affected_rows = $_GET['save'];
  for($i = 0; $i < count($_GET['save']); $i++) {
    if(function_exists('grid_before_edit'))
      $grid_before_edit = grid_before_edit($_GET['save'][$i]);

    $sql='UPDATE '.$_TABLE['name'].' AS x';
    $set=' SET ';
    for($j = 0; $j < count($row['name']); $j++) {
      if(!isset($_POST[$row['name'][$j]][$i]))
        $_POST[$row['name'][$j]][$i] = '';
    
      # REGISTRO REFERENCIAL
      if($row['function'][$j] == 'REFERENCE') {

        #
        # MULTIPLEX  SELECT
        #
        $field = 'CONCAT(';
        for($k = 1; $k < count($row['comment'][$j]); $k++) {
          // SUB REGISTROS
          if(preg_match('/\[*\]/',$row['comment'][$j][$k])) {
            $reg = preg_replace('#(.*?)\[.*#si','\1',$row['comment'][$j][$k]);
            $subreg = explode(':',preg_replace('#.*\[(.*?)\]#si','\1',$row['comment'][$j][$k]));
            $field .= '(SELECT x.' . $subreg[1] . ' FROM ' . $subreg[0] . ' AS x WHERE x' . $j . '.' . $reg . '=x.id)';
          } else $field .= 'x' . $j . '.' . $row['comment'][$j][$k];
          $field .= ',\''.$row['separator'][$j][$k] . '\'';
          if($k < count($row['comment'][$j])-1) $field .= ',';
        }
        $field .= ')';

        # REGISTRO CONDICIONAL
        if(isset($_MODULE['condition'][$row['name'][$j]])) {
          $cond = $_MODULE['condition'][$row['name'][$j]];
          $in='';
          $sqx = 'SELECT ' . $field . ' FROM ' . $_TABLE['name'] . ' AS x';
          $sqx .= ' INNER JOIN ' . $row['comment'][$j][0] . ' AS x' . $j . ' ON x.' . $row['name'][$j] . '=x' . $j . '.id';
          $sqx .= ' WHERE x.id=\'' . $_GET['save'][$i] . '\'';
          $result = mysqli_query($_DB['session'], $sqx);
          if($fetch = $result->fetch_array()) {
            $cond = explode(';', $cond);
            for($x = 0; $x < count($cond); $x++) {
              $aux = explode(':', $cond[$x]);
              $rox[$aux[0]] = explode(',', $aux[1]);
            }
            if(isset($rox[$fetch[0]])) {
              for($x = 0;$x < count($rox[$fetch[0]]); $x++) {
                $in .= '\'' . $rox[$fetch[0]][$x] . '\'';
                if($x < count($rox[$fetch[0]]) -1 ) $in .= ',';
              }
            }
          }
        }

        $sql.=' INNER JOIN ' . $row['comment'][$j][0] . ' AS x' . $j . ' ON ' . $field . '=\'' . $_POST[$row['name'][$j]][$i] . '\'';

        if(isset($in)) {
          $sql .= ' AND ' . $field . ' IN(' . $in . ')';
          unset($in);
        }
        $set .= 'x.' . $row['name'][$j] . '=x' . $j . '.id';

      } else {
        $set .= 'x.' . $row['name'][$j] . '=\'' . $_POST[$row['name'][$j]][$i] . '\'';
      }
      
      // INJECCION DE CODIGO EN EL INNER Y EN EL SET
      if(function_exists('grid_edit_apppend')) {
        if(grid_edit_apppend('inner', $i))
          $inner_in = grid_edit_apppend('inner', $i);
        
        if(grid_edit_apppend('set', $i))
          $set_in = grid_edit_apppend('set', $i);
      }

      if($j < count($row['name']) - 1) {
        $set .= ',';
      } else {
        if(isset($inner_in)) $sql .= $inner_in;
        if(isset($set_in)) $set .= $set_in;
      }
    
 
    }

    $sql .= $set;
    //$KERNEL->alert($sql);
    $sql .= ' WHERE x.id=' . $_GET['save'][$i] . ' AND ' . $_MODULE['restrict'][_EDIT];
    
    $rows .= '' . $_GET['save'][$i] . '';
    
    if(mysqli_query($_DB['session'], $sql)) {
      if(mysqli_affected_rows($_DB['session']) > 0) {
        $data .= '1';
      } else {
        $data .= '0';
        unset($affected_rows[$i]);
      }
      // funcion para el duplicado de registros
      if(function_exists('grid_after_edit'))
        grid_after_edit($_GET['save'][$i], $grid_before_edit);

    } else {
      $data .= '-1';
      unset($affected_rows[$i]);
    }

    if($i < count($_GET['save']) - 1) {
      $rows .= ',';
      $data .= ',';
    }
  }

  $grid_after_edit_affected_rows = false;

  if(function_exists('grid_after_edit_affected_rows'))
    $grid_after_edit_affected_rows = grid_after_edit_affected_rows($affected_rows);

  $output = '[';
  if($grid_after_edit_affected_rows)
    $output .= $grid_after_edit_affected_rows . ',';  
  $output .= '{run:"getware.ui.content.info.grid",window:"' . $_POST['window'] . '",rows:[' . $rows . '],data:[' . $data . ']}';
  $output .= ']';  
  exit($output);
}

?>