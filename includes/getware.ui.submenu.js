/*
 * Keep It Simple, Stupid!
 * Filename: includes/getware.ui.submenu.js
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!getware.ui) getware.ui={}
getware.ui.submenu = {
    get: function(window, element, module, filter = false, name = false) {
        window = window.toString().substr(0, 13);
        if(filter) {
            if(filter = $('div[id=ui-window-' + window + '] input[id=' + filter + ']').val()) {
            } else filter = $('div[id=ui-window-' + window + '] input[id=' + filter.split('x')[0] + 'x]').val();
        } else filter = '\%';
        var url = 'module=admin&admin=' + module + '&window=' + window + '&submenu=' + element + '&filter=' + filter + '&name=' + name;
        url += '&term=' + encodeURIComponent($('div[id=ui-window-' + window + '] input[id=\'' + element + '\']').val());
        getware.get(url);
    },
    make: function(json) {
        var x = $('#window-menu');
        x.hide();
        x.html('');
        //x.css('width',200);
        x.css('top', posY - 5);
        x.css('left', posX - 5);

        var output = '';
        for(var i = 0; i < json.value.length; i++) {
            let name = '';
            if(json.name) name = '[name=\\\'' + json.name + '\\\']';
            output += '<a href="#" onclick="';
            output += '$(\'div[id=ui-window-' + json.window + '] input[id=\\\'' + json.id + '\\\']' + name + '\')';
            output += '.attr(\'value\',\'' + json.value[i] + '\')';
            output += '.trigger(\'change\');';

            if(json.info[i])
                output += '$(\'div[id=ui-window-' + json.window + '] div[id=' + json.id + ']\').html(\'' + json.info[i] + '\');';

            output += '">' + json.label[i] + '</a><br>';
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