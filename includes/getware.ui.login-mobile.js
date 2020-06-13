/*
 * Keep It Simple, Stupid!
 * Filename: includes/getware.ui.js
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!getware.ui) getware.ui={}
getware.ui.login={
 form:function(form){
  var string='';
  var x=$('table td[id=content_center]')
   .find('textarea,input[type!=button]').each(function(){
    var post=this.id; //NORMAL SEND
    string+=post+'='+encodeURIComponent(this.value)+'&';
  });
  return string;
 },

 make:function(json){
  var date=new Date();
  date = date.getTime();

  var output='<p class="title">' + json.data +'</p>';
  output+='<form method="post" "form_'+date+'">';
  output+='<input type="text" value="" maxlength="25" placeholder="username" id="username">';
  output+='<br>';
  output+='<input type="password" value="" maxlength="25" placeholder="password" id="password">';
  output+='<br>';
  output+='<button type="button" id="login" onclick="javascript: getware.get(\'' + json.get + '\',getware.ui.login.form(' + date + ') + \'&' + json.post + '\', true);" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false">';
  
  output+='<span class="ui-button-text">ACEPTAR</span>';
  output+='</button>';


  output+='</form>';
  
  $('table td[id=content_center]').html(output);

 }
}