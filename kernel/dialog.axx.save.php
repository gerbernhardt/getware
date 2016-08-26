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
if($_GET['add']=='save') {
 if(!isset($_ADMIN['head'])) $_ADMIN['head']=$_ADMIN['ini'];
 # UPDATE TABLE SET @PARENT=@PARENT_ID WHERE ID IN(@ID)
 mysqli_query($_DB['session'],'SET @i=0');         # @ID PRIMER REGISTRO INSERTADO ID=99
 mysqli_query($_DB['session'],'SET @sql=\'\'');
 mysqli_query($_DB['session'],'SET @msg=\'\'');
 mysqli_query($_DB['session'],'SET @breakInsert=false');
 if($this->check->info()) { # CONTROL INTERNO ANTES GUARDAR
  if(function_exists('axx_check')) axx_check();
  $sql='INSERT INTO '.$_TABLE['name'].' (';
  for($i=$_ADMIN['ini'];$i<$_ADMIN['end'];$i++) {
   $sql.='`'.$_TABLE['column']['name'][$i].'`';
   if($i<$_ADMIN['end']-1) $sql.=',';
  }
  $sql.=') VALUES ';
  for($j=1;$j<count($_POST[$_ADMIN['head']]);$j++) {
   $sql.='(';

   for($i=$_ADMIN['ini'];$i<$_ADMIN['end'];$i++) {
    # ESTA RUTINA SE ENCUENTRA EN SAVE_AXX_INFO()
    if(!isset($_POST[$i][$j])) {
     if($i<$_ADMIN['head']&&isset($_POST[$i][$j-1]))
      $_POST[$i][$j]=$_POST[$i][$j-1];
     else $_POST[$i][$j]='';
    }

    if($_TABLE['column']['function'][$i]=='REFERENCE') {
     #
     # MULTIPLEX  SELECT
     #
     $field='CONCAT(';
     for($k=1;$k<count($_TABLE['column']['comment'][$i]);$k++) {
      // SUB REGISTROS
      if(preg_match('/\[*\]/',$_TABLE['column']['comment'][$i][$k])){
       $reg=preg_replace('#(.*?)\[.*#si','\1',$_TABLE['column']['comment'][$i][$k]);
       $subreg=explode(':',preg_replace('#.*\[(.*?)\]#si','\1',$_TABLE['column']['comment'][$i][$k]));
       $field.='(SELECT xx.'.$subreg[1].' FROM '.$subreg[0].' AS xx WHERE x.'.$reg.'=xx.id)';
      }else $field.='x.'.$_TABLE['column']['comment'][$i][$k];
      $field.=',\''.$_TABLE['column']['separator'][$i][$k].'\'';
      if($k<count($_TABLE['column']['comment'][$i])-1) $field.=',';
     }
     $field.=')';
     $sql.='(SELECT x.id FROM '.$_TABLE['column']['comment'][$i][0].' AS x WHERE '.$field.'=\''.$_POST[$i][$j].'\' LIMIT 1)';
    }elseif($_TABLE['column']['function'][$i]=='REFERENCES') {
     #
     # MULTIPLEX  SELECT DIMENSIONAL
     #
     $sql.=0;
    } else $sql.='\''.$_POST[$i][$j].'\'';

    if($i<$_ADMIN['end']-1) $sql.=',';
   }

   $sql.=')';
   if($j<count($_POST[$_ADMIN['head']])-1) $sql.=',';

  }
  # INSERT INTO TABLE VAUES(AA,BB),(YY,ZZ);
  if($result=mysqli_query($_DB['session'],$sql)) {# EL TRIGGER GUARDA @ID=EL PRIMER REGISTRO INSERTADO
   $id='';  # EL TRIGGER PADRE  GUARDA EL ID INSERTADO EN @PARENT_ID
   for($i=0;$i<mysqli_affected_rows($_DB['session']);$i++) { # GUARDA TODOS LOS ID'S INSERTADOS
    $id.=mysqli_insert_id($_DB['session'])+$i;# UTILIZANDO EL AUTOINCREMENT LOGICO DEL SQL
    if($i<mysqli_affected_rows($_DB['session'])-1) $id.=',';
   }
   $eid=explode(',',$id);
    for($i=$_ADMIN['ini'];$i<$_ADMIN['end'];$i++){
     if($_TABLE['column']['function'][$i]=='REFERENCES') {
      #
      # MULTIPLEX  SELECT DIMENSIONAL
      #
      $table=explode('#',$_TABLE['column']['comment'][$i][0]);
      if(is_array($_POST[$i])){
       for($j=1;$j<count($_POST[$i]);$j++) {
        $sql='INSERT INTO '.$table[0].' (`'.$table[1].'`,`'.$table[2].'`) VALUES ';
       	for($k=0;$k<count($_POST[$i][$j]);$k++) {
         $sql.='('.$eid[($j-1)].',(SELECT xx.id FROM '.$table[3].' AS xx WHERE xx.'.$_TABLE['column']['comment'][$i][1].'="'.$_POST[$i][$j][$k].'"))';
         if($k<count($_POST[$i][$j])-1) $sql.=',';
        }
        mysqli_query($_DB['session'],$sql);
       }
      }
     }
    }
   # UPDATE CHILDREN IN(@ID) SET PARENT=@PARENT_ID
   if($result=mysqli_query($_DB['session'],'SELECT @msg,@sql,@parent,@parent_id')) {
    if($fetch=$result->fetch_array()) {
     if($fetch['@msg']!='') $KERNEL->alert($fetch['@msg']);
     if($fetch['@sql']!='') {
      $sql=explode(';',$fetch['@sql']);
      $error=false;
      for($i=0;$i<count($sql);$i++) {
       if($sql[$i]!='') @mysqli_query($_DB['session'],$sql[$i]);
       //print $sql[$i];
      }
      if($fetch['@parent']!='') {
       $i=$_TABLE['column']['eman'][$fetch['@parent']];
       $ref=$_TABLE['column']['comment'][$i];
       $KERNEL->alert('DESEA IMPRIMIR EL COMPROBANTE?',$_GET['reference'],$ref[0],'print='.$fetch['@parent_id'],false,'Imprimir');
      }
      $KERNEL->alert('DATOS GUARDADOS!',$_GET['reference']);
     }
    }
   }
   $_MODULE['next']=true;
   $KERNEL->alert('DATOS GUARDADOS!',$_GET['reference']);
  } else $KERNEL->alert(mysqli_error($_DB['session'])); # ENVIA EL ERROR DEL INSERT
  # EL ADD COMUN TIENE EL DECODIFICADOR FUNCIONANDO CORECTAMENTE
 } # NO PASO EL INFO_ADD()
}

?>