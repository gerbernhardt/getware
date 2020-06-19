/*
 * Keep It Simple, Stupid!
 * Filename: includes/getware.ui.grid.js
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!getware.ui) getware.ui = {}

getware.ui.grid = {
  form: function(date) {
    let string = 'window=' + date + '&';
    $('div[id=overflow-c-' + date + '] .ui-selected input[type!=button][type!=checkbox]').each(function() {
      string += this.name + '[]' + '=' + encodeURIComponent(this.value) + '&';
    });
    return string;
  },
  
  sort: function(date, op) {
    let x = $('div[grid=' + date.split('-')[2] + '] div[id=content] li');
    for(i = 0; i < x.length - 1; i++) {
      for(j = i + 1; j < x.length; j++) {
        a = $('li[id=' + x[i].id + '] div[id=row-' + date.split('-')[1] + '-' + date.split('-')[2]+ ']');
        b = $('li[id=' + x[j].id + '] div[id=row-' + date.split('-')[1] + '-' + date.split('-')[2]+ ']');
        
        if(a.find('input').length) {
          a = a.find('input').val();
          b = b.find('input').val();
        } else {
          a = a.text();
          b = b.text();
        }

        if(op == true) {
          aux = a;
          a = b;
          b = aux;
        }
        if(a < b) {
          css = $('li[id=' + x[i].id + ']').attr('class');
          tooltip = $('li[id=' + x[i].id + ']').attr('title')
          html = $('li[id=' + x[i].id + ']').html();
          
          // INTERCAMBIA LA CLASE DEL <LI>
          $('li[id=' + x[i].id + ']').attr('class', $('li[id=' + x[j].id + ']').attr('class'));
          $('li[id=' + x[j].id + ']').attr('class', css);
          
          // INTERCAMBIA LA TITLE DEL <LI>
          $('li[id=' + x[i].id + ']').attr('title', $('li[id=' + x[j].id + ']').attr('title'));
          $('li[id=' + x[j].id + ']').attr('title', tooltip);
          
          // INTERCAMBIA LA HTML DEL <LI>
          $('li[id=' + x[i].id + ']').html($('li[id=' + x[j].id + ']').html());
          $('li[id=' + x[j].id + ']').html(html);
        }
      }
    }
    getware.ui.grid.check(date.split('-')[2]);
    $('div[grid=' + date.split('-')[2] + '] li').removeClass('ui-selected');
  },

  toolbar: function(module, date, options) {
    let output = '';
    for(i = 0; i < options.data.length; i++) {
      output += '<a href="#" onclick="javascript:getware.ui.menu.action(\'';
      if(options.module[i])
        output += options.module[i];
      else output += module;
      output += '\',' + date + ',\'' + options.cmd[i] + '\',\'' + options.type[i] + '\',';
      output += '\'' + options.blank[i] + '\');" role="button" class="ui-corner-all" href="#">';
      output += '<div style="margin-left:' + (26 * i) + 'px;" class="icon-toolbar icon-' + options.img[i] + '"  title="' + options.data[i] + '">&nbsp;</div></a>';
      //output+='<span class="icon-toolbar icon-'+options.img[i]+'">&nbsp;</span></a>';
    }
    return output;
  },

  check: function(date) {
    // GRID
    let h = 'div[grid=' + date + '] div[id=header] li';
    let c = 'div[grid=' + date + '] div[id=content] li';

    // HEADER CHECK.CLICK()
    $(h + ' input').click(function() {
      // this.id.split('-')[1] es el id
      if(this.checked == true) { // ALL CKECK ON
        $('div[id=content] li').addClass('ui-selected').find('input').each(function(){
          this.checked = true;
        });
      } else { // ALL CKECK OFF
        $('div[id=content] li').removeClass('ui-selected')
        .find('div').removeClass('ui-selected')
        .find('input').each(function() {
          this.checked = false;
        });
      }
    });

    // CONTENT CHECK.CLICK()
    $(c + ' input').click(function() {
      if(this.checked == true) {
      $('input[id=' + this.id + ']').parent().parent().each(function() {
        $('li[id=' + this.id + ']').addClass('ui-selected');
      });
      } else {
        $('input[id=' + this.id + ']').parent().parent().each(function() {
          $('li[id=' + this.id + ']').removeClass('ui-selected');
          $('li[id=' + this.id + '] div').removeClass('ui-selected');
        });
      }
      // UNCHECK HEADER CHECKBOX
      $('input[id="0-' + this.id.split('-')[1] + '"]').each(function() {
        this.checked = false;
      });
    });
  },

  make:function(json) {
    // TOMA EL DATE UI DEL SEARCH.MAKE()
    // QUE SE GENERA ANTERIORMENTE PARA
    // QUE TENGA CONECTIVIDAD CON EL NAVBAR
    // SI NO EXISTE LOS CREA!
    if(getware.ui.date) {
      date = getware.ui.date;
    } else {
      let date = new Date();
      date = date.getTime();
      getware.ui.date = date;
    }

    let output = getware.table.open();
    output += '<div grid="' + date + '">';
    
    /*
     * TITLE
     */
    output += '<div id="title">';
    getware.ui.menu.options = json.menu;
    output += '<img style="cursor:pointer;" width="20" src="images/down.png" onclick="javascript:getware.ui.menu.make(\'' + json.module + '\',' + date + ');">';
    output += json.title;
    output += '</div>';
    /*
     * TOOLBAR
     */
    output += '<div id="toolbar">';
    output += getware.ui.grid.toolbar(json.module, date, json.menu);
    output += '</div>';

    // OVERFLOW-H: MOVE HORIZONTAL AXIS SUBTITLES
    output += '<div id="overflow-h-' + date + '">';
    
    /*
     * SUBTITLES
     */
    output += '<div id="header">';
    output += '<li id="field-0-' + date + '">';
    // CHECKBOX ALL
    output += '<div id="row-0-' + date + '"><input id="0-' + date + '" type="checkbox" /></div>';
    for(i = 0; i < json.columns.length; i++) {
      output += '<div id="row-' + (i + 1) + '-' + date + '">' + json.columns[i].name;
      // SORT IMAGE
      output += '<div class="ui-dialog-titlebar-sort ui-icon ui-icon-triangle-2-n-s" sort>';
      output += '</div></div>';
    }
    output += '</li>';
    output += '</div>'; // END DIV HEADER
    output += '</div>'; // END DIV OVERFLOW-H

    // OVERFLOW-V: MOVE VERTICAL AXIS CONTENT
    output += '<div id="overflow-c-' + date + '">';
    
    /*
     * CONTENT
     */
    output += '<div id="content">';
    for(i = 0; i < json.data.length; i++) {
      rows = '';
      css = '';
      if(json.data[i][(json.columns.length + 1)])    // DATA.LENGTH > (COLUMNS.LENGHT+1)
        css = json.data[i][(json.columns.length + 1)]; // INPUT CSS

      tooltip = '';
      if(json.data[i][(json.columns.length + 2)])   // DATA.LENGTH > (COLUMNS.LENGHT+2)
        tooltip = json.data[i][(json.columns.length + 2)]; // TOOLTIP

      /* ANULADA EL DOBLE CLICK TEMPORALMENTE
      * EN SU LUGAR SE ANEXA EL TITLE O ALT!
      dblclick='';
      if(json.data[i][(json.columns.length+3)])                           // DATA.LENGTH > (COLUMNS.LENGHT+2)
      dblclick='javascript:'+json.data[i][(json.columns.length+3)]+';'; // INPUT DBLCLICK FUNCTION()
      */

      dblclick = '';
      if(json.data[i][(json.columns.length + 3)])                           // DATA.LENGTH > (COLUMNS.LENGHT+2)
        dblclick = json.data[i][(json.columns.length + 3)]+';'; // INPUT DBLCLICK FUNCTION()
      
      for(j = 1; (j < json.data[i].length) && (j <= json.columns.length); j++) {
        rows += '<div id="row-' + j + '-' + date + '">'; // MAKE ROW
        if(json.columns[j - 1].edit) { // IF EDIT INPUT
          rows += '<input id="' + j + '-' + json.data[i][0] + '" placeholder="' + json.columns[j - 1].name + '" name="' + json.columns[j - 1].field + '"';
          rows += ' type="text" value="' + json.data[i][j] + '" class="ui-autocomplete-input ui-selectee" ' + json.columns[j - 1].edit + ' />';
        } else rows += json.data[i][j]; // ELSE NORMAL DATA
        rows += '</div>';
      }

      // MAKE LI CONTENT FIELD
      output += '<li id="field-' + (i + 1) + '-' + date + '" class="' + css + '" title="' + tooltip + '">';

      // CHECKBOX INDIVIDUAL
      output += '<div id="row-0-' + date + '">';
      output += '<input id="' + json.data[i][0] + '-' + date + '" type="checkbox" style="vertical-align:top;" />';
      if(json.extra)
        output+='<input type="button" style="background: url(images/navbar_plus.png) no-repeat;cursor:pointer;margin:0px;vertical-align:top;" />';
      output += '</div>';
      output += rows; // ROW ON FOR()
      output += '</li>';
    }
    output += '</div>'; // END DIV CONTENT
    output += '</div>'; // END DIV OVERFLOW-V
    
    /*
     * NAVBAR
     */
    output += '<div id="navbar">';
    pages = json.navbar.rows / json.navbar.limit;
    if(parseInt(pages) < pages)
      pages = parseInt(pages) + 1;
    else pages = parseInt(pages);

    output += '<div id="pages">';

    // PREV - FISRT
    output += '<a onclick="getware.get(';
    output += '\'module=admin&amp;admin=' + json.module + '&amp;start=0\', ';
    output += 'getware.ui.search.form(\'form_search_' + date + '\')';
    output += ');" href="#"><img src="images/navbar_first.gif"></a>';

    prev = json.navbar.start - 1;
    if(prev < 0) prev = 0;
    output += '<a onclick="getware.get(';
    output += '\'module=admin&amp;admin=' + json.module+'&amp;start=' + prev + '\', ';
    output += 'getware.ui.search.form(\'form_search_' + date + '\')';
    output += ');" href="#"><img src="images/navbar_prev.gif"></a>';
/*
    buttons = ['start', 'prev', 'next', 'end'];
    for(i = 0; i < buttons.length; i++) {
      output += '<button id="' + buttons[i] + '" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only" role="button" title="' + buttons[i] + '">';
      output += '<span class="ui-button-icon-primary ui-icon ui-icon-seek-' + buttons[i] + '"></span>';
      output += '<span class="ui-button-text">' + buttons[i] + '</span></button>';
    }
*/
    // NUMBERS
    if(pages > 25 && json.navbar.start > 12) {
      if(json.navbar.start + 12 < pages)
        ini = json.navbar.start - 12;
      else ini = pages - 25;
    } else ini = 0;

    for(i = 0; i < 25 && ini < pages; i++) {
      if(ini == json.navbar.start) {
        output += '<a class="thispage">' + (ini + 1) + '</a>';
      } else {
        output += '<a class="navpage" onclick="getware.get(';
        output += '\'module=admin&amp;admin=' + json.module+'&amp;start=' + ini + '\', ';
        output += 'getware.ui.search.form(\'form_search_' + date + '\')';
        output += ');" href="#">' + ( ini + 1) + '</a>';
      }
      ini++;
    }

    // NEXT - LAST
    next = json.navbar.start + 1;
    if(next >= pages) next = pages - 1;

    output += '<a onclick="getware.get(';
    output += '\'module=admin&amp;admin=' + json.module + '&amp;start=' + next + '\', ';
    output += 'getware.ui.search.form(\'form_search_' + date + '\')';
    output += ');" href="#"><img src="images/navbar_next.gif"></a>';

    output += '<a onclick="getware.get(';
    output += '\'module=admin&amp;admin=' + json.module + '&amp;start=' + (pages - 1) + '\', ';
    output += 'getware.ui.search.form(\'form_search_' + date + '\')';
    output += ');" href="#"><img src="images/navbar_last.gif"></a>';
    output += '</div>';

    output += '<div id="description">[ ' + json.navbar.rows + ' resultados / pagina: ' + (json.navbar.start + 1) + ' de ' + pages + ' ]</div>';
    output += '</div>';
    output += getware.table.close();

    // SI APPEND ES TRUE AGREGA
    if(json.append == 'true') // ES PARA QUE NO BORRE AL SEARCH BOX
      $(json.window).append('<br>' + output);
    else $(json.window).html(output);

    getware.ui.grid.render(date, json); // INI PLUGIN()
  },

  render: function(date, json) {
    let columns = json.columns;
    
    // GRID
    let g = ' div[grid=' + date + ']';
    
    // TITLE
    let gt = g + ' div[id=title]';
    
    // TOOLBAR
    gtb = g + ' div[id=toolbar]';
    
    // NAVBAR
    gnb = g + ' div[id=navbar]';
    
    // HEADER
    let gh = g + ' div[id=header]';
    let ghl = gh + ' li';
    let ghlr = ghl + ' div[id^=row-]';
    let goh = g + ' div[id=overflow-h-' + date + ']';
    let goc = g + ' div[id=overflow-c-' + date + ']';
    
    // CONTENT
    let gc = g + ' div[id=content]';
    let gcl = gc + ' li';
    let gclr = gcl + ' div[id^=row-]';

    // RESIZABLE HEADERS
    let length = $(ghlr).length
    for(i = 1; i < length; i++) {
      r = $(ghl+' div[id=' + $(ghlr)[i].id + ']');
      rc = $(gcl + ' div[id=' + $(ghlr)[i].id + ']');
      
      if(navigator.appVersion.indexOf('MSIE') == -1)
        r.resizable({minWidth:25, maxHeight:20, minHeight:20, alsoResize: gcl + ' div[id=' + $(ghlr)[i].id + ']'});
      
      r.css('width', columns[i - 1].width);
      rc.css('width', columns[i - 1].width);
      
      if(columns[i - 1].align) {
        rc.css('text-align', columns[i - 1].align);
      // rc.css('padding','2px 2px 2px 2px');
      // rc.css('margin','2px 2px 2px 2px');
      }

      r.click(function() {
        input = 'div[id=' + this.id + '] div[sort]';
        output = 'div[id!=' + this.id + '][id^=row][id=' + this.id.split('-')[2] + '] div[sort]';
        if($(input).hasClass('ui-icon-triangle-2-n-s') == true) {
          $(input).removeClass('ui-icon-triangle-2-n-s');
          $(input).addClass('ui-icon-triangle-1-n');
          $(output).removeClass('ui-icon-triangle-2-n-s ui-icon-triangle-1-n ui-icon-triangle-2-s');
          $(output).addClass('ui-icon-triangle-2-n-s');
          getware.ui.grid.sort(this.id, false);
        } else {
          $(input).toggleClass('ui-icon-triangle-1-s ui-icon-triangle-1-n');
          $(output).removeClass('ui-icon-triangle-2-n-s ui-icon-triangle-1-n ui-icon-triangle-2-s');
          $(output).addClass('ui-icon-triangle-2-n-s');
          if($(input).hasClass('ui-icon-triangle-1-n') == true)
            getware.ui.grid.sort(this.id, false);
          else getware.ui.grid.sort(this.id, true);
        }
      });
    }

    $(g).addClass('ui-corner-all grid');
    $(gt).addClass('ui-widget-header grid-title');
    $(gtb).addClass('grid-header');
    $(gnb).addClass('ui-widget-header grid-navbar');

    $(goh).addClass('grid-overflow-h');
    $(goc).addClass('grid-overflow-c');

    $(gh).addClass('grid-header');
    $(ghl).addClass('grid-header-field');
    $(ghlr).addClass('grid-header-row');
    $(ghlr + ' div.ui-resizable-s').remove();
    $(ghlr + ' div.ui-resizable-se').remove();
    //$(ghlr+' div.ui-resizable-se').css('cursor','e-resize');
    // NO FUNCA

    $(ghlr + ' div[sort]').addClass('ui-dialog-titlebar-sort ui-icon ui-icon-triangle-2-n-s');
    $(gc).addClass('grid-content');
    $(gcl).addClass('ui-widget-content grid-content-field');
    $(gclr).addClass('grid-content-row');
    $(gc + ' input[type=text]').addClass('grid_input');

    // INPUT VIEW AUTOCOMPLETE()
    $(gc + ' input[type=text][view]').each(function() {
      $(this).autocomplete({
        source:'index.php?module=admin&admin=' + json.module + '&row=' + this.id + '&ajax',
        minLength:0
      });
      // ON DBLCLICK SELECT SIMULATION
      $(this).dblclick(function() {
        if($(this).autocomplete('widget').is(':visible')) {
          this.autocomplete('close');
          return;
        }
        $(this).autocomplete('search','');
        $(this).focus();
      });
    });

    // MOVE X HEADERS
    $('div[id^=overflow-c-]').scroll(function() {
      $('div[id=' + this.id.replace(/-c-/, '-h-') + ']').scrollLeft($('div[id=' + this.id + ']').scrollLeft());
    });
    
    // SELECTABLE CONTENT
    $(gc).selectable({
      stop: function() {
        result = $('#result').empty();
        // ALL CHECK OFF
        $('li', this).each(function() {
          $('input', this).each(function() {
            this.checked = false;
          });
        });
        // UISELECT=CHECK ON
        $('li.ui-selected', this).each(function() {
          $('input', this).each(function() {
            this.checked = true;
          });
          result.append('#' + this.id);
        });
        // HEADER CHECK OFF
        $(ghl + ' input').each(function() {
          this.checked = false;
        });
      }
    });
    getware.ui.grid.check(date);

    // ALL CHECK HEADERS AND CONTENT WIDTH:25
    $(g + ' div li div[id^=row-0]').css('width', 22);
    $(g + ' div li div[id*=row-0]').css('width', 22);

    if(json.extra) {
      // CAMBIA WIDTH
      $(g + ' div li div[id^=row-0]').css('width', 38);
      $(g + ' div li div[id*=row-0]').css('width', 38);
      $(g + ' div li div[id*=row-0] input[type=button]').click(function() {
        let x = $(this).parent().parent();
        if($(x).height() == 24) {
          $(this).css('background', 'url(images/navbar_minus.png) no-repeat');
          $(this).css('height', '18');
          for(i = 0; i < json.extra.length; i++) {
            $(x).find('div[id*=row-' + json.extra[i] + ']').css('clear', 'both');
            $(x).find('div[id*=row-' + json.extra[i] + ']').css('position', 'relative');
            $(x).find('div[id*=row-' + json.extra[i] + ']').css('margin-left', '45px');
          }
          $(x).height($(x).height() + (22 * json.extra.length));
        } else {
          $(this).css('background','url(images/navbar_plus.png) no-repeat');
          for(i = 0; i < json.extra.length; i++) {
            $(x).find('div[id*=row-' + json.extra[i] + ']').css('clear', 'none');
            $(x).find('div[id*=row-' + json.extra[i] + ']').css('position', 'none');
            $(x).find('div[id*=row-' + json.extra[i] + ']').css('margin-left', '2px');
          }
          $(x).height($(x).height() - (22 * json.extra.length));
        }
      });
    }
  }
}