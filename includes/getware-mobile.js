/*
 * Keep It Simple, Stupid!
 * Filename: includes/getware.js
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
var posX=0,posY=0;
var getware={
 debug:false,
 table:{
  open:function(style){
   if(style=='undefined') style='';
   return '<table width="100%" class="'+style+' ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"><tr><td>';
  },
  close:function(){
   return '</td></tr></table>';
  }
 },
 mouse:function(e){
  if(!e)
   var e=window.event;
  if(e.pageX||e.pageY){
   posX=e.pageX;
   posY=e.pageY;
  } else if(e.clientX||e.clientY){
   posX=e.clientX;
   posY=e.clientY;
  }
 },
 data:function(dataResponse){
  var x = $('#content_center');
  if(/<script>/.test(dataResponse)) {
   x.append(dataResponse);
  } else if(/{run:/.test(dataResponse)) {
   dataResponse=eval(dataResponse);
   for(var e=0; e < dataResponse.length; e++) {
    eval(dataResponse[e].run + '(dataResponse[' + e + ']);');
   }
  } else x.html(dataResponse);
 },
 url:function(json){
  getware.get(json.url);
 },
 get:function(url,data,redirect){
  var async=true;  // FALSE ES PARA QUE NO HABRA MUCHAS VENTANAS AL HACER CLICK
                    // TRUE ES PARA QUE FUNCIONE EL PROGRESSBAR!!!
  var refresh=new Date();
  if(!data) data='';
  if(!redirect) url='index.php?'+url+'&ajax='+refresh.getTime();
  if(/module=login/.test(url)) async=true; //ES TRUE PARA QUE REAPAREZCA LA VENTANA DE LOGIN
  ////////////////////////////////////////
  $.ajax({
   async: async,// ES FALSE PARA QUE NO SE CREEN CONFLICTOS EN EL ID DE LAS VENTANAS
   type: 'POST',
   dataType: 'text', // SI NO ESPECIFICO HTML CUANDO DEVUELVE EN JSON DA ERRROR!
   contentType: 'application/x-www-form-urlencoded',
   url: url,
   data: data,
   success:function(data) {
    getware.data(data);
   }
  });
  ///////////////////////
  return false;
 },
 getjson:function(url,data,redirect){
  var async=true;  // FALSE ES PARA QUE NO HABRA MUCHAS VENTANAS AL HACER CLICK
                    // TRUE ES PARA QUE FUNCIONE EL PROGRESSBAR!!!
  var refresh=new Date();
  if(!data) data='';
  if(!redirect) url='index.php?'+url+'&ajax='+refresh.getTime();
  if(/module=login/.test(url))async=true; //ES TRUE PARA QUE REAPAREZCA LA VENTANA DE LOGIN
  $.ajax({
   async:async,// ES FALSE PARA QUE NO SE CREEN CONFLICTOS EN EL ID DE LAS VENTANAS
   type:'POST',
   dataType:'text', // SI NO ESPECIFICO HTML CUANDO DEVUELVE EN JSON DA ERRROR!
   contentType:'application/x-www-form-urlencoded',
   url:url,
   data:data,
   error:function(){
    if(getware.debug==true) console.log('getware.getjson()->error()');
   },
   success:function(data){return data;}
  });
  return false;
 },
 getfile:function(url,date){
  var message='';
  var refresh=new Date();
  var formData=new FormData($('form[id='+date+']')[0]);
  url='index.php?'+url+'&ajax='+refresh.getTime();
  $.ajax({
   url:url,
   type:'POST',
   data:formData,
   async:false,
   //necesario para subir archivos via ajax
   cache:false,
   contentType:false,
   processData:false,
   success:function(data){getware.data(data);},
   error:function(){message='Ha ocurrido un error.';}
  });
  return false;
 },
 getopen:function(url,data){
  var refresh=new Date();
  url='index.php?'+encodeURI(url)+'&ajax='+refresh.getTime();
  open(url);
  return false;
 }
}
function removeWindows(){
 $('body div.ui-dialog').each(function(){if($(this).css('display')=='none')$(this).remove();});
 
 $('body').children('div[id^=ui-window-]').each(function(){if($(this).css('display')=='none')$(this).remove();});
 setTimeout(removeWindows,2000);
}

function toggleMenu(sync){
	if(sync == false) {
		$('.block').css('display','none');
        $('td[id=content_left]').css('display','none');
        $('td[id=content_center]').css('display','block');
	} else {
		$('.block').css('display','block');
        $('td[id=content_left]').css('display','block');
        $('td[id=content_center]').css('display','none');
	}
}

$(document).ready(function(){
 $('body').bind('mousemove',function(e){getware.mouse(e);});
 setTimeout(removeWindows,2000);

 // ALL BLOCKS
 $('.block')
  .addClass('ui-widget ui-widget-content ui-helper-clearfix ui-corner-all')
  .find('.block-header').addClass('ui-widget-header ui-corner-all');
 
 // MAXIMIZE
 $('.block')
  .find('.block-header').addClass('ui-widget-header ui-corner-all')
   .prepend('<a href="#" class="ui-dialog-titlebar-minus ui-corner-all" role="button"><div class="ui-icon ui-icon-minusthick">minimize</div></a>').end()
  .find('.block-content').css('display','block');

 // TOOGLE SHOW-HIDE
 $('.block-header').click(function(){
  $(this).toggleClass('ui-icon-minusthick').toggleClass('ui-icon-plusthick');
  $(this).parents('.block:first').find('.block-content').toggle();
 });

 $('td[id=content_left]').css('display','none');
 
 $('td img[id=menu]').click(function(){
    toggleMenu(true);
 });
 $('td[id=content_left] a').click(function(){
    toggleMenu(false);
 });

});