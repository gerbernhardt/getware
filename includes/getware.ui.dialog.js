/*
 * Keep It Simple, Stupid!
 * Filename: includes/getware.ui.dialog.js
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!getware.ui) getware.ui={}
getware.ui.dialog={
 form:function(form){
  var string='window='+form+'&';
  var x=$('div[id=ui-window-'+form+']').find('textarea,input[type!=button],select').each(function(){
   if(this.type!='checkbox'||this.checked==true){
    if(this.type=='select-multiple'){ //SELECT
     if(/x/.test(this.id)){ // AXX
      split=this.id.split('x');
      post=split[0]+'['+split[1]+'][]';
     }else{
      post=this.id+'[]';   //EDIT && ADD
     }
    }else if(/x/.test(this.id)){ //AXX SEND
     split=this.id.split('x');
     post=split[0]+'['+split[1]+']';
    }else if(/-/.test(this.id)){ //AXX SEND
     post=this.id.split('-')[0]+'[]';
    }else if(/dat/.test(this.id)){ //DATE SEND
     post=parseInt(this.id);
    }else var post=this.id;// NORMAL SEND

    if(this.type=='select-multiple'){
     $(this).find('option:selected').each(function(){
      string+=post+'='+encodeURIComponent(this.value)+'&';
     });
    }else{
     string+=post+'='+encodeURIComponent(this.value)+'&';
    }
   }
  });
  return string;
 },

 make:function(json){
  /*
   * MAKE HTML
   */
  var date=new Date();
  var date=date.getTime();
  if(!json.width) json.width=640;
  if(!json.blank) json.blank=false;

  var action=json.append.split('\.')[1]+'=save'; // XXX.YYYY -> &YYY=SAVE&
  if(json.append=='edit'){
   action=json.append+'='+json.id+'&save'; // SI EXISTE ID EDIT FUNCTION
  }else if(json.append=='add'){
   action='add=save'; // SI EXISTE ID EDIT FUNCTION
  }else if(json.append=='axx'){
   action='add=save'; // SI EXISTE ID EDIT FUNCTION
  }else if(json.append=='uxx'){
   action='upload='+json.id+'&save';
  }else if(json.append=='query'||json.append=='qxx'){
   if(json.query)
    action=json.query;
   else action='query=run'; // SI EXISTE ID EDIT FUNCTION
  }
  if(json.moduleAction)
   url='module=admin&admin='+json.moduleAction+'&';
  else url='module=admin&admin='+json.module+'&';

  if(json.action)
   url+=json.action+'&reference='+date;
  else url+=action+'&reference='+date;

  if(!json.buttom) json.buttom=false;
  if(!json.simulation) json.simulation=false;
  if(!json.pageOrientation) json.pageOrientation='landscape';
  var output='<div id="ui-window-'+date+'" pageOrientation="'+json.pageOrientation+'">';
  // SI ENCUENTRA UN PUNTO "." EJECUTA
  // -> myProject.myModule.myAppend(date,json)
  // else: getware.getware.ui.content.edit(date,json)
  if(/\./.test(json.append))
   var append=eval(json.append+'(date,json);');
  else var append=eval('getware.ui.content.'+json.append+'(date,json);');
  /*
   * WINDOW.OPENER FEED-BACK!!!
   */
  if(json.blank){
   var form='<form id="form_'+date+'" action="index.php?'+url+'&ajax" enctype="multipart/form-data" method="post" target="popup"';
   var parameters='';
   if(json.opener){
    if(!json.blank_width&&!json.blank_height){
     json.blank_width=200;
     json.blank_height=200;
    }
    parameters+='width='+json.blank_width+',height='+json.blank_height+',top=0,left=0,location=no,resizable=no,'
    parameters+='menubar=no,toolbar=no,directories=no,scrollbars=no,status=no,visible=none';
   }
   form+=' onsubmit="window.open(\'\',\'popup\',\''+parameters+'\')">';
   form+=append+'</form>';
   append=form;
  }
  output+=append;
  output+='</div>';
  $('div[id=window-dialog]').append(output);
  getware.ui.dialog.init(json.module,date,json.title,json.width,url,json.blank,json.buttom,json.simulation);
  if(json.afterAppend) eval(json.afterAppend+'(json,date)')
 },

 makeButtons:function(buttom){
  ok='\'Aceptar\':function(){$(this).dialog(\'close\');}';
  pdf='\'Imprimir\':function(){makePDF(module,date);}';
  print='\'Imprimir\':function(){print();}';
  addfile='\'Adjuntar Archivo\':function(){getware.get(url.replace(/&edit=/gi,\'&upload=\').replace(/&save/gi,\'\'));}';
  upload='\'Subir Archivo\':function(){getware.getfile(url,date);}';
  close='\'Cerrar\':function(){$(this).dialog(\'close\');}';
  save='\'Guardar\':function(){getware.get(url,getware.ui.dialog.form(date));}';

  send='\'Enviar\':function(){\n';
  send+=' if(blank==true)\n';
  send+='  $(\'form[id=\'+date+\']\').submit();\n';
  send+=' else getware.get(url,getware.ui.dialog.form(date));\n';
  send+='}';

  view='\'Ver\':function(){\n';
  view+=' if(blank==true)\n';
  view+='  $(\'form[id=\'+date+\']\').submit();\n';
  view+=' else getware.get(url,getware.ui.dialog.form(date));\n';
  view+='}';


  output='ui.dialog({buttons:{';
  if(buttom=='ok') output+=ok;
  else if(buttom=='pdf') output+=pdf+','+close;
  else if(buttom=='print') output+=print+','+close;
  else if(buttom=='addfile') output+=save+','+addfile+','+close;
  else if(buttom=='upload') output+=upload+','+close;
  else if(buttom=='send') output+=send+','+close;
  else if(buttom=='view') output+=view+','+close;
  else output+=save+','+close;
  output+='}});';
  return output;
 },

 init:function(module,date,title,width,url,blank,buttom,simulation){
  /*
   * INIT UI
   */
  if(posUI<640)
   posUI+=20;
  else posUI=20;
  var ui=$('div[id=ui-window-'+date+']');
  ui.dialog({
   title:title,
   zIndex:666,
   position:[posUI,posUI],
   modal:false,
   resizable:true, // SI=FALSE SE QUEDA PEQUE�A LA VENTANA CUANDO SE AGREGAN ELEMENTOS
   show:'slide', //hide:{effect:'drop',direction:'up'},
   hide:'explode',
   width:width,height:480,
   maxWidth:width,maxHeight:480,
   minWidth:100,minHeight:480
  });
  // MAKE BUTTONS
  eval(getware.ui.dialog.makeButtons(buttom));

  /*
   * MAKE UI-CLASSES
   */
  var ui=$('div[aria-labelledby="ui-dialog-title-ui-window-'+date+'"]');
  ui.find('div[accordion]').accordion({header:'h3'});
  ui.find('div[tabs]').tabs();
  ui.find('div[slider]').slider({range:true,values:[17,27]});
  ui.find('div[progressbar]').progressbar({value:20});
  ui.find('ul[menu]').menu();
  //ui.find('select').multiselect({minWidth:170});

  // AUTOCOMPLETE WINDOW VIEW
  //ui.find('input[view]').autocomplete({source:'view.json?'+this.id,minLength:2});
  // MIN & MAX DATE
  // ui.find('input[datepicker]').datepicker({minDate:-20,maxDate:'+1M +10D',buttonImage:'../consorcios/images/calendar.png',changeMonth: true,changeYear:true,showButtonPanel:true,showAnim:'clip',dateFormat:'yy-mm-dd',showOn:'button',buttonImageOnly:true});

  ui.find('input[datepicker=date]').datepicker({buttonImage:'images/calendar.png',changeMonth:true,changeYear:true,showButtonPanel:true,showAnim:'clip',dateFormat:'yy-mm-dd',showOn:'button',buttonImageOnly:true});
  ui.find('input[datepicker=month]').datepicker({buttonImage:'images/calendar.png',changeMonth:true,changeYear:true,showButtonPanel:true,showAnim:'clip',dateFormat:'yy-mm',showOn:'button',buttonImageOnly:true});
  ui.find('input[datepicker=year]').datepicker({buttonImage:'images/calendar.png',changeMonth:false,changeYear:true,showButtonPanel:true,showAnim:'clip',dateFormat:'yy',showOn:'button',buttonImageOnly:true});

  /*
   * MINIMIZE BUTTON
   */
  var head=ui.find('div.ui-dialog-titlebar');
  head.prepend('<a href="#" id="'+date+'" class="ui-dialog-titlebar-minus ui-corner-all" role="button"><div id="'+date+'" class="ui-icon ui-icon-minusthick">minimize</div></a>');
  var minus=head.find('a.ui-dialog-titlebar-minus');
  var close=head.find('a.ui-dialog-titlebar-close');
  var content=ui.find('div.ui-dialog-content');
  var footer=ui.find('div.ui-dialog-buttonpane');
  minus.find('div.ui-icon-minusthick').click(function(){
   var ui='div[aria-labelledby='+this.id+']';
   $(ui+' div.ui-dialog-content').toggle();
   $(ui+' div.ui-dialog-buttonpane').toggle();
   $(ui).css('height','auto');
   $(ui+' a[id='+this.id+'] div')
    .toggleClass('ui-icon-minusthick')
    .toggleClass('ui-icon-plusthick');
   /*
   if($(ui+' a[id='+this.id+'] div').hasClass('ui-icon-plusthick')){
    $(ui).css('width',200);
    $(ui).css('position','static');
   }else{
    $(ui).css('width','auto');
    $(ui).css('position','absolute');
   }
   */
  });
  minus.find('div.ui-icon-minusthick').hover(
   function(){$('div[aria-labelledby='+this.id+'] a[id='+this.id+']').addClass('ui-state-hover');},
   function(){$('div[aria-labelledby='+this.id+'] a[id='+this.id+']').removeClass('ui-state-hover');}
  );

  $(content).css('height','auto');

  // INPUT VIEW TD.WIDTH-20PT
  /*
  ui.find('input[type=text]').each(function(){
   if($(this).attr('view'))
    intWidth=29;
   else intWidth=25;
   $(this).css('width',parseInt($(this).parent().width()-intWidth)+'px');
  });
  */
 }
}