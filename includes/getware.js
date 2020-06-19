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

var posX = 0, posY = 0;
var logo = new Image();
logo.src = 'images/logos/logo.png';

function roundToTwo(num) {
  num = Number(num);
  return +(Math.round(num + "e+2")  + "e-2");
}

function toFixed(str) {
  return parseFloat(parseFloat(str).toFixed(2));
}

function textToNum(str) {
  str += '';
  
  regExp = /\./g;
  replace = '';
  str = str.replace(regExp, replace);
 
  regExp = /,/g;
  replace = '.';
  str = str.replace(regExp, replace);
  return parseFloat(parseFloat(str).toFixed(2));
}

function numToText(str) {
  roundToTwo(str);
  str = parseFloat(str).toFixed(2);
  x = str.split('.');
  x1 = x[0];
  x2 = x.length > 1 ? ',' + x[1] : '';
  let rgx = /(\d+)(\d{3})/;
  while(rgx.test(x1)) {
      x1 = x1.replace(rgx, '$1' + '.' + '$2');
  }
  return x1 + x2;
}

var getware = {
 debug: false,
 table: {
  open: function(style){
   if(typeof style === 'undefined') style='';
   return '<table width="' + $('#content_center').width() + '" class="'+style+' ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"><tr><td>';
  },
  close: function(){
   return '</td></tr></table>';
  }
 },

 fnumber: function(num) {
  num = String(num).split('.');
  num[1] = num[1] ? num[1] : '00';
  let regx = /(\d+)(\d{3})/;
  while(regx.test(num[0])) {
    num[0] = num[0].replace(regx, '$1' + '.' + '$2');
  }
  return num[0] + ',' + num[1];
 },

 mouse: function(e) {
  if(!e) e = window.event;
  if(e.pageX || e.pageY) {
   posX = e.pageX;
   posY = e.pageY;
  } else if(e.clientX || e.clientY) {
   posX = e.clientX;
   posY = e.clientY;
  }
 },

 data: function(dataResponse) {
  let obj = $('#content_center');
  if(/{run:/.test(dataResponse)) {
    dataResponse = eval(dataResponse);
    for(let i = 0; i < dataResponse.length; i++) {
      let run = dataResponse[i].run.replace(/~/gi, '_')
      eval(run + '(dataResponse[' + i + ']);');
    }
    return;
  }
  
  if(/<script>/.test(dataResponse))
    obj.append(dataResponse);
  else obj.html(dataResponse);
 },

 url: function(json) {
  getware.get(json.url);
 },
 
 form: function(form){
    let string = 'window=' + form + '&';
    $('form[id='+form+']').find('textarea, input[type!=button], select').each(function() {
      let post = this.id;// NORMAL SEND
      string += post + '=' + encodeURIComponent(this.value) + '&';
    });
    return string;
   },

  get: function(url, data = '', redirect = false) {
    $('#progressbar').progressbar({value:false});
    let async = true;  // FALSE ES PARA QUE NO HABRA MUCHAS VENTANAS AL HACER CLICK
    // TRUE ES PARA QUE FUNCIONE EL PROGRESSBAR!!!
    let refresh = new Date();

    if(!redirect) url = 'index.php?' + url + '&ajax=' + refresh.getTime();
    if(/module=login/.test(url)) async = true; //ES TRUE PARA QUE REAPAREZCA LA VENTANA DE LOGIN

    $.ajax({
      async: async,// ES FALSE PARA QUE NO SE CREEN CONFLICTOS EN EL ID DE LAS VENTANAS
      type: 'POST',
      dataType: 'text', // SI NO ESPECIFICO HTML CUANDO DEVUELVE EN JSON DA ERRROR!
      contentType: 'application/x-www-form-urlencoded',
      url: url,
      data: data,
      error: function() {},
      progress: function(e) {
        if(e.lengthComputable) {
          let pct = (e.loaded / e.total) * 100;
          $('#progressbar')
          .progressbar('option','value', pct)
          .children('.ui-progressbar-value')
          .html('# ' + (e.loaded / 1024).toPrecision(2) + ' KB: ' + pct.toPrecision(3) + '%')
          .css('display','block');
        }
      },
      success: function(data) {
        getware.data(data);
      }
    });
  },

 getfile: function(url, date){
  var refresh = new Date();
  var formData = new FormData($('form[id=' + date + ']')[0]);
  url = 'index.php?' + url + '&ajax=' + refresh.getTime();
  $.ajax({
    url: url,
    type: 'POST',
    data: formData,
    async: true,
    //necesario para subir archivos via ajax
    cache: false,
    contentType: false,
    processData: false,
    xhr: function() {
      let xhr = $.ajaxSettings.xhr();
      if(xhr.upload)
        xhr.upload.addEventListener('progress', function(e) {
          if(e.lengthComputable) {
            let pct = (e.loaded * 100) / e.total;
            $('#progressbar')
            .progressbar('option', 'value', pct)
            .children('.ui-progressbar-value')
            .html('# ' + roundToTwo(e.loaded / 1024) + ' KB: ' + pct.toPrecision(3) + '%')
            .css('display','block');
          }  
        }, false);
      return xhr;
    },
    success: function(data){getware.data(data);},
    error: function(){message='Ha ocurrido un error.';},
    resetForm: true
  });
  return false;
 },

 getopen: function(url) {
  let refresh = new Date();
  url = 'index.php?' + encodeURI(url) + '&ajax=' + refresh.getTime();
  open(url);
  return false;
 }
}

var syncMenu = false;
function removeWindows() {
    $('body div.ui-dialog').each(function() {
      if($(this).css('display') == 'none')
        $(this).remove();
    });
    
    $('body').children('div[id^=ui-window-]').each(function() {
      if($(this).css('display') == 'none')
        $(this).remove();
    });
    setTimeout(removeWindows, 2000);
}

$(document).ready(function(){
  $('body').bind('mousemove', function(e) {
    getware.mouse(e);
  });
 setTimeout(removeWindows, 2000);

 //$('div[id^=block]').dialog({
 $('td[id=content_left]').sortable({connectWith:'td[id^=content_]'});
 // ALL BLOCKS
 $('.block')
  .addClass('ui-widget ui-widget-content ui-helper-clearfix ui-corner-all')
  .find('.block-header').addClass('ui-widget-header ui-corner-all');
 
 // MINIMIZE
 $('.block||[thick=2]')
  .find('.block-header')
   .prepend('<a href="#" class="ui-dialog-titlebar-minus ui-corner-all" role="button"><div class="ui-icon ui-icon-plusthick">maximize</div></a>').end()
  .find('.block-content').css('display','none');
 
 // MAXIMIZE
 $('.block||[thick=1]')
  .find('.block-header').addClass('ui-widget-header ui-corner-all')
   .prepend('<a href="#" class="ui-dialog-titlebar-minus ui-corner-all" role="button"><div class="ui-icon ui-icon-minusthick">minimize</div></a>').end()
  .find('.block-content').css('display','block');
 
  // TOOGLE SHOW-HIDE
  $('.block-header .ui-icon').click(function() {
    $(this).toggleClass('ui-icon-minusthick').toggleClass('ui-icon-plusthick');
    $(this).parents('.block:first').find('.block-content').toggle();
  });
 /*
 $('.block-header .ui-icon').hover(
  function(){$(this).addClass('ui-state-hover');},
  function(){$(this).removeClass('ui-state-hover');}
 );
 $('td[id^=content_]').disableSelection();
 */
});