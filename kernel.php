<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

$_TABLE['name'] = $_GET['admin'];

class kernel{

 # TABLE ANALYZER
 function table() {
  global $_ADMIN, $_TABLE, $_DB;

  $sql='SELECT DISTINCT XC.column_name,XC.column_type,XC.column_comment';
  $sql.=' FROM INFORMATION_SCHEMA.COLUMNS AS XC';
  $sql.=' WHERE XC.TABLE_NAME=\''.$_TABLE['name'].'\' AND XC.TABLE_SCHEMA=\''.$_DB['name'].'\'';
  //exit($sql);
  if($result=mysqli_query($_DB['session'],$sql)) {
   for($i=0;$fetch=$result->fetch_array();$i++) {
    if($fetch['column_type']=='datetime')
     $fetch['column_type']='datetime(10)';
    $_TABLE['column']['name'][$i]=$fetch['column_name'];
    $_TABLE['column']['eman'][$_TABLE['column']['name'][$i]]=$i;
    $_TABLE['column']['type'][$i]=$fetch['column_type'];
    $_TABLE['column']['length'][$i]=preg_replace('#.*\((.*?)\).*#si','\1',$fetch['column_type']);
    if(substr($_TABLE['column']['type'][0],strlen($_TABLE['column']['type'][0])-8,10)=='zerofill')
     $_TABLE['column']['zerofill'][$i]=true;
    $_TABLE['column']['size'][$i]=preg_replace('#.*\((.*?)\)#si','\1',$fetch['column_type']);

    $_TABLE['column']['function'][$i]=preg_replace('/\(.*\)/','',$fetch['column_comment']);
    if($_TABLE['column']['function'][$i]!=''){
     $_TABLE['column']['comment'][$i]=explode('.',preg_replace('#.*\((.*?)\)#si','\1',$fetch['column_comment']));
     for($j=1;$j<count($_TABLE['column']['comment'][$i]);$j++){
      $aux=explode('|',$_TABLE['column']['comment'][$i][$j]);
      $_TABLE['column']['comment'][$i][$j]=$aux[0];
      if(isset($aux[1]))
       $_TABLE['column']['separator'][$i][$j]=$aux[1];
      else $_TABLE['column']['separator'][$i][$j]='';
     }
    }

   } if($i<1) $this->alert('TABLA INEXISTENTE!');
   $_ADMIN['ini']=1;
   $_ADMIN['end']=count($_TABLE['column']['name']);
  } else $this->alert('ERROR EN LA CONSULTA SQL!');
 }

  # COLUMN MAKE
  function make_row($index, $column, $table) {
    global $_DB, $_TABLE;

    $sql='SELECT DISTINCT x.column_name, x.column_type, x.column_comment';
    $sql.=' FROM INFORMATION_SCHEMA.COLUMNS AS x';
    $sql.=' WHERE x.column_name=\'' . $column . '\' AND x.TABLE_NAME=\'' . $table . '\' AND x.TABLE_SCHEMA=\'' . $_DB['name'] . '\'';
    if($result = mysqli_query($_DB['session'], $sql)) {
      if($fetch = $result->fetch_array()) {
        if($fetch['column_type']=='datetime')
          $fetch['column_type']='datetime(10)';
        
        $_TABLE['column']['name'][$index] = $fetch['column_name'];
        $_TABLE['column']['eman'][$_TABLE['column']['name'][$index]] = $index;
        $_TABLE['column']['type'][$index] = $fetch['column_type'];
        $_TABLE['column']['length'][$index] = preg_replace('#.*\((.*?)\).*#si','\1',$fetch['column_type']);
        $_TABLE['column']['size'][$index] = preg_replace('#.*\((.*?)\)#si', '\1', $fetch['column_type']);

        $_TABLE['column']['function'][$index] = preg_replace('/\(.*\)/', '', $fetch['column_comment']);
        if($_TABLE['column']['function'][$index]!='') {
          $_TABLE['column']['comment'][$index] = explode('.', preg_replace('#.*\((.*?)\)#si', '\1', $fetch['column_comment']));
          for($j = 1; $j < count($_TABLE['column']['comment'][$index]); $j++) {
            $aux = explode('|', $_TABLE['column']['comment'][$index][$j]);
            $_TABLE['column']['comment'][$index][$j] = $aux[0];
            if(isset($aux[1]))
              $_TABLE['column']['separator'][$index][$j] = $aux[1];
            else $_TABLE['column']['separator'][$index][$j] = '';
          }
        }
      }
    } else $this->alert('ERROR EN LA CONSULTA SQL!');
  }
  # COLUMN REMOVE
  function remove_row($index) {
    global $_TABLE;
    unset($_TABLE['column']['eman'][$_TABLE['column']['name'][$index]]);
    unset($_TABLE['column']['name'][$index]);
    unset($_TABLE['column']['type'][$index]);
    unset($_TABLE['column']['length'][$index]);
    unset($_TABLE['column']['function'][$index]);
    unset($_TABLE['column']['comment'][$index]);
    unset($_TABLE['column']['separator'][$index]);
  }
  function make_select($i, $post) {
    global $_TABLE;
    $_TABLE['column']['comment'][$i];

    $field = 'CONCAT(';
    for($j = 1; $j < count($_TABLE['column']['comment'][$i]); $j++) {
        // SUB REGISTROS
        if(preg_match('/\[*\]/', $_TABLE['column']['comment'][$i][$j])) {
            $reg = preg_replace('#(.*?)\[.*#si','\1', $_TABLE['column']['comment'][$i][$j]);
            $subreg = explode(':', preg_replace('#.*\[(.*?)\]#si','\1', $_TABLE['column']['comment'][$i][$j]));
            $field .= '(SELECT xx.' . $subreg[1] . ' FROM `' . $subreg[0] . '` AS xx WHERE x.' . $reg . '=xx.id)';
        } else {
          $field .= 'x.' . $_TABLE['column']['comment'][$i][$j];
        }

        if($_TABLE['column']['separator'][$i][$j] != '')
          $field .= ',\'' . $_TABLE['column']['separator'][$i][$j] . '\'';
        
        if($j < count($_TABLE['column']['comment'][$i]) - 1) $field .= ',';
    }
    $field .= ')';
    $sql = '(SELECT x.* FROM `' . $_TABLE['column']['comment'][$i][0] . '` AS x WHERE ' . $field . '=\'' . $post . '\')';
    return $sql;
  }
 
