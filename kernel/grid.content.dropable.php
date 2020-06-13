<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel/grid.content.dropable.php
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
if(isset($_MODULE['grid']['edit'])) $_MODULE['grid']['edit']=$this->$this->make_array($_MODULE['grid']['edit']);
$_MODULE['output'].='{run:"$getGridDropable.make",module:"'.$_GET['admin'].'",window:"td[id=content_center]",append:"'.$append.'",';

$type='content_center';
$pattern = array('/-/', '/_/');
$replacement = array(' > ', ' ');
if(!isset($_MODULE['title'])) $_MODULE['title'] = preg_replace($pattern, $replacement, $_TABLE['name']);
$_MODULE['output'].='title:"'.strtoupper($_MODULE['title']).'",';
$_MODULE['output'].='menu:'.$this->$this->menu().',';
# INIT TITLE
$_MODULE['output'].='columns:[';
for($i=0;$i<count($_MODULE['grid']['name']);$i++) {
 $_MODULE['output'].='{name:"'.utf8_encode(ucwords($_MODULE['grid']['name'][$i])).'",';
 $_MODULE['output'].='field:"'.utf8_encode($_MODULE['grid']['field'][$i]).'",';
 if(isset($_MODULE['grid']['edit'][$_MODULE['grid']['field'][$i]])) {
  $sync=$_TABLE['column']['eman'][$_MODULE['grid']['field'][$i]];
  $_MODULE['output'].='edit:"'.$this->row_type($sync).'",';
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
  $_MODULE['output'].='['.$fetch[0].',';
  for($j=1;$j<=count($_MODULE['grid']['field']);$j++) {
   if($fetch[$j]=='') $fetch[$j]='NULL';
   $_MODULE['output'].='"'.$fetch[$j].'"';
   if($j<count($_MODULE['grid']['field'])) $_MODULE['output'].=',';
  }
  if(function_exists('$this->extra'))
   $_MODULE['output'].=$this->extra($fetch);
  $_MODULE['output'].=']';
  if($i<mysqli_num_rows($result)-1) $_MODULE['output'].=',';
 }
}
$_MODULE['output'].=']';
if(isset($_MODULE['extra']))
 $_MODULE['output'].=',extra:['.$_MODULE['extra'].']';
$_MODULE['output'].='}';

?>