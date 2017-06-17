/*
 * Keep It Simple, Stupid!
 * Filename: includes/getware.ui.content.js
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!getware.ui) getware.ui={}
getware.ui.content={
 aff:{
  id:[],
  inner:'x',
  dblclick:function(window,e){
   var split=e.split('x');
   if(split.length>1){
    if(split[1]>1){
     var upValue=$('div[id=ui-window-'+window+'] #'+split[0]+'x'+(split[1]-1)).val();
     $('div[id=ui-window-'+window+'] #'+e).val(upValue);
    }
   }
  },
  add:function(window){
   if(!getware.ui.content.aff.id[window]) getware.ui.content.aff.id[window]=0;
   getware.ui.content.aff.id[window]++;
   var insertRow=document.getElementById('xRow_'+window).insertRow(-1);
   var insertCell=insertRow.insertCell(-1);
   var temp=getware.ui.content.aff.inner.replace(/(xIDx)+/g,'x'+getware.ui.content.aff.id[window]);
   if(getware.ui.content.aff.id[window]%2==0)
    insertCell.innerHTML=temp.replace(/(xCLASSx)+/g,'list_even');
   else insertCell.innerHTML=temp.replace(/(xCLASSx)+/g,'list_odd');
   $('div[id=ui-window-'+window+']').parent().css('height','auto');
  },
  remove:function(window){
   var deleteRow=document.getElementById('xRow_'+window);
   if(deleteRow.rows.length>2){
    getware.ui.content.aff.id[window]--;
    deleteRow.deleteRow(deleteRow.rows.length-1);
   }
   $('div[id=ui-window-'+window+']').parent().css('height','auto');
  }
 },
 info:{
  grid:function(json){
   var x = '';
   var image = ['error', 'none', 'success'];
   for(i=0; i < json.rows.length; i++) {
    x = $('div[id=overflow-c-' + json.window + '] input[id=' + json.rows[i] + '-' + json.window + ']');
    json.data[i] += 1;
    x.parent().parent().find('input[type=text]').css('background','url(\'./images/input_' + image[json.data[i]] + '.png\')');
   }
  },
  edit:function(json){
   var x='';
   var image=['error','hide','success'];
   for(i=0;i<json.rows.length;i++){
    x=$('div[id=ui-window-'+json.window+'] td[id=img'+json.rows[i]+'] div');
    json.data[i]++;
    x.removeClass();
    if(json.data[i]>1) json.data[i]=2;
    x.addClass('icon icon-'+image[json.data[i]]);
    //x.html('<img src="images/'+image[json.data[i]]+'.png" title="">');
   }
  },
  add:function(json){
   var x='';
   var image=['output','input'];
   for(i=0;i<json.rows.length;i++){
    x=$('div[id=ui-window-'+json.window+'] input[id^='+json.rows[i]+']');
    x.css('background','url(\'./images/'+image[json.data[i]]+'.png\')');
   }
  }
 },
 add:function(date,json){
  return getware.ui.content.edit(date,json);
 },
 edit:function(date,json){
  /*****************
  * MAKE X: ADD & EDIT
  ******************/
  var j=1;
  var rest=0;
  var row=json.ini;
  var output='<table cellspacing="0" cellpadding="0" width="100%"><tr><td valign="top">';
  for(i=0;i<json.type.length;i++){
   if(json.type[i]=='text') rest=rest+4;
   if(rest==json.br){
    output+='</td><td valign="top">';
    json.br=json.br*2;
    j++;
   }
   output+='<table cellspacing="1" cellpadding="1" width="98%"><tr class="list_even">';
   output+='<td class="subtitle" width="110" valign="top" align="left">'+json.name[i]+'</td>';
   output+='<td id="img'+row+'" width="16" valign="top"><div>&nbsp;</div></td>';
   output+='<td valign="top" align="left">';
   var value='';
   if(json.data) value=json.data[i];
   if(json.type[i]=='date')
    output+='<input id="'+row+'date'+date+'" name="'+row+date+'" type="text" size="10" maxlength="10" value="'+value+'" datepicker="'+json.type[i]+'" />';
   if(json.type[i]=='month')
    output+='<input id="'+row+'date'+date+'" name="'+row+date+'" type="text" size="10" maxlength="10" value="'+value+'" datepicker="'+json.type[i]+'" />';
   if(json.type[i]=='year')
    output+='<input id="'+row+'date'+date+'" name="'+row+date+'" type="text" size="10" maxlength="10" value="'+value+'" datepicker="'+json.type[i]+'" />';
   if(json.type[i]=='view')
    output+='<input id="'+row+'" name="'+row+'" type="text" size="25" maxlength="255" class="view" value="'+value+'" view="'+json.type[i]+'" />&nbsp;<img id="ximage'+row+'" src="images/view.png" title="Ver" onclick="javascript:getware.ui.submenu.get(\''+date+'\',\''+row+'\',\''+json.module+'\',\'\');" />';
   if(json.type[i]=='select'){
    output+='<select id="'+row+'" name="'+row+'" multiple="multiple">';
    for(j=0;j<json.data[i][0].length;j++)
     output+='<option value="'+json.data[i][0][j]+'" selected>'+json.data[i][0][j]+'</option>';
    for(j=0;j<json.data[i][1].length;j++)
     output+='<option value="'+json.data[i][1][j]+'">'+json.data[i][1][j]+'</option>';
    output+='</select>';
   }
   if(json.type[i]=='varchar')
    output+='<input id="'+row+'" name="'+row+'" type="text" size="25" maxlength="255" value="'+value+'" />';
   if(json.type[i]=='pass')
    output+='<input id="'+row+'" name="'+row+'" type="password" size="25" maxlength="255" value="" />';
   if(json.type[i]=='text')
    output+='<textarea id="'+row+'" name="'+row+'" wrap="on" cols="22" rows="7">'+value+'</textarea>';
   output+='</td></tr></table>';
   row++;
   rest++;
  }
  output+='</td></tr></table>';
  /*****************
  * END OF ADD & EDIT
  ******************/
  return output;
 },
 query:function(date,json){
  /*****************
  * MAKE X: QUERY
  ******************/
  var j=1;
  var td=15;
  var rest=0;
  var row=json.ini;
  var output='<table cellspacing="0" cellpadding="0" width="100%"><tr><td valign="top">';
  for(i=0;i<json.type.length;i++){
   if(json.type[i]=='text') rest=rest+5;
   if(rest>=td){output+='</td><td valign="top">';j++;td=td*2;}
   output+='<table cellspacing="1" cellpadding="1" width="98%"><tr class="list_even">';
   output+='<td class="subtitle" width="122" valign="top" align="left">'+json.name[i]+'</td>';
   output+='<td id="img'+row+'" width="16" valign="top"><div>&nbsp;</div></td>';
   output+='<td valign="top" align="left">';
   var value='';
   var info='';
   if(json.data) value=json.data[i];
   if(json.info) info=json.info[i];
   if(json.type[i]=='date')
    output+='<input id="'+row+'date'+date+'" name="'+row+'" type="text" size="10" maxlength="10" value="'+value+'" datepicker="'+json.type[i]+'" />'+info;
   if(json.type[i]=='month')
    output+='<input id="'+row+'date'+date+'" name="'+row+'" type="text" size="10" maxlength="10" value="'+value+'" datepicker="'+json.type[i]+'" />'+info;
   if(json.type[i]=='year')
    output+='<input id="'+row+'date'+date+'" name="'+row+'" type="text" size="10" maxlength="10" value="'+value+'" datepicker="'+json.type[i]+'" />+info';
   if(json.type[i]=='view')
    output+='<input id="'+row+'" name="'+row+'" type="text" size="25" maxlength="255" class="view" value="'+value+'" view="'+json.type[i]+'" />&nbsp;<img id="ximage'+row+'" src="images/view.png" title="Ver" onclick="javascript:getware.ui.submenu.get(\''+date+'\',\''+row+'\',\''+json.module+'\',\'\');" />'+info;
   if(json.type[i]=='varchar')
    output+='<input id="'+row+'" name="'+row+'" type="text" size="25" maxlength="255" value="'+value+'" />'+info;
   if(json.type[i]=='pass')
    output+='<input id="'+row+'" name="'+row+'" type="password" size="25" maxlength="255" value="" />'+info;
   if(json.type[i]=='text')
    output+='<textarea id="'+row+'" name="'+row+'" wrap="on" cols="22" rows="7">'+value+'</textarea>'+info;
   output+='</td></tr></table>';
   row++;
   rest++;
  }
  /*****************
  * END OF QUERY
  ******************/
  return output;
 },
 qxx:function(date,json){
  /*****************
  * MAKE XX: QUERY
  ******************/
  var br=0;
  var row=json.ini;
  var head=json.head;
  var output='<table id="xTitle" cellspacing="0" cellpadding="0" width="98%"><tr>';
  // ROW
  output+='<table id="xRow_'+date+'" cellspacing="0" cellpadding="0" width="98%"><tr><td>';

  output+='<table cellspacing="1" cellpadding="1"><tr class="list_even">';
  for(i=0;i<json.name.length&&head>row;i++){
   var value=json.data[i];

   if(json.type[i]=='text'){
   	output+='</tr></table>'
   	output+='<table cellspacing="1" cellpadding="1"><tr class="list_even">';
   	output+='<textarea id="'+row+'x" name="'+row+'x" placeholder="'+json.name[i]+'" wrap="on" style="width:98%;height:150px;">'+value+'</textarea>';
   	output+='</tr></table>'
   	output+='<table cellspacing="1" cellpadding="1"><tr class="list_even">';
   }
   if(json.type[i]!='text') output+='<td width="'+json.size[i]+'%" valign="top" align="left">';
   if(json.type[i]=='select'){
    output+='<select id="'+row+'x" name="'+row+'x" multiple="multiple">';
    for(j=0;j<value[0].length;j++)
     output+='<option value="'+value[0][j]+'" selected>'+value[0][j]+'</option>';
    for(j=0;j<value[1].length;j++)
     output+='<option value="'+value[1][j]+'">'+value[1][j]+'</option>';
    output+='</select>';
   }
   if(json.type[i]!='text'&&json.type[i]!='textbox'&&json.type[i]!='select') output+='<input id="'+row+'x" name="'+row+'x" placeholder="'+json.name[i]+'" ';
   if(json.type[i]=='date') output+='type="text" maxlength="10" class="date" value="'+value+'" datepicker="'+json.type[i]+'" />';
   if(json.type[i]=='year') output+='type="text" maxlength="10" class="date" value="'+value+'" datepicker="'+json.type[i]+'" />';
   if(json.type[i]=='view') output+='type="text" maxlength="255" class="view" value="'+value+'" view="'+json.type[i]+'" />&nbsp;<img id="ximage'+row+'x" src="images/view.png" title="Ver" onclick="javascript:getware.ui.submenu.get(\''+date+'\',\''+row+'x\',\''+json.module+'\',\'\');">';
   if(json.type[i]=='varchar') output+='type="text" maxlength="255" class="normal" value="'+value+'" />';
   if(json.type[i]=='pass') output+='type="password" maxlength="255" class="normal" value="" />';
   if(json.type[i]=='check') output+='type="checkbox" value="'+value+'" onclick="javascript:if(this.checked==true) this.value=\'SI\'; else this.value=\'NO\';" />';

   if(json.type[i]!='text'&&json.type[i]!='textbox') output+='</td>';
   row++;
  }
  output+='</tr></table>';

  getware.ui.content.aff.inner='';
  head=json.head-json.ini;
  for(i=head;i<json.name.length;i++){
   if(br==json.br) br=0;
   if(br==0) getware.ui.content.aff.inner+='<table cellspacing="1" cellpadding="1"><tr class="xCLASSx">';
   getware.ui.content.aff.inner+='<td width="'+json.size[i]+'%" valign="top" align="left">';
   value=json.data[i];

   if(json.type[i]!='text'&&json.type[i]!='textbox'&&json.type[i]!='select')
    getware.ui.content.aff.inner+='<input id="'+row+'xIDx" name="'+row+'xIDx" placeholder="'+json.name[i]+'" ';

   if(json.type[i]=='file')
    getware.ui.content.aff.inner+='type="file" value="'+value+'" />';
   if(json.type[i]=='date')
    getware.ui.content.aff.inner+='type="text" maxlength="10" class="date" value="'+value+'" ondblclick="javascript:getware.ui.content.aff.dblclick('+date+',\''+row+'xIDx\');" datepicker="'+json.type[i]+'" />';
   if(json.type[i]=='year')
    getware.ui.content.aff.inner+='type="text" maxlength="10" class="date" value="'+value+'" ondblclick="javascript:getware.ui.content.aff.dblclick('+date+',\''+row+'xIDx\');" datepicker="'+json.type[i]+'" />';
   if(json.type[i]=='view'){
    getware.ui.content.aff.inner+='type="text" maxlength="255" class="view" value="'+value+'" filter="'+json.filter[i]+'" ondblclick="javascript:getware.ui.content.aff.dblclick('+date+',\''+row+'xIDx\');" view="'+json.type[i]+'" />&nbsp;';
    getware.ui.content.aff.inner+='<img id="ximage'+row+'xIDx" src="images/view.png" title="Ver" onclick="javascript:getware.ui.submenu.get(\''+date+'\',\''+row+'xIDx\',\''+json.module+'\',\''+json.filter[i]+'xIDx\');" />';
   }
   if(json.type[i]=='varchar')
    getware.ui.content.aff.inner+='type="text" maxlength="255" class="normal" value="'+value+'" ondblclick="javascript:getware.ui.content.aff.dblclick('+date+',\''+row+'xIDx\');" />';
   if(json.type[i]=='pass')
    getware.ui.content.aff.inner+='type="password" maxlength="255" class="normal" value="" />';

   if(json.type[i]=='select'){
    getware.ui.content.aff.inner+='<select id="'+row+'xIDx" name="'+row+'xIDx" multiple="multiple">';
    for(j=0;j<value[1].length;j++)
     getware.ui.content.aff.inner+='<option value="'+value[1][j]+'">'+value[1][j]+'</option>';
    getware.ui.content.aff.inner+='</select>';
   }

   if(json.type[i]=='text')
    getware.ui.content.aff.inner+='<input id="'+row+'xIDx" name="'+row+'xIDx" type="text" maxlength="255" class="normal" value="'+value+'" ondblclick="javascript:getware.ui.content.aff.dblclick('+date+',\''+row+'xIDx\');" />';

   if(json.type[i]=='textbox')
   	getware.ui.content.aff.inner+='<textarea id="'+row+'xIDx" name="'+row+'xIDx" placeholder="'+json.name[i]+'" wrap="on" style="width:98%;height:50px;" ondblclick="javascript:getware.ui.content.aff.dblclick('+date+',\''+row+'xIDx\');">'+value+'</textarea>';

   if(json.type[i]=='check')
    getware.ui.content.aff.inner+='<input id="'+row+'xIDx" name="'+row+'xIDx" type="checkbox" value="'+value+'" onclick="javascript:if(this.checked==true) this.value=\'SI\'; else this.value=\'NO\';" />';

   getware.ui.content.aff.inner+='<div id="'+row+'xIDx" name="'+row+'xIDx"></div></td>';
   if(br==json.br) getware.ui.content.aff.inner+='</tr></table>';
   row++;
   br++;
  }
  output+='</td></tr></table>';
  output+='<p align="center">';
  output+='<input type="button" value="Agregar" onclick="javascript:getware.ui.content.aff.add('+date+');'+json.afterAppend+'(\''+json.module+'\','+date+');" />';
  output+='<input type="button" value="Eliminar" onclick="javascript:getware.ui.content.aff.remove('+date+');" />';
  output+='</p>';
  /*****************
  * END OF QUERY XX
  ******************/
  return output;
 },
 uxx:function(date,json){
  /*****************
  * MAKE XX: UPLOAD
  ******************/
  // ROW
  var output='<form id="'+date+'">';
  output+='<table id="xRow_'+date+'" cellspacing="0" cellpadding="0" width="98%"><tr><td>';
  getware.ui.content.aff.inner='';
  getware.ui.content.aff.inner+='<table cellspacing="1" cellpadding="1"><tr class="xCLASSx">';
  getware.ui.content.aff.inner+='<td width="'+json.size[0]+'%" valign="top" align="left">';
  getware.ui.content.aff.inner+=json.name[0]+'<br>';
  getware.ui.content.aff.inner+='<input id="0xIDx" name="0xIDx" type="file" class="file" value="" />';
  getware.ui.content.aff.inner+='</td>';
  output+='</td></tr></table>';
  output+='</form>';
  output+='<p align="center">';
  output+='<input type="button" value="Agregar" onclick="javascript:getware.ui.content.aff.add('+date+');'+json.afterAppend+'(\''+json.module+'\','+date+');" />';
  output+='<input type="button" value="Eliminar" onclick="javascript:getware.ui.content.aff.remove('+date+');" />';
  output+='</p>';
  /*****************
  * END OF UPLOAD XX
  ******************/
  return output;
 },
 axx:function(date,json){
  /*****************
  * MAKE XX: ADD
  ******************/
  var br=0;
  var row=json.ini;
  var head=json.head;
  var output='<table id="xTitle" cellspacing="0" cellpadding="0" width="98%"><tr>';
  // ROW
  output+='<table id="xRow_'+date+'" cellspacing="0" cellpadding="0" width="98%"><tr><td>';

  output+='<table cellspacing="1" cellpadding="1"><tr class="list_even">';
  for(i=0;i<json.name.length&&head>row;i++){
   var value=json.data[i];

   if(json.type[i]=='text'){
   	output+='</tr></table>'
   	output+='<table cellspacing="1" cellpadding="1"><tr class="list_even">';
   	output+='<textarea id="'+row+'x" name="'+row+'x" placeholder="'+json.name[i]+'" wrap="on" style="width:98%;height:150px;">'+value+'</textarea>';
   	output+='</tr></table>'
   	output+='<table cellspacing="1" cellpadding="1"><tr class="list_even">';
   }
   if(json.type[i]!='text') output+='<td width="'+json.size[i]+'%" valign="top" align="left">';
   if(json.type[i]=='select'){
    output+='<select id="'+row+'x" name="'+row+'x" multiple="multiple">';
    for(j=0;j<value[0].length;j++)
     output+='<option value="'+value[0][j]+'" selected>'+value[0][j]+'</option>';
    for(j=0;j<value[1].length;j++)
     output+='<option value="'+value[1][j]+'">'+value[1][j]+'</option>';
    output+='</select>';
   }
   if(json.type[i]!='text'&&json.type[i]!='select') output+='<input id="'+row+'x" name="'+row+'x" placeholder="'+json.name[i]+'" ';
   if(json.type[i]=='date') output+='type="text" maxlength="10" class="date" value="'+value+'" datepicker="'+json.type[i]+'" />';
   if(json.type[i]=='year') output+='type="text" maxlength="10" class="date" value="'+value+'" datepicker="'+json.type[i]+'" />';
   if(json.type[i]=='view') output+='type="text" maxlength="255" class="view" value="'+value+'" view="'+json.type[i]+'" />&nbsp;<img id="ximage'+row+'x" src="images/view.png" title="Ver" onclick="javascript:getware.ui.submenu.get(\''+date+'\',\''+row+'x\',\''+json.module+'\',\'\');">';
   if(json.type[i]=='varchar') output+='type="text" maxlength="255" class="normal" value="'+value+'" />';
   if(json.type[i]=='pass') output+='type="password" maxlength="255" class="normal" value="" />';
   if(json.type[i]=='check') output+='type="checkbox" value="'+value+'" onclick="javascript:if(this.checked==true) this.value=\'SI\'; else this.value=\'NO\';" />';

   if(json.type[i]!='text') output+='</td>';
   row++;
  }
  output+='</tr></table>';

  getware.ui.content.aff.inner='';
  head=json.head-json.ini;
  for(i=head;i<json.name.length;i++){
   if(br==json.br) br=0;
   if(br==0) getware.ui.content.aff.inner+='<table cellspacing="1" cellpadding="1"><tr class="xCLASSx">';
   getware.ui.content.aff.inner+='<td width="'+json.size[i]+'%" valign="top" align="left">';
   value=json.data[i];

   if(json.type[i]!='select')
    getware.ui.content.aff.inner+='<input id="'+row+'xIDx" name="'+row+'xIDx" placeholder="'+json.name[i]+'" ';

   if(json.type[i]=='file')
    getware.ui.content.aff.inner+='type="file" value="'+value+'" />';
   if(json.type[i]=='date')
    getware.ui.content.aff.inner+='type="text" maxlength="10" class="date" value="'+value+'" ondblclick="javascript:getware.ui.content.aff.dblclick('+date+',\''+row+'xIDx\');" datepicker="'+json.type[i]+'" />';
   if(json.type[i]=='year')
    getware.ui.content.aff.inner+='type="text" maxlength="10" class="date" value="'+value+'" ondblclick="javascript:getware.ui.content.aff.dblclick('+date+',\''+row+'xIDx\');" datepicker="'+json.type[i]+'" />';
   if(json.type[i]=='view'){
    getware.ui.content.aff.inner+='type="text" maxlength="255" class="view" value="'+value+'" filter="'+json.filter[i]+'" ondblclick="javascript:getware.ui.content.aff.dblclick('+date+',\''+row+'xIDx\');" view="'+json.type[i]+'" />&nbsp;';
    getware.ui.content.aff.inner+='<img id="ximage'+row+'xIDx" src="images/view.png" title="Ver" onclick="javascript:getware.ui.submenu.get(\''+date+'\',\''+row+'xIDx\',\''+json.module+'\',\''+json.filter[i]+'xIDx\');" />';
   }
   if(json.type[i]=='varchar')
    getware.ui.content.aff.inner+='type="text" maxlength="255" class="normal" value="'+value+'" ondblclick="javascript:getware.ui.content.aff.dblclick('+date+',\''+row+'xIDx\');" />';
   if(json.type[i]=='pass')
    getware.ui.content.aff.inner+='type="password" maxlength="255" class="normal" value="" />';

   if(json.type[i]=='select'){
    getware.ui.content.aff.inner+='<select id="'+row+'xIDx" name="'+row+'xIDx" multiple="multiple">';
    for(j=0;j<value[1].length;j++)
     getware.ui.content.aff.inner+='<option value="'+value[1][j]+'">'+value[1][j]+'</option>';
    getware.ui.content.aff.inner+='</select>';
   }

   if(json.type[i]=='text')
    getware.ui.content.aff.inner+='<input id="'+row+'xIDx" name="'+row+'xIDx" type="text" maxlength="255" class="normal" value="'+value+'" ondblclick="javascript:getware.ui.content.aff.dblclick('+date+',\''+row+'xIDx\');" />';
   if(json.type[i]=='check')
    getware.ui.content.aff.inner+='<input id="'+row+'xIDx" name="'+row+'xIDx" type="checkbox" value="'+value+'" onclick="javascript:if(this.checked==true) this.value=\'SI\'; else this.value=\'NO\';" />';

   getware.ui.content.aff.inner+='<div id="'+row+'xIDx" name="'+row+'xIDx"></div></td>';
   if(br==json.br) getware.ui.content.aff.inner+='</tr></table>';
   row++;
   br++;
  }
  output+='</td></tr></table>';
  output+='<p align="center">';
  output+='<input type="button" value="Agregar" onclick="javascript:getware.ui.content.aff.add('+date+');'+json.afterAppend+'(\''+json.module+'\','+date+');" />';
  output+='<input type="button" value="Eliminar" onclick="javascript:getware.ui.content.aff.remove('+date+');" />';
  output+='</p>';
  /*****************
  * END OF ADD XX
  ******************/
  return output;
 },
 render:function(json,window){
  if(!json.module)
   module=json;
  else module=json.module;
  $('select').multiselect({minWidth:195});
  $('div[id=ui-window-'+window+']').find('input[type=text][view]').each(function(){
   if(filter=$('div[id=ui-window-'+window+'] input[id='+$(this).attr('filter')+']').val()){
   }else filter=$('div[id=ui-window-'+window+'] input[id='+$(this).attr('filter')+'x]').val();
   var url='index.php?module=admin&admin='+module+'&autocomplete='+this.id+'&filter='+filter+'&ajax='

   $(this).autocomplete({
    source:url,
    minLength:0,
    select:function(event,ui){
     $(this).val(ui.item.value);
     if(ui.item.info){
      $('div[id='+this.id+']').html(ui.item.info);
     }
     return false;
    }
   });
  });
 }
}