  # PRIVILEGES ANALYZER
 function privilege($type) {
  global $_ADMIN,$_TABLE,$KERNEL;
  if(isset($_ADMIN[$type])&&isset($_GET[$type])){
   if($_ADMIN[$type]==true){
    return true;
   }else $this->alert('NO TIENES PRIVILEGIOS!');
  }else return false;
 }

 function restrict($type) {
  global $_USER,$CORE,$_ADMIN,$_TABLE,$_MODULE;
  $return=true;
  if(isset($_MODULE['restrict'][$type])){
   foreach($_MODULE['restrict'][$type] as $x=>$y) {
    $op='=';
    if(isset($_MODULE['restrict'][_OPERATOR][$type][$x]))
     $op=$_MODULE['restrict'][_OPERATOR][$type][$x];
   }
  } else $return=true;
  return $return;
 }

 # ROW ANALYZER
  function row_type($i) {
    global $_ADMIN,$_TABLE;
    $type = $_TABLE['column']['type'][$i];
    $name = $_TABLE['column']['name'][$i];
    $function = $_TABLE['column']['function'][$i];

    if($function == 'REFERENCE' || $function == 'FILE' || $function == 'FILE_CUSTOM')
      return 'view';
    elseif($function == 'REFERENCES')
      return 'select';
    elseif($name == 'password')
      return 'pass';
    elseif($type == 'date')
      return 'date';
    elseif($type == 'year')
      return 'year';
    elseif($type == 'text')
      return 'text';
    elseif(substr($type, 0, 6) == 'double')
      return 'number';
    else return 'varchar';
  }

function set_autocomplete($str){
  $str = str_replace('\n','',$str);
  $str = str_replace('&quot;','\\"',$str);
  $str = str_replace('&amp;','&',$str);
  return $str;
}
function json_object($name, $array, $numeric = true) {
  $i = 0;
  $count = count($array);
  $output = $name . ':{';
  foreach($array as $key => $value) {
    $output .= $key . ':';
    
    if($numeric && is_numeric($value))
      $output .= $value;
    else $output .= '"' . $value . '"';
    
    if($i < $count - 1) $output .= ',';
    
    $i++;
  }
  $output .= '}';
  return $output;
}
function json_array($array, $numeric = true) {
  $i = 0;
  $count = count($array);
  $output = '[';
  foreach($array as $value) {
      if($numeric && is_numeric($value))
        $output .= $value;
      else $output .= '"' . $value . '"';
      if($i < $count - 1) $output .= ',';
      $i++;
  }
  $output .= ']';
  return $output;
}
  # JSON EXIT
  function json_exit($output) {
    $output = preg_replace('/\r/','', $output);
    $output = preg_replace('/\n/','\\n', $output);
    //header('Content-Type: text/json');
    header('Accept-Ranges: bytes');
    header('Content-Length: ' . strlen($output));
    exit($output);
   }
  # JSON PRINT
 function json_print() {
  global $_MODULE;
  if(is_array($_MODULE['output']))
    $_MODULE['output'] = implode(',',$_MODULE['output']);
  
  $_MODULE['output'] = preg_replace('/\r/','',$_MODULE['output']);
  $_MODULE['output'] = preg_replace('/\n/','\\n',$_MODULE['output']);
  $_MODULE['output'] = '['.$_MODULE['output'].']';
  if(isset($_GET['ajax'])) {
    //header('Content-Type: text/json');
    header('Accept-Ranges: bytes');
    header('Content-Length: ' . strlen($_MODULE['output']));
  }

  if(!isset($_GET['ajax']))
   print '<script>getware.data(\''.$_MODULE['output'].'\');</script>';
  else print $_MODULE['output'];

 }
 # ALERT JSON GENERATOR
 function alert($data,$reference=false,$module=false,$action=false,$blank=false,$button='Ok',$exec=false) {
  global $_ADMIN,$_TABLE,$_MODULE;
  $_MODULE['output']='{run:"getware.ui.alert.make",';
  if($reference) $_MODULE['output'].='reference:"'.$reference.'",';
  if($module) $_MODULE['output'].='module:"'.$module.'",';
  if($action) $_MODULE['output'].='action:"'.$action.'",';
  if($action) $_MODULE['output'].='button:"'.$button.'",';
  if($blank) $_MODULE['output'].='blank:true,';
  if($exec) $_MODULE['output'].='exec:"'.$exec.'",';
  $_MODULE['output'].='data:"'.$data.'"}';
  if(!isset($_MODULE['next'])) exit('['.$_MODULE['output'].']');
 }

}

$KERNEL=new kernel;
include('kernel/query.php');
include('kernel/search.php');
include('kernel/grid.php');
include('kernel/dialog.php'); 

?>