<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel/query.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();

class kernel_query{

 # EXCEL GENERATOR
 function excel($no_limits = false) {
  global $_DB,$_USER,$_ADMIN,$_TABLE,$_MODULE,$CORE,$sql;
  include('kernel/query.excel.php');
 }

 # CACHE SQL GENERATOR
 function cache() {
  global $_DB,$_USER,$_ADMIN,$_TABLE,$_MODULE,$CORE,$sql;
  include('kernel/query.cache.php');
 }

 # SQL GENERATOR
 function make() {
  global $_DB, $KERNEL, $_USER, $_ADMIN, $_TABLE, $_MODULE, $CORE, $sql;
  include('kernel/query.make.php');
 }

 # SUB SQL GENERATOR
 function subquery($reference,$as=false) {
  //$reference[0]='nf[n0:n0f[n1:n1f[n2:n2f]]]';
  $row=substr($reference,0,strpos($reference,'[')); // nf
  $tables=array();$rows=array();
  $reference=substr($reference,strpos($reference,'[',0)); // [n0:n0f[n1:n1f[n2:n2f]]]

  while(preg_match('/\[*\]/',$reference)){
   $reference=substr($reference,1,-1);// [n2:n2f[n3:n3f]] = n2:n2f[n3:n3f]
   $tables[]=substr($reference,0,strpos($reference,':')); // n2
   $reference=substr($reference,strpos($reference,':')+1,strlen($reference)); // n2f[n3:n3f]
   if(preg_match('/\[*\]/',$reference)){
    $rows[]=substr($reference,0,strpos($reference,'[')); // n2f
    $reference=(substr($reference,strpos($reference,'[',0))); // [n3:n3f]
   }else $rows[]=$reference; //n3f
  }

  $select='';$join='';$where='';
  for($i=count($tables)-1;$i>-1;$i--){
   if($i<count($tables)-1)
    $join.=' INNER JOIN `'.$tables[$i].'` AS xx'.$i.' ON xx'.$i.'.'.$rows[$i].'=xx'.($i+1).'.id';
   else $select.='SELECT DISTINCT  xx'.$i.'.'.$rows[$i].' FROM `'.$tables[$i].'` AS xx'.$i;
  }
  $where.=' WHERE x'.$as.'.'.$row.'=xx0.id';
  return '('.$select.$join.$where.')';
 }
 
}

$KERNEL->query=new kernel_query;

?>