//
// Getware: Ultra-Secure Script
// Filename: includes/getware.ui.js, 2012/04/03
// Copyright (c) 2012 by German Bernhardt
// E-mail: <german.bernhardt@gmail.com | german.bernhardt@hotmail.com>
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License.
if(!getware.ui) getware.ui={}
getware.ui.login={
 form:function(form){
  var string='';
  var x=$('div[id=window-message]')
   .find('textarea,input[type!=button]').each(function(){
    var post=this.id; //NORMAL SEND
    string+=post+'='+encodeURIComponent(this.value)+'&';
  });
  return string;
 },

 make:function(json){
  var date=new Date();
  date=date.getTime();
  $('div[id=window-message]:ui-dialog').dialog('destroy');
  var output='<p><span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>'+json.data+'';
  output+='<form method="post" "form_'+date+'">';
  output+='<input type="text" class="username_block" value="" maxlength="25" size="15" name="username" id="username">';
  output+='<br>';
  output+='<input type="password" class="password_block" value="" maxlength="25" size="15" name="password" id="password">';
  output+='</form></p>';
  $('div[id=window-message]').html(output);
  var title='Login';
  $('div[id=window-message]').dialog({
   title:title,
   modal:true,
   buttons:{
    Ok:function(){
     getware.get(json.get,getware.ui.login.form(date)+'&'+json.post,true);
     $(this).dialog('close');
    },
    Cancel:function(){$(this).dialog('close');}
   }
  });
 }
}