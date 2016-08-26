<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel/dialog.add.save.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();
if($_GET['add']=='save') {
 if($this->info()) {
  $sql='INSERT INTO '.$_TABLE['name'];
  $sql.='(';
  for($i=$_ADMIN['ini'];$i<$_ADMIN['end'];$i++) {
   $sql.='`'.$_TABLE['column']['name'][$i].'`';
   if($i<$_ADMIN['end']-1) $sql.=',';
  }
  $sql.=') VALUES (';
  for($i=$_ADMIN['ini'];$i<$_ADMIN['end'];$i++) {
   if(!isset($_POST[$i])) $_POST[$i]='';
   if($_TABLE['column']['function'][$i]=='REFERENCE') {
    #
    # MULTIPLEX  SELECT
    #
    $_TABLE['column']['comment'][$i];

    $field='CONCAT(';
    for($j=1;$j<count($_TABLE['column']['comment'][$i]);$j++) {
     // SUB REGISTROS
     if(preg_match('/\[*\]/',$_TABLE['column']['comment'][$i][$j])){
      $reg=preg_replace('#(.*?)\[.*#si','\1',$_TABLE['column']['comment'][$i][$j]);
      $subreg=explode(':',preg_replace('#.*\[(.*?)\]#si','\1',$_TABLE['column']['comment'][$i][$j]));
      $field.='(SELECT xx.'.$subreg[1].' FROM '.$subreg[0].' AS xx WHERE x.'.$reg.'=xx.id)';
     }else $field.='x.'.$_TABLE['column']['comment'][$i][$j];
     $field.=',\''.$_TABLE['column']['separator'][$i][$j].'\'';
     if($j<count($_TABLE['column']['comment'][$i])-1) $field.=',';
    }
    $field.=')';
    $sql.='(SELECT x.id FROM '.$_TABLE['column']['comment'][$i][0].' AS x WHERE '.$field.'=\''.$_POST[$i].'\')';
   }elseif($_TABLE['column']['function'][$i]=='REFERENCES') {
    #
    # MULTIPLEX  SELECT DIMENSIONAL
    #
    $sql.=0;
   } else {
    $sql.='\''.$_POST[$i].'\'';
   }
   if($i<$_ADMIN['end']-1) $sql.=',';
  }
  $sql.=')';
  if(mysqli_query($_DB['session'],$sql)) {
   $id=mysqli_insert_id($_DB['session']);
   for($i=$_ADMIN['ini'];$i<$_ADMIN['end'];$i++){
    if($_TABLE['column']['function'][$i]=='REFERENCES') {
     #
     # MULTIPLEX  SELECT DIMENSIONAL
     #
     $table=explode('#',$_TABLE['column']['comment'][$i][0]);
     if(is_array($_POST[$i])){
      $sql='INSERT INTO '.$table[0].' (`'.$table[1].'`,`'.$table[2].'`) VALUES ';
      for($j=0;$j<count($_POST[$i]);$j++) {
       $sql.='('.$id.',(SELECT xx.id FROM '.$table[3].' AS xx WHERE xx.'.$_TABLE['column']['comment'][$i][1].'="'.$_POST[$i][$j].'"))';
       if($j<count($_POST[$i])-1) $sql.=',';
      }
      mysqli_query($_DB['session'],$sql);
     }
    }
   }
   if(function_exists('upload')) upload(); # ACTIVA LA FUNCION UPLOAD QUEDO DESDE KEBLAR
   $_MODULE['next']=true;
   $KERNEL->alert('DATOS GUARDADOS!',$_GET['reference']);
   # ERROR SQL
  } else {
   $error=mysqli_error($_DB['session']);
   #
   # ERROR REGISTRO RELACIONAL NO EXISTE
   #
   if(preg_match('/Column \'.*\' cannot be null/',$error)) {
    $x=preg_replace('/Column \'/','',$error);
    $x=preg_replace('/\' cannot be null/','',$x);
    $_MODULE['output']='[{run:"getware.ui.content.info.add",window:"'.$_POST['window'].'",';
    $rows='';
    $data='';
    for($i=$_ADMIN['ini'];$i<$_ADMIN['end'];$i++) {
     $row=$i;
     $value=1;

     if(!isset($_TABLE['column']['eman'][$x])){
//      $_MODULE['next']=true;
      $KERNEL->alert('ERROR EN EL TRIGGER!<BR>'.$error);
     }
     if($i==$_TABLE['column']['eman'][$x])
      $value=0;

     $rows.='"'.$row.'"';
     $data.='"'.$value.'"';
     if($i<count($_TABLE['column']['name'])-1) {
      $rows.=',';
      $data.=',';
     }
    }
    $_MODULE['output'].='rows:['.$rows.'],data:['.$data.']}]';
    exit($_MODULE['output']);
    #
    # ERROR REGISTRO DUPLICADO
    #
   } elseif(preg_match('/Duplicate entry \'.*\' for key \'.*\'/',$error)) {
    $x=preg_replace('/Duplicate entry \'.*\' for key \'/','',$error);
    $x=preg_replace('/\'/','',$x);
    $sql='SELECT DISTINCT XC.COLUMN_NAME';
    $sql.=' FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS XC';
    $sql.=' WHERE XC.CONSTRAINT_SCHEMA=\''.$_DB['name'].'\'';
    $sql.=' AND XC.TABLE_SCHEMA=\''.$_DB['name'].'\'';
    $sql.=' AND XC.TABLE_NAME=\''.$_TABLE['name'].'\'';
    $sql.=' AND XC.CONSTRAINT_NAME=\''.$x.'\'';
    $result=mysqli_query($_DB['session'],$sql);
    $error=array();
    for($i=0;$fetch=$result->fetch_array();$i++) {
    $error[$i]=$fetch[0];
    }
    $_MODULE['output']='[{run:"getware.ui.content.info.add",window:"'.$_POST['window'].'",';
    $rows='';
    $data='';
    for($i=$_ADMIN['ini'];$i<count($_TABLE['column']['name']);$i++) {
     $row=$i;
     $value=1;
     if(isset($error[$i-$_ADMIN['ini']])) $value=0;
     $rows.='"'.$row.'"';
     $data.='"'.$value.'"';
     if($i<count($_TABLE['column']['name'])-1) {
      $rows.=',';
      $data.=',';
     }
    }
    $_MODULE['output'].='rows:['.$rows.'],data:['.$data.']}]';
    exit($_MODULE['output']);
    #
    # ERROR REGISTRO DEBE SER UN NUMERO ENTERO o FECHA DEPENDE DEL CAMPO
    #
   } elseif(preg_match('/Incorrect .* value: \'.*\' for column \'.*\'/',$error)) {
    $x=preg_replace('/Incorrect .* value: \'.*\' for column \'/','',$error);
    $x=preg_replace('/\'.*/','',$x);
    //$KERNEL->alert($x);
    $_MODULE['output']='[{run:"getware.ui.content.info.add",window:"'.$_POST['window'].'",';
    $rows='';
    $data='';
    for($i=$_ADMIN['ini'];$i<count($_TABLE['column']['name']);$i++) {
     $row=$i;
     $value=1;
     if($_TABLE['column']['name'][$i]==$x) $value=0;
     $rows.='"'.$row.'"';
     $data.='"'.$value.'"';
     if($i<count($_TABLE['column']['name'])-1) {
      $rows.=',';
      $data.=',';
     }
    }
    $_MODULE['output'].='rows:['.$rows.'],data:['.$data.']}]';
    exit($_MODULE['output']);
    # ERROR DESCONOCIDO ENVIA UN ALERT
    # FUNCIONA EL DECODIFICADOR DE ERRORES PERO NO EL INFO_ADD()
   } else $KERNEL->alert($error);
  }
 }
}

?>