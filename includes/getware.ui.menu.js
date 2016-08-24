//
// Getware: Ultra-Secure Script
// Filename: includes/getware.ui.menu.js, 2012/04/03
// Copyright (c) 2012 by German Bernhardt
// E-mail: <german.bernhardt@gmail.com | german.bernhardt@hotmail.com>
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License.
if(!getware.ui) getware.ui={}
getware.ui.menu={
 debug:false,
 action:function(module,date,action,type,blank){
  var id='',url='module=admin&admin='+module+'&'+action;
  // SI ES ADD O UPLOAD ENVIA Y SALE
  if(action=='add'||action=='upload'||action=='excel'||type=='true'){
   if(blank=='true')
    getware.getopen(url);
   else getware.get(url);
   return false;
  }else{ // SINO EDIT, VIEW, PRINT, MAKE,ETC
   // SELECCIONA TODOS LOS CHECBOX DEL GRID
   var rows=$('div[grid='+date+'] div[id=content] input[type=checkbox][checked]');
   for(i=0;i<rows.length;i++){id+=rows[i].id.split('-')[0];if(i<rows.length-1) id+=',';}
   if(id=='') return false; // SI NO HAY ID'S SELECCIONADOS NA OREALIZA NINGUNA ACCION
   if(blank=='true'){
    getware.getopen(url+'='+id);
   } else {
    if(action=='save')
     getware.get(url+'='+id,getware.ui.grid.form(date));
    else getware.get(url+'='+id);
   }
  }
  return false;
 },
 make:function(module,date,options){
  var x=$('#window-menu');
  x.hide();
  x.html('');
  //x.css('width',150);
  x.css('top',posY-5);
  x.css('left',posX-5);
  x.addClass('ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all');
  var output='';
  for(var i=0;i<options.data.length;i++){
   output+='<div class="menu-h">';
   output+='<a href="#" onclick="javascript:getware.ui.menu.action(\'';
   if(options.module[i])
    output+=options.module[i];
   else output+=module;
   output+='\','+date+',\''+options.cmd[i]+'\',\''+options.type[i]+'\',';
   output+='\''+options.blank[i]+'\');" role="button" class="ui-corner-all" href="#"><div class="icon icon-'+options.img[i]+'">';
   output+='&nbsp;</div><span class="menu">'+options.data[i]+'</span></a></div>';
  }
  x.html(output);
  x.slideToggle();
  x.hover(
   function(){x.show();},
   function(){x.hide();}
  );
  return false;
 }
}