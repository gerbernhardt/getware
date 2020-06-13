<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel/dialog.axx.check.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

class kernel_dialog_axx_check{
 
 function restrict($i,$j) {
  global $_DB,$_USER,$_ADMIN,$_TABLE,$_MODULE;
  if(isset($_MODULE['restrict'][_ADD][$_TABLE['column']['name'][$i]])) {
   $row['name']=$_TABLE['column']['name'][$i];
   $row['data']=$_MODULE['restrict'][_ADD][$_TABLE['column']['name'][$i]];
   $row['operator']='==';
   if(isset($_MODULE['restrict'][_OPERATOR][_ADD][$_TABLE['column']['name'][$i]]))
    $row['operator']=$_MODULE['restrict'][_OPERATOR][_ADD][$row['name']];
   $e='if($row[\'data\']'.$row['operator'].'$_POST[$i][$j])';
   $e.=' return true;';
   $e.='else return false;';
   return eval($e);
  } else return true;
 }
 
 #
 # EL REGISTRO RELACIONAL TIENE QUE EXISTIR SI O SI
 #
 function exists($i,$j) {
  global $KERNEL,$_DB,$_ADMIN,$_TABLE,$_MODULE;
  if(isset($_MODULE['exists'][$_TABLE['column']['name'][$i]])) {
    #
    # MULTIPLEX  SELECT
    #
    $field='CONCAT(';
    for($k=1;$k<count($_TABLE['column']['comment'][$i]);$k++) {
      // SUB REGISTROS
      if(preg_match('/\[*\]/',$_TABLE['column']['comment'][$i][$k])) {
        $reg=preg_replace('#(.*?)\[.*#si','\1',$_TABLE['column']['comment'][$i][$k]);
        $subreg=explode(':',preg_replace('#.*\[(.*?)\]#si','\1',$_TABLE['column']['comment'][$i][$k]));
        $field.='(SELECT xx.'.$subreg[1].' FROM `'.$subreg[0].'` AS xx WHERE x.'.$reg.'=xx.id)';
      } else $field.='x.'.$_TABLE['column']['comment'][$i][$k];
      $field.=',\''.$_TABLE['column']['separator'][$i][$k].'\'';
      if($k<count($_TABLE['column']['comment'][$i])-1) $field.=',';
    }
    $field.=')';

    $table=$_TABLE['column']['comment'][$i][0];
    # REGISTRO RELACIONAL
    if(isset($_TABLE['column']['comment'][$i])) {
      $sql='SELECT NULL FROM `'.$table.'` AS x';
      $sql.=' WHERE '.$field.'=\''.$_POST[$i][$j].'\'';
      //exit($sql);
    } else return true;

    if($result=mysqli_query($_DB['session'],$sql)) {
      //$KERNEL->alert(mysqli_num_rows($result));
      if(mysqli_num_rows($result) > 0)
        return true;
      else return false;
    } return false;

  } else return true;
 }
 #
 # EL REGISTRO TIENE CONDICIONES EN LOS CARACTERES
 # NO ADMITE REGISTROS EN BLANCO
 #
 function blank($i,$j) {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE;
  if(isset($_MODULE['blank'][$_TABLE['column']['name'][$i]])) {
   if(trim($_POST[$i][$j])=='')
    return false;
   else return true;
  } else return true;
 }
  #
  # EL REGISTRO DEBE SER UN ENTERO
  #
  function int($i, $j) {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE;
  if(isset($_MODULE['int'][$_TABLE['column']['name'][$i]])) {
    if(is_int($_POST[$i][$j]))
      return true;
    else return false;
    } else return true;
  }
  #
  # EL REGISTRO DEBE SER UN ENTERO
  #
  function float($i, $j) {
    global $_DB,$_ADMIN,$_TABLE,$_MODULE;
    if(isset($_MODULE['int'][$_TABLE['column']['name'][$i]])) {
      if(is_float($_POST[$i][$j]))
        return true;
      else return false;
      } else return true;
    }
 #
 # EL REGISTRO ES UNICO
 # CHEQUEA QUE EL REGISTRO INSERTADO NO ESTE DUPLICADO
 #
 function unique($i,$j) {
  global $_DB,$_ADMIN,$_TABLE,$_MODULE;
  if(isset($_MODULE['unique'][$_TABLE['column']['name'][$i]])) {
   #
   # MULTIPLEX  SELECT
   #
   $reference=$_TABLE['column']['comment'][$i];
   if(count($reference)>2) {
    $field='CONCAT(';
    for($j=1;$j<count($reference);$j++) {
     $field.='x0.'.$reference[$j];
     if($j<count($reference)-1) $field.=',';
    }
    $field.=')';
   } else $field='x.'.$reference[1];
   # REGISTRO RELACIONAL
   if(isset($_TABLE['column']['comment'][$i])&&count($reference)>=2) {
    $sql='SELECT NULL FROM `'.$_TABLE['name'].'` AS x';
    $sql.=' INNER JOIN `'.$reference[0].'` AS x0 ON x.'.$_TABLE['column']['name'][$i].'=x0.id';
    $sql.=' WHERE '.$field.'=\''.$_POST[$i][$j].'\'';
   # REGISTRO NORMAL
   } else {
    $sql='SELECT NULL FROM `'.$_TABLE['name'].'` AS x';
    $sql.=' WHERE x.'.$_TABLE['column']['name'][$i].'=\''.$_POST[$i][$j].'\'';
   }
   $result=mysqli_query($_DB['session'],$sql);
   if(mysqli_num_rows($result))
    return false;
   else return true;
  } else return true;
 }
  function make_array($x) {
    global $_DB, $_MODULE;
    if(isset($_MODULE[$x])) {
      $count = count($_MODULE[$x]);
      for($i = 0; $i < $count; $i++) {
        $AUX = $_MODULE[$x][$i];
        $_MODULE[$x][$AUX] = true;
        unset($_MODULE[$x][$i]);
      }
    }
  }

