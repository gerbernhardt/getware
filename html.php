<?php

#
# Getware: Ultra-Secure Script
# Filename: html.php, 2010/08/04
# Copyright (c) 2004 - 2011 by German Bernhardt
# E-mail: <german.bernhardt@gmail.com>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

class html {

 function font($content,$class) {
  return '<font class="'.$class.'">'.$content.'</font>';
 }

 function table($width,$class,$aux,$level) {
  if($aux!='') $aux=' '.$aux;
  if($width!='') $aux=' width="'.$width.'"'.$aux;
  if($class!='') $aux=' class="'.$class.'"'.$aux;
  $aux='<table border0="1" cellspacing="0" cellpadding="0"'.$aux.'>';
  if($level==2) $aux=$aux.'<tr><td align="center">';
  if($level==1) $aux=$aux.'<tr>';
  return $aux;
 }

 function tr($content) {
  return '<tr>'.$content.'</tr>';
 }

 function td($align,$valign,$width,$class,$aux,$content) {
  if($aux!='') $aux=' '.$aux;
  if($align!='') $aux=' align="'.$align.'"'.$aux;
  if($valign!='') $aux=' valign="'.$valign.'"'.$aux;
  if($width!='') $aux=' width="'.$width.'"'.$aux;
  if($class!='') $aux=' class="'.$class.'"'.$aux;
  $aux='<td'.$aux.'>'.$content.'</td>';
  return $aux;
 }

 function table_close($level) {
  $aux='</table>';
  if($level==2) $aux='</td></tr>'.$aux;
  if($level==1) $aux='</tr>'.$aux;
  return $aux;
 }

 function select($name) {
  return '<select id="'.$name.'" name="'.$name.'">';
 }

 function select_close() {
  return '</select>';
 }

 function option($value,$content,$aux) {
  if($aux!='') $aux=' '.$aux;
  return '<option value="'.$value.'"'.$aux.'>'.$content.'</option>';
 }

 function input($name,$type,$size,$maxlength,$value,$onclick,$aux) {
  global $links;
  if($aux!='') $aux=' '.$aux;
  if($type=='button'&&$onclick!='') {
   return '<input id="'.$name.'" name="'.$name.'" type="button" size="'.$size.'" maxlength="'.$maxlength.'" value="'.$value.'" onclick="'.$onclick.'"'.$aux.' autocomplete="off" />';
  } elseif($type=='checkbox') {
   if($value!=1) $value=0;
   return '<input id="'.$name.'" name="'.$name.'" type="checkbox" value="'.$value.'"  onclick="'.$onclick.'"'.$aux.' />';
  } elseif(preg_match('/id=/',$aux)) {
   return '<input name="'.$name.'" type="'.$type.'" size="'.$size.'" maxlength="'.$maxlength.'" value="'.$value.'"'.$aux.' autocomplete="off" />';
  } else {
   return '<input id="'.$name.'" name="'.$name.'" type="'.$type.'" size="'.$size.'" maxlength="'.$maxlength.'" value="'.$value.'"'.$aux.' autocomplete="off" />';
  }
 }

 function textarea($name,$rows,$cols,$wrap,$class,$aux,$content) {
  if($aux!='') $aux=' '.$aux;
  if($name!='') $aux=' name="'.$name.'"'.$aux;
  if($rows!='') $aux=' rows="'.$rows.'"'.$aux;
  if($cols!='') $aux=' cols="'.$cols.'"'.$aux;
  if($wrap!='') $aux=' wrap="'.$wrap.'"'.$aux;
  if($class!='') $aux=' class="'.$class.'"'.$aux;
  return '<textarea'.$aux.'>'.$content.'</textarea>';
 }

 function img($url,$title) {
  return '<img src="'.$url.'" title="'.$title.'">';
 }

 function a($url,$elementID,$aux,$text,$link) {
  if($aux!='')
   $aux=' '.$aux;
  if($link=='normal')
   return '<a href="'.$url.'"'.$aux.'>'.$text.'</a>';
  elseif($link=='ajax')
   if(preg_match('/remove/',$url))
    return '<a href="#" onclick="javascript:xQuestion(\'?module='.$url.'&amp;ajax\',\''.$elementID.'\');" '.$aux.'>'.$text.'</a>';
   else return '<a href="#" onclick="javascript:xGet(\'?module='.$url.'&amp;ajax\',\''.$elementID.'\');" '.$aux.'>'.$text.'</a>';
  else return '<a href="?module='.$url.'"'.$aux.'>'.$text.'</a>';
 }

 function form($xURL,$xElementId,$xFormId,$link) {
  if($link=='normal')
   return '<form id="'.$xFormId.'" action="'.$xURL.'" method="post">';
  elseif($link=='ajax')
   return '<form id="'.$xFormId.'" method="post">';
  else return '<form id="'.$xFormId.'" action="?module='.$xURL.'" method="post" enctype="multipart/form-data">';
 }

 function form_close() {
  return '</form>';
 }

}

?>