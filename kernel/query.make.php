<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel/query.make.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();

if(function_exists('query')) {
  query();
} else {
  
  # INI QUERY
  $SELECT = 'SELECT x.id,';
  $FROM = ' FROM `'.$_TABLE['name'].'` AS x';
  $JOIN = '';
  $WHERE = ' WHERE ';
  $ORDER = 'x.id';
  $SORT = 'DESC';
  
  if(isset($_MODULE['query']['order']))
    $ORDER = $_MODULE['query']['order'];

  if(isset($_MODULE['query']['sort']))
    $SORT = $_MODULE['query']['sort'];

  $as = 0;
  $indexAS = array();
  $column = $_TABLE['column'];
  $grid = $_MODULE['grid'];
  $count_f = count($grid['field']);
  for($i = 0; $i < $count_f; $i++) {
    $eman = $column['eman'][$grid['field'][$i]];

    if($column['function'][$eman] == 'REFERENCE') {
      #
      # MULTIPLEX  SELECT
      #
      $comment = $column['comment'];
      $separator = $column['separator'];
      $count = count($comment[$eman]);

      // CAMBIA TEMPORALMENTE EL REFERENCE EN EL MODULO
      if(isset($grid['reference'][$grid['name'][$i]])) {
        if(isset($grid['separator'][$grid['name'][$i]])){
          $separator[$eman] = $grid['separator'][$grid['name'][$i]];
        } else {
          for($j = 1; $j < $count; $j++) {
            $separator[$eman][$j] = '';
          }
        }
        
        $comment[$eman] = $grid['reference'][$grid['name'][$i]];
      }
      
      if($count == 2) {
        // NO HACE FALTA HACER CONCAT()
        if(preg_match('/\[*\]/', $comment[$eman][1]))
          $SELECT .= $this->subquery($comment[$eman][1], $as);
        else $SELECT .= 'x' . $as . '.' . $comment[$eman][1];

      } else {

        $SELECT .= 'CONCAT(';
        for($j = 1; $j < $count; $j++) {
          // SUB REGISTROS
          if(preg_match('/\[*\]/', $comment[$eman][$j]))
            $SELECT .= $this->subquery($comment[$eman][$j], $as);
          else $SELECT .= 'x' . $as . '.' . $comment[$eman][$j];
          
          if($separator[$eman][$j] != '')
            $SELECT .= ',\'' . $separator[$eman][$j] . '\'';

          if($j < $count - 1)
            $SELECT.=',';

        }
        $SELECT .= ')';

      }
      $SELECT .= ' AS `' . $column['name'][$eman] . '`';

      // 'material' = 0;
      $indexAS[$grid['field'][$i]] = $as;
      $JOIN .= ' LEFT JOIN `'.$comment[$eman][0].'` AS x'.$as.' ON x.'.$grid['field'][$i].'=x'.$as.'.id';
      $as++;

    } elseif(isset($grid['sql'][$grid['name'][$i]])) {
      $SELECT .= $grid['sql'][$grid['name'][$i]];
    } else {
      $SELECT .= 'x.' . $grid['field'][$i];
    }
    #
    # END OF REFERENCE
    #

    if($i < count($grid['field']) - 1) $SELECT.=',';
  }

 $as = 0;
 if(isset($_POST['search'])) {
   
  $search = $_MODULE['search'];
  $count = count($search['field']);
  $fields = $search['field'];
  $comment = $column['comment'];
  $separator = $column['separator'];

  for($i = 0; $i < $count; $i++) {

    $eman = $column['eman'][$fields[$i]];
    $name = $search['name'][$i];

    if($column['function'][$eman] == 'REFERENCE') {
      #
      # MULTIPLEX  SEARCH
      #
      $count_c = count($comment[$eman]);
      // CAMBIA TEMPORALMENTE EL REFERENCE PARA EL WHERE
      if(isset($search['reference'][$name])) {
        if(isset($search['separator'][$name])) {
          $separator[$eman] = $search['separator'][$name];
        } else {
          for($j = 1; $j < $count_c; $j++) {
            $separator[$eman][$j] = '';
          }
        }
        $comment[$eman] = $search['reference'][$name];
      }


      //if(in_array($search['field'][$i], $grid['field'])) {
        //$field = '`' . $fields[$i] . '`';
      //} else {
        $field = 'CONCAT(';
        for($j = 1; $j < $count_c; $j++) {
          // SUB REGISTROS
          if(preg_match('/\[*\]/', $comment[$eman][$j])) {
            $reference = array($indexAS[$fields[$i]], $comment[$eman][$j]);
            $field .= $this->subquery($comment[$eman][$j], $indexAS[$fields[$i]]);
          } else {
            $field .= 'x'. $indexAS[$fields[$i]] . '.' . $comment[$eman][$j];
          }
          
          if($separator[$eman][$j] != '') $field .= ',\'' . $separator[$eman][$j] . '\'';
          if($j < $count_c - 1) $field .= ',';
        }
        $field .= ')';
      //}

      $as++;
    } elseif(isset($search['sql'][$name])) {
      $field = $search['sql'][$name];
    } else {
      $field = 'x.' . $fields[$i];
    }

    if(isset($_POST['search'][$i]) && $_POST['search'][$i] != '') {
      if($column['type'][$eman] == 'date') {
        $_POST['search'][$i] = $CORE->setdate($_POST['search'][$i]);
      }
      
      $WHERE .= $field;
      
      $filter = 'LIKE';
      
      if(isset($_POST['filter'][$i])) {
        if(in_array($_POST['filter'][$i], array('high', 'low', 'middle'))) {
          if($_POST['filter'][$i] == 'high') $filter = '>';
          if($_POST['filter'][$i] == 'low') $filter = '<';
          if($_POST['filter'][$i] == 'middle') $filter = '=';
        }
      }

      $WHERE .= ' ' . $filter . ' \'' . $_POST['search'][$i] . '\'';
      
      if(isset($_POST['search'][$i + 1]) && $_POST['search'][$i + 1] != '')
        $WHERE .=  ' AND ';
    }
  }
 }
 
 if(isset($_MODULE['restrict'][_VIEW]))
    $WHERE .= $_MODULE['restrict'][_VIEW];


 $ORDER = ' ORDER BY ' . $ORDER . ' ' . $SORT;

 $rows = 20;
 if(isset($_GET['rows']))
  $rows = $_GET['rows'];

 $start = 0;
 if(isset($_GET['start']))
  $start = $_GET['start'] * $rows;

 $LIMIT = ' LIMIT ' . $start . ',' . $rows;

 if($WHERE == ' WHERE ') $WHERE = '';
 
 $sql = $SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT;

 $_MODULE['grid']['navbar'] = $FROM . $JOIN . $WHERE . $ORDER;// NO VA $LIMIT;

 $this->cache();
}

?>