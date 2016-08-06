//
// Getware: Ultra-Secure Script
// Filename: includes/getware.ui.submenu.js, 2012/04/03
// Copyright (c) 2012 by German Bernhardt
// E-mail: <german.bernhardt@gmail.com | german.bernhardt@hotmail.com>
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License.
if(!$getware.ui) $getware.ui={}
$getware.ui.submenu={
 get:function($window,$this,$module,$field_filter){
  if($field_filter!=''){
   if($filter=$('div[id=ui-window-'+$window+'] input[id='+$field_filter+']').val()){
   } else $filter=$('div[id=ui-window-'+$window+'] input[id='+$field_filter.split('x')[0]+'x]').val();
  } else $filter='\%';
  var $url='module=admin&admin='+$module+'&window='+$window+'&submenu='+$this+'&filter='+$filter;
  $url+='&term='+encodeURIComponent($('div[id=ui-window-'+$window+'] input[id='+$this+']').val());
  $getware.get($url);
 },
 make:function($json){
  var $x=$('#window-menu');
  $x.hide();
  $x.html('');
  //$x.css('width',200);
  $x.css('top',$posY+5);
  $x.css('left',$posX+5);
  var $output='';
  for(var $i=0;$i<$json.value.length;$i++) {
   $output+='<a href="#" onclick="javascript:';
   $output+='$(\'div[id=ui-window-'+$json.window+'] input[id='+$json.id+']\').attr(\'value\',\''+$json.value[$i]+'\').trigger(\'change\');';
   if($json.info[$i])
    $output+='$(\'div[id=ui-window-'+$json.window+'] div[id='+$json.id+']\').html(\''+$json.info[$i]+'\');';
   $output+='">'+$json.label[$i]+'</a><br>';
  }
  $x.html($output);
  $x.slideToggle();
  $x.hover(
   function(){$x.show();},
   function(){$x.hide();}
  );
  return false;
 }
}