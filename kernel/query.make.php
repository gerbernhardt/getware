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

 if(!isset($_MODULE['query']['order']))
  $_MODULE['query']['order']='x.id';

 if(!isset($_MODULE['query']['sort']))
  $_MODULE['query']['sort']='DESC';

 if(!isset($_MODULE['restrict'][_VIEW]))
  $_MODULE['restrict'][_VIEW]=true;
 if(!isset($_MODULE['restrict'][_SEARCH][_VIEW]))
  $_MODULE['restrict'][_SEARCH][_VIEW]=true;


 # INI QUERY
 $_MODULE['query']['select']='SELECT x.id,';
 $_MODULE['query']['from']=' FROM '.$_TABLE['name'].' AS x';
 $as=0;
 $_MODULE['query']['join']='';
 $_MODULE['query']['where']=' WHERE ';

 for($i=0;$i<count($_MODULE['grid']['field']);$i++) {
  $x=$_TABLE['column']['eman'][$_MODULE['grid']['field'][$i]];
  if($_TABLE['column']['function'][$x]=='REFERENCE') {
   #
   # MULTIPLEX  SELECT
   #
   // CAMBIA TEMPORALMENTE EL REFERENCE EN EL MODULO
   if(isset($_MODULE['grid']['reference'][$_MODULE['grid']['name'][$i]])){
    $_AUX['comment']=$_TABLE['column']['comment'][$x];
    $_AUX['separator']=$_TABLE['column']['separator'][$x];
    if(isset($_MODULE['grid']['separator'][$_MODULE['grid']['name'][$i]])){
     $_TABLE['column']['separator'][$x]=$_MODULE['grid']['separator'][$_MODULE['grid']['name'][$i]];
    }else{
     for($j=1;$j<count($_TABLE['column']['comment'][$x]);$j++)
      $_TABLE['column']['separator'][$x][$j]='';
    }
    $_TABLE['column']['comment'][$x]=$_MODULE['grid']['reference'][$_MODULE['grid']['name'][$i]];
   }

   $_MODULE['query']['select'].='CONCAT(';
   for($j=1;$j<count($_TABLE['column']['comment'][$x]);$j++) {
    // SUB REGISTROS
    if(preg_match('/\[*\]/',$_TABLE['column']['comment'][$x][$j])){
     $_MODULE['query']['select'].=$this->subquery($_TABLE['column']['comment'][$x][$j],$as);
    }else $_MODULE['query']['select'].='x'.$as.'.'.$_TABLE['column']['comment'][$x][$j];

    $_MODULE['query']['select'].=',\''.$_TABLE['column']['separator'][$x][$j].'\'';
    if($j<count($_TABLE['column']['comment'][$x])-1){
     $_MODULE['query']['select'].=',';
    }

   }// END FOR
   $_MODULE['query']['select'].=') AS \''.$_TABLE['column']['name'][$x].'\'';
   // VUELVE EL REFERENCE ANTERIOR
   if(isset($_AUX)){
    $_TABLE['column']['comment'][$x]=$_AUX['comment'];
    $_TABLE['column']['separator'][$x]=$_AUX['separator'];
    unset($_AUX);
   }

   $_MODULE['query']['join'].=' LEFT JOIN '.$_TABLE['column']['comment'][$x][0].' AS x'.$as.' ON x.'.$_MODULE['grid']['field'][$i].'=x'.$as.'.id';
   $as++;

  } elseif(isset($_MODULE['grid']['sql'][$_MODULE['grid']['name'][$i]])) {
   $_MODULE['query']['select'].=$_MODULE['grid']['sql'][$_MODULE['grid']['name'][$i]];
  } else $_MODULE['query']['select'].='x.'.$_MODULE['grid']['field'][$i];
  #
  # END OF REFERENCE
  #

  if($i<count($_MODULE['grid']['field'])-1) $_MODULE['query']['select'].=',';
 }

 $as=0;
 if(isset($_POST['search'])) {
  for($i=0;$i<count($_MODULE['search']['field']);$i++) {

   $x=$_TABLE['column']['eman'][$_MODULE['search']['field'][$i]];
   if($_TABLE['column']['function'][$x]=='REFERENCE') {
    #
    # MULTIPLEX  SEARCH
    #
    // CAMBIA TEMPORALMENTE EL REFERENCE PARA EL WHERE
    if(isset($_MODULE['search']['reference'][$_MODULE['search']['name'][$i]])){
     $_AUX['comment']=$_TABLE['column']['comment'][$x];
     $_AUX['separator']=$_TABLE['column']['separator'][$x];

     if(isset($_MODULE['search']['separator'][$_MODULE['search']['name'][$i]])){
      $_TABLE['column']['separator'][$x]=$_MODULE['search']['separator'][$_MODULE['search']['name'][$i]];
     }else{
      for($j=1;$j<count($_TABLE['column']['comment'][$x]);$j++)
       $_TABLE['column']['separator'][$x][$j]='';
     }
     $_TABLE['column']['comment'][$x]=$_MODULE['search']['reference'][$_MODULE['search']['name'][$i]];
    }
    $field='CONCAT(';
    for($j=1;$j<count($_TABLE['column']['comment'][$x]);$j++) {
     // SUB REGISTROS
     if(preg_match('/\[*\]/',$_TABLE['column']['comment'][$x][$j])){
      $reference=array($as,$_TABLE['column']['comment'][$x][$j]);
      $field.=$this->subquery($_TABLE['column']['comment'][$x][$j],$as);
     }else $field.='x'.$as.'.'.$_TABLE['column']['comment'][$x][$j];
     $field.=',\''.$_TABLE['column']['separator'][$x][$j].'\'';
     if($j<count($_TABLE['column']['comment'][$x])-1) $field.=',';
    }
    $field.=')';
    // VUELVE EL REFERENCE ANTERIOR
    if(isset($_AUX)){
     $_TABLE['column']['comment'][$x]=$_AUX['comment'];
     $_TABLE['column']['separator'][$x]=$_AUX['separator'];
     unset($_AUX);
    }

    $as++;
   } elseif(isset($_MODULE['search']['sql'][$_MODULE['search']['name'][$i]])) {
    $field=$_MODULE['search']['sql'][$_MODULE['search']['name'][$i]];
   } else $field='x.'.$_MODULE['search']['field'][$i];

   if(isset($_POST['search'][$i])&&$_POST['search'][$i]!='') {
    if($_TABLE['column']['type'][$x]=='date') $_POST['search'][$i]=$CORE->setdate($_POST['search'][$i]);
    $_MODULE['query']['where'].=$field.' LIKE \''.$_POST['search'][$i].'\' AND ';
   }

   if($i==(count($_MODULE['search']['field'])-1)) {
    $_MODULE['query']['where'].=$_MODULE['restrict'][_VIEW];
   }
  }
 } else $_MODULE['query']['where'].=$_MODULE['restrict'][_VIEW];

 $_MODULE['query']['order']=' ORDER BY '.$_MODULE['query']['order'].' '.$_MODULE['query']['sort'];

 $rows=20;
 if(isset($_GET['rows']))
  $rows=$_GET['rows'];

 $start=0;
 if(isset($_GET['start']))
  $start=$_GET['start']*$rows;

 $_MODULE['query']['limit']=' LIMIT '.$start.','.$rows;

 $sql=$_MODULE['query']['select'];
 $sql.=$_MODULE['query']['from'];
 $sql.=$_MODULE['query']['join'];
 $sql.=$_MODULE['query']['where'];
 $sql.=$_MODULE['query']['order'];
 $sql.=$_MODULE['query']['limit'];
 //exit($sql);
 $this->cache();
}

?>