  #
  # ENVIA EL JSON CON LOS DATOS INCORRECTOS
  #
  function info() {
    global $_DB, $_TABLE, $_ADMIN;
    if(!isset($_POST['window'])) exit();
  
    $_MODULE['output'] = '[';
    $_MODULE['output'] .= '{';
    $_MODULE['output'] .= 'run:"getware.ui.content.info.add",window:"' . $_POST['window'] . '",';
    
    $rows = '';
    $data = '';
  
    $this->make_array('int');
    $this->make_array('float');
    $this->make_array('blank');
    $this->make_array('exists');
    $this->make_array('unique');

    for($i = $_ADMIN['ini']; $i < $_ADMIN['head']; $i++) {
      # GENERA LOS POST QUE NO EXISTEN
      if(!isset($_POST[$i][0])) $_POST[$i][0] = '';
      $value = 1;
      
      if(!$this->int($i, 0)) $value = 0;
      if(!$this->float($i, 0)) $value = 0;
      if(!$this->blank($i, 0)) $value = 0;
      if(!$this->unique($i, 0)) $value = 0;
      if(!$this->exists($i, 0)) $value = 0;
      if(!$this->restrict($i, 0)) $value = 0;
      
      $rows .= '"' . $i . 'x' . '",';
      $data .= '"' . $value . '",';
    }

    for($i = $_ADMIN['head']; $i < $_ADMIN['end']; $i++) {
      for($j = 0; $j < count($_POST[$_ADMIN['head']]); $j++) {
        # GENERA LOS POST QUE NO EXISTEN
        if(!isset($_POST[$i][$j])) $_POST[$i][$j] = '';
        //print $i . 'x ' . $j . '=> ' . $_POST[$i][$j].'<br>';

        $value = 1;
        if(!$this->int($i, $j)) $value = 0;
        if(!$this->blank($i, $j)) $value = 0;
        if(!$this->unique($i, $j)) $value = 0;
        if(!$this->exists($i, $j)) $value = 0;
        if(!$this->restrict($i, $j)) $value = 0;

        $row = $i . 'x' . $j;
        $rows .= '"' . $row . '"';
        $data .= '"' . $value . '"';
        if($j < count($_POST[$_ADMIN['head']]) - 1) {
          $rows .= ',';
          $data .= ',';
        }
      }
      if($i < $_ADMIN['end'] - 1) {
        $rows .= ',';
        $data .= ',';
      }
    } 

    $_MODULE['output'] .= 'rows:[' . $rows . '],';
    $_MODULE['output'] .= 'data:[' . $data . ']';

    $_MODULE['output'] .= '}';
    $_MODULE['output'] .= ']';
    
    if(preg_match('/"0"/', $_MODULE['output']))
      exit($_MODULE['output']);
    else return true;
  }
}

$KERNEL->dialog->axx->check = new kernel_dialog_axx_check;

?>