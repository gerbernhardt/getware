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
if(!getware.ui) getware.ui = {}

getware.ui.search = {
  form: function(form) {
    let string = 'window=' + form.substr(5, 13) + '&';
    
    $('form[id=' + form + '] input[type=text]').each(function() {
      string += 'search[]=' + encodeURIComponent(this.value) + '&';
    });
    
    $('form[id=' + form + '] select').each(function() {
      string += 'filter[]=' + encodeURIComponent(this.value) + '&';
    });

    return string;
  },

  onEnter: function(event) {
    let key = event.which || event.keyCode;
    if (key == 13)
      return true;
    else return false;
  },

  make: function(json) {
    let date = new Date();
    date = date.getTime();
    getware.ui.date = date; // PARA QUE TENGA CONECTIVIDAD CON EL NAVBAR
    
    let output = getware.table.open('search-toolbar');
    output += '<form id="form_search_' + date + '" method="post">';

    // INI DATA INPUT
    let onclick = 'getware.get(\'module=admin&amp;admin=' + json.module+'\', getware.ui.search.form(\'form_search_' + date + '\'));return false;';
    for(i = 0; i <= json.name.length; i++) {
      if(i < json.name.length) {
        output += '<div style="float:left; width:' + (json.size[i] * 10) + 'px;">';
        output += '<input id="' + i + '" type="text" class="search" value="' + json.data[i] + '" placeholder="' + json.name[i] + '"';
        output += ' onkeypress="if(getware.ui.search.onEnter(event)){' + onclick + '}" />'; 
        output += '<select id="f' + i + '" style ="margin-left: -38px;border-top-width: 1px;padding-left: 0px;padding-top: 2px;padding-bottom: 2px;margin-top: -1px;" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">';
        
        let filter = ['like', 'high', 'low', 'middle'];
        let filterName = ['..', '>', '<', '='];
        for(j = 0; j < filter.length; j++) {
          let selected = ''
          if(json.filter) {
            if(json.filter[i] == filter[j]) selected = ' selected';
          }
          output += '<option value="' + filter[j] + '"' + selected + '>' + filterName[j] +'</option>';
        }
        output += '</select>';
        output += '</div>';
      } else {
        output += '<div style="float:left;">';
        output += '<input type="button" title="Buscar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"';
        output += ' onclick="javascript:' + onclick + '" value="Buscar">';
        output += '</div>';
      }
    }

    output += '</form>';
    output += getware.table.close();
    $(json.window).html(output);
    
    $(json.window + ' input[type=text]').each(function() {
      url='index.php?module=admin&admin='+json.module+'&search='+this.id+'&ajax'
      $(this).autocomplete({source:url,minLength:0});
    });
    
    return false;
  }
}