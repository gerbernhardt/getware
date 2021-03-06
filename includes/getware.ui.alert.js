/*
 * Keep It Simple, Stupid!
 * Filename: includes/getware.ui.alert.js
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
var posUI=0;
if(!getware.ui) getware.ui={}

getware.ui.alert={
 
 make:function(json){
  $('div[id=window-message]:ui-dialog').dialog('destroy');
  var data='<p><span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>'+json.data+'</p>';
  $('div[id=window-message]').html(data);
  var title='Alerta';
  if(json.title) title=json.title;

  ok=':function(){';
  ok+=' $(this).dialog(\'close\');';
  ok+=' if(json.reference)';
  ok+='  $(\'div[aria-labelledby$=\'+json.reference+\']\').hide({effect:\'drop\',direction:\'up\'})';
  ok+='}';

  url=':function(){';
  url+=' url=\'module=admin&admin=\'+json.module+\'&\'+json.action;';
  url+='  if(json.blank)';
  url+='   getware.getopen(url);';
  url+='  else getware.get(url);}';

  output='$(\'div[id=window-message]\')';
  output+='.dialog({title:title,modal:true,buttons:{';

  if(!json.module)
   output+='\'Aceptar\''+ok;
  else output+='\''+json.button+'\''+url+','+'\'Cerrar\''+ok;

  output+='}});';
  if(json.exec) eval(json.exec); 
  eval(output);
 }

}