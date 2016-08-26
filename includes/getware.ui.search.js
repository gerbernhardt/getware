/*
 * Keep It Simple, Stupid!
 * Filename: includes/getware.ui.search.js
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!getware.ui) getware.ui={}
getware.ui.search={
 form:function(form){
  var string='window='+form.substr(5,13)+'&';
  var x=$('form[id='+form+'] input[type=text]').each(function(){
   string+='search[]='+encodeURIComponent(this.value)+'&';
  });
  return string;
 },

 make:function(json){
  var date=new Date();
  date=date.getTime();
  getware.ui.date=date; // PARA QUE TENGA CONECTIVIDAD CON EL NAVBAR
  var output=getware.table.open('search-toolbar');
  output+='<form id="form_search_'+date+'" method="post">';
  output+='<table cellspacing="0" cellpadding="0" width="100%">';
  /*
  // INI TITLE
  output+='<tr>';
  for(i=0;i<=json.name.length;i++){
   if(i<json.name.length)
    output+='<td class="subtitle" width="'+json.size[i]+'%" valign="top" align="left">'+json.name[i]+'</td>';
   else output+='<td class="subtitle" width="'+json.size[i]+'%" valign="top" align="left">&nbsp;</td>';
  }
  output+='</tr>';
  */
  // INI DATA INPUT
  output+='<tr>';
  for(i=0;i<=json.name.length;i++){
   if(i<json.name.length) {
    output+='<td valign="top" align="left">';
     output+='<input id="'+i+'" type="text" class="search" value="'+json.data[i]+'" placeholder="'+json.name[i]+'" />'; //placeholder="'+json.name[i]+'"
    output+='</td>';
   } else {
    output+='<td valign="top" align="left" width="64">';
   	output+='<input type="button" title="Buscar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="javascript:getware.get(\'module=admin&amp;admin='+json.module+'\',getware.ui.search.form(\'form_search_'+date+'\'));" value="Buscar">';
    output+='</td>';
   }
  }
  output+='</tr>';

  output+='</table>';
  output+='</form>';
  output+=getware.table.close();
  $(json.window).html(output);
  $(json.window+' input[type=text]').each(function(){
   url='index.php?module=admin&admin='+json.module+'&search='+this.id+'&ajax'
   $(this).autocomplete({source:url,minLength:0});
  });
  return false;
 }
}