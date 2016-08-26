<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel/grid.content.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();

if(!isset( $_MODULE['output'])) {
 $_MODULE['output']='';$append='false';
} else {$_MODULE['output'].=',';$append='true';}
if(isset($_MODULE['grid']['edit'])) $_MODULE['grid']['edit']=$this->make_array($_MODULE['grid']['edit']);
$_MODULE['output'].='{run:"getware.ui.grid.make",module:"'.$_GET['admin'].'",window:"td[id=content_center]",append:"'.$append.'",';

$type='content_center';
$_MODULE['output'].='title:"'.strtoupper($_MODULE['title']).'",';
$_MODULE['output'].='menu:'.$this->menu().',';
$_MODULE['output'].='navbar:'.$this->navbar().',';
# INIT TITLE
$_MODULE['output'].='columns:[';
for($i=0;$i<count($_MODULE['grid']['name']);$i++) {
 $_MODULE['output'].='{name:"'.utf8_encode(ucwords($_MODULE['grid']['name'][$i])).'",';
 $_MODULE['output'].='field:"'.utf8_encode($_MODULE['grid']['field'][$i]).'",';

 $x=$_TABLE['column']['eman'][$_MODULE['grid']['field'][$i]];
 if(preg_match('/tinyint|int|double|float/i',$_TABLE['column']['type'][$x])
  &&!preg_match('/REFERENCE/i',$_TABLE['column']['function'][$x])){
  $_MODULE['output'].='align:"right",';
 }

 if(isset($_MODULE['grid']['edit'][$_MODULE['grid']['field'][$i]])) {
  $sync=$_TABLE['column']['eman'][$_MODULE['grid']['field'][$i]];
  $_MODULE['output'].='edit:"'.$KERNEL->row_type($sync).'",';
 } else $_MODULE['output'].='edit:0,';
 $_MODULE['output'].='width:'.($_MODULE['grid']['size'][$i]*10).'}';
 if($i<count($_MODULE['grid']['name'])-1) $_MODULE['output'].=',';
}
$_MODULE['output'].='],';
# END TITLE

# INIT RESULT
$_MODULE['output'].='data:[';
if($result=mysqli_query($_DB['session'],$sql)) {
 for($i=0;$fetch=$result->fetch_array();$i++) {
  $_MODULE['output'].='["'.$fetch[0].'",';
  for($j=1;$j<=count($_MODULE['grid']['field']);$j++) {
   if($fetch[$j]=='') $fetch[$j]='NULL';
   #
   # MULTIPLEX  SELECT DIMENSIONAL
   #
   $eman=$_TABLE['column']['eman'][$_MODULE['grid']['field'][$j-1]];
   if(preg_match('/REFERENCES/i',$_TABLE['column']['function'][$eman])){
    $field='CONCAT(';
    for($k=1;$k<count($_TABLE['column']['comment'][$eman]);$k++) {
     //$_TABLE['column']['comment'][$x][0]='afiliados_sexos#afiliado#sexo#sexos';
     //$_TABLE['column']['comment'][$x][1]='nombre';
     // SUB REGISTROS
     if(preg_match('/\[*\]/',$_TABLE['column']['comment'][$eman][$k])){
      $reg=preg_replace('#(.*?)\[.*#si','\1',$_TABLE['column']['comment'][$eman][$k]);
      $subreg=explode(':',preg_replace('#.*\[(.*?)\]#si','\1',$_TABLE['column']['comment'][$eman][$k]));
      $field.='(SELECT xx.'.$subreg[1].' FROM '.$subreg[0].' AS xx WHERE x.'.$reg.'=xx.id)';
     }else $field.='x.'.$_TABLE['column']['comment'][$eman][$k];

     $field.=',\''.$_TABLE['column']['separator'][$eman][$k].'\'';
     if($k<count($_TABLE['column']['comment'][$eman])-1) $field.=',';
    }
    $field.=')';

    $table=explode('#',$_TABLE['column']['comment'][$eman][0]);
    $sql='SELECT '.$field.' FROM '.$table[3].' AS x';
    $sql.=' INNER JOIN '.$table[0].' AS x0 ON x0.'.$table[2].'=x.id AND x0.'.$table[1].'='.$fetch['id'];
    if($result_r=mysqli_query($_DB['session'],$sql)) {
     $datas=array();
     while($fetch_r=mysqli_fetch_array($result_r)){
      $datas[]='"'.$fetch_r[0].'"';
     }
     $datas=implode(',',$datas);
     if($datas!='')
      $_MODULE['output'].='['.$datas.']';
     else $_MODULE['output'].='"NULL"';
    } else $KERNEL->alert(mysqli_error($_DB['session']));
   #
   # END OF MULTIPLEX
   #
   } else $_MODULE['output'].='"'.$fetch[$j].'"';
   if($j<count($_MODULE['grid']['field'])) $_MODULE['output'].=',';
  }
  // GRID EXTRA
  if(function_exists('grid_extra')) $_MODULE['output'].=grid_extra($fetch);
  // GRID TOOLTIP
  if(function_exists('grid_tooltip')) $_MODULE['output'].=grid_tooltip($fetch);

  $_MODULE['output'].=']';
  if($i<mysqli_num_rows($result)-1) $_MODULE['output'].=',';
 }
}
$_MODULE['output'].=']';
if(isset($_MODULE['extra'])) $_MODULE['output'].=',extra:['.$_MODULE['extra'].']';
$_MODULE['output'].='}';

?>