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
if(!getware.ui) getware.ui = {}
getware.ui.content = {
    aff: {
        id:[],
        inner:[],
        rmclick: function(windowId, e) {
            $(e).parent().parent().parent().parent().remove();
            let fields = $('div[id=ui-window-' + windowId + ']').find('tr li[id=field]');
            let countFields = fields.length
            for(i = 0; i < countFields; i++) {
                let rows = $(fields[i]).find('input');
                let countRows = rows.length;
                for(j = 0; j < countRows; j++) {
                    let split = rows[j].id.split('x');
                    $(rows[j]).attr('id', split[0] + 'x' + i);
                }
                getware.ui.content.aff.id[windowId][0] = i;
            }
        },
        dblclick: function(windowId, e) {
            let split = e.split('x');
            if(split.length > 0) {
                if(split[1] > 0) {
                    let upValue = $('div[id=ui-window-' + windowId + '] #' + split[0] + 'x' + (split[1] - 1)).val();
                    $('div[id=ui-window-' + windowId + '] #' + e).val(upValue);
                }
            }
        },
        add: function(module, windowId) {
            getware.ui.content.aff.id[windowId][0]++;
            let insertRow = document.getElementById('row-' + windowId).insertRow(-1);
            let insertCell = insertRow.insertCell(-1);
            let temp = getware.ui.content.aff.inner[windowId].replace(/(xIDx)+/g, 'x' + getware.ui.content.aff.id[windowId][0]);

            if(getware.ui.content.aff.id[windowId][0] % 2 == 0)
                insertCell.innerHTML = temp.replace(/(xCLASSx)+/g, 'list_even ui-corner-all');
            else insertCell.innerHTML = temp.replace(/(xCLASSx)+/g, 'list_odd');
            $('div[id=ui-window-' + windowId + ']').parent().css('height', 'auto');
            let x = getware.ui.content.aff.id[windowId][1];
            let y = getware.ui.content.aff.id[windowId][0];
            $(insertRow).find('input[id=' + x + 'x' + y + ']').focus();
            getware.ui.content.render(module, windowId, insertRow);

        },
        remove: function(windowId) {
            let deleteRow = document.getElementById('row-' + windowId);
            if(deleteRow.rows.length > 2) {
                getware.ui.content.aff.id[windowId][0]--;
                deleteRow.deleteRow(deleteRow.rows.length - 1);
            }
            $('div[id=ui-window-' + windowId + ']').parent().css('height', 'auto');
        }
    },
    info: {
        grid: function(json) {
            let x = '';
            let image = ['error', 'none', 'success'];
            for(i=0; i < json.rows.length; i++) {
                x = $('div[id=overflow-c-' + json.window + '] input[id=' + json.rows[i] + '-' + json.window + ']');
                json.data[i] += 1;
                x.parent().parent().find('input[type=text]').css('background','url(\'./images/input_' + image[json.data[i]] + '.png\')');
            }
        },
        edit: function(json) {
            let x = '';
            let image = ['error', 'hide', 'success'];
            for(i = 0; i < json.rows.length; i++) {
                x = $('div[id=ui-window-' + json.window + '] td[id=img' + json.rows[i] + '] div');
                json.data[i]++;
                x.removeClass();
                if(json.data[i] > 1) json.data[i] = 2;
                x.addClass('icon icon-' + image[json.data[i]]);
                //x.html('<img src="images/'+image[json.data[i]]+'.png" title="">');
            }
        },
        add:function(json){
            let input, select;
            let image = ['output', 'input'];
            for(i = 0; i < json.rows.length; i++) {
                json.rows[i] = json.rows[i].replace(/(\[)+/g, '\\[');
                input = $('div[id=ui-window-' + json.window + '] input[id=' + json.rows[i] + ']');
                select = $('div[id=ui-window-' + json.window + '] select[id=' + json.rows[i] + ']');
                input.css('background','url(\'./images/' + image[json.data[i]] + '.png\')');
                select.css('background','url(\'./images/' + image[json.data[i]] + '.png\')');
            }
        },
        error:function(json){
            let input, select;
            let image = ['output', 'input'];

            let name = '';
            if(json.name) name = '[name=\\\'' + json.name + '\\\']';

            let obj = 'div[id=ui-window-' + json.window + ']';

            for(i = 0; i < json.rows.length; i++) {
                input = $(obj + ' input[id=\'' + json.rows[i] + '\']');
                select = $(obj + ' select[id=\'' + json.rows[i] + '\']');
                input.css('background','url(\'./images/' + image[json.data[i]] + '.png\')');
                select.css('background','url(\'./images/' + image[json.data[i]] + '.png\')');
            }
        }
    },
    html: function(json, row, i, extra = '') {
        //content = getware.ui.content.html(json.module, json.windowId, json.type[i], row, '', json.data[i]);
        let output = '';
        placeholder = 'placeholder="' + json.name[i] + '" '
        let size = '';
        if(name == '') size = ' size="25"';
        
        if(json.type[i] == 'date')
            output = '<input id="' + row + 'date' + json.windowId + '" name="' + row + json.windowId + '" ' + placeholder + 'type="date" maxlength="10" class="date" value="' + json.data[i] + '" datepicker="' + json.type[i] + '" ' + extra + ' />';
        
        if(json.type[i] == 'month')
            output = '<input id="' + row + 'date' + json.windowId + '" name="' + row + json.windowId + '" ' + placeholder + 'type="text" maxlength="10" class="date" value="' + json.data[i] + '" datepicker="' + json.type[i] + '" ' + extra + ' />';
        
        if(json.type[i] == 'year')
            output = '<input id="' + row + 'date' + json.windowId + '" name="' + row + json.windowId + '" ' + placeholder + 'type="text" maxlength="10" class="date" value="' + json.data[i] + '" datepicker="' + json.type[i] + '" ' + extra + ' />';
        
        if(json.type[i] == 'view') {
            output = '<input id="' + row + '" name="' + row + '" ' + placeholder + 'type="text" ' + size + 'maxlength="255" class="view" value="' + json.data[i] + '" view="' + json.type[i] + '" ' + extra + ' />';
            output += '<img id="' + row + '" src="images/view.png" width="16" title="Ver" type="view" />';
            label = '';
            
            if(typeof json.add !== 'undefined') {
                if(typeof json.add[parseInt(row)] !== 'undefined') {
                    output += '<img id="add" src="images/add.png" width="16" title="Agregar" type="add" />';
                    output += '<label id="add" style="display:none;">' + json.add[parseInt(row)] + '</label>';
                }
            }
            output += '<label id="filter" style="display:none;">' + json.filter[i] + '</label>';
        }
        if(json.type[i] == 'number')
            output = '<input id="' + row + '" name="' + row + '" ' + placeholder + 'type="number" step="any" ' + size + ' maxlength="255" class="normal" value="' + json.data[i] + '" ' + extra + ' />';

        if(json.type[i] == 'select') {
            output = '<select id="' + row + '" name="' + row + '" multiple="multiple" ' + placeholder + '>';
            for(j = 0; j < json.data[i][0].length; j++)
                output += '<option value="' + json.data[i][0][j] + '" selected>' + json.data[i][0][j] + '</option>';
            for(j = 0; j < json.data[i][1].length; j++)
                output+='<option value="' + json.data[i][1][j] + '">' + json.data[i][1][j] + '</option>';
            output += '</select>';
        }
        
        if(json.type[i] == 'varchar')
            output = '<input id="' + row + '" name="' + row + '" ' + placeholder + 'type="text" ' + size + ' maxlength="255" class="normal" value="' + json.data[i] + '" ' + extra + ' />';
        
        if(json.type[i] == 'pass')
            output = '<input id="' + row + '" name="' + row + '" ' + placeholder + 'type="password" ' + size + ' maxlength="255" class="normal" value="" />';
        
        if(json.type[i] == 'text') {
            output = '<textarea id="' + row + '" name="' + row + '" ' + placeholder + 'wrap="on" class="normal" ';
            output += 'style="width:calc(100% - 11px);height:92%;"' + extra + '>' + json.data[i] + '</textarea>';
        }
        return output;
    },
    add:function(json){
        return getware.ui.content.edit(json);
    },
    edit: function(json) {
        /*****************
        * MAKE X: ADD & EDIT
        ******************/
        let rest = 0;
        let height = 24;
        let marginLeft = 0;
        let row = json.ini;
        let bottom = 0;
        let output = '';
        
        let sync = true;
        for(i = 0; i < json.type.length; i++) {
            if(sync == true) {
                sync = false;
                output += '<div style="margin-left:' + marginLeft + 'px;float:left;width:330px;margin-top:8px;background:#ADD8E6;box-shadow: 0 0px 15px 5px #318BA8;">\n';
            }
            content = getware.ui.content.html(json, row, i);
            if(json.type[i] == 'text') {
                rest += 4;
                height = height * 4;
            } else height = 24;
            if((i == json.type.length - 1) || (rest >= json.br))
                bottom = 1;
            else bottom = 0;
            output += '<li class="list_even ui-widget-content ui-li" style="height:' + height + 'px;width:100%;border:1px dashed #8eacc1;border-bottom:' + bottom + 'px dashed #8eacc1;">\n';
            output += '<div id="0" class="ui-title " style="text-align:left;width: 100px;" title="'+ json.name[i] + '">'+ json.name[i] + '</div>\n';
            output += '<div id="img' + row + '" class="ui-title " style="padding-top:1px;width:16px;"><div>&nbsp;</div></div>\n';
            output += '<div id="1" class="ui-title " style="text-align:left;width:184px;height:100%;padding-top:1px;">' + content + '</div>\n';
            output += '</li>\n';

            if(rest >= json.br) {
                sync = true;
                marginLeft = 20;
                output += '</div>\n';
                json.br = json.br * 2;
            }
            if(i == json.type.length - 1) output += '</div>\n';
            row++;
            rest++;
        }
        output += '</div>';
        /*****************
        * END OF ADD & EDIT
        ******************/
        return output;
    },
    axx: function(json) {
        /*****************
        * MAKE XX: ADD
        ******************/
        let row = json.ini;
        let head = json.head;
        let width = json.width - 27;
        // ROW
        let output = '<table id="row-' + json.windowId + '" class="dialog-main" cellspacing="0" cellpadding="0" width="' + width + '">';
        output += '<tr>';
        output += '<td>';
        let countPX = 0;
        let widthDiff = 0;
        output += '<li class="ui-widget-content dialog-field" style="height:auto;">';       
        for(i = 0; (i < json.name.length) && (head > row); i++) {
            if(head == row + 1)
                json.size[i] = width - (widthDiff + countPX);
            else widthDiff += json.size[i];

            if(json.type[i] == 'text') {
                output += '<div id="' + i + '" name="' + i + '" class="ui-content dialog-row" style="padding:0px;width:' + width + 'px;height:48px;">';
            } else {
                countPX++;
                output += '<div id="' + i + '" name="' + i + '" class="ui-content dialog-row" style="padding:0px;width:' + json.size[i] + 'px;">';
            }
            output += getware.ui.content.html(json, row + 'x', i);
            output += '</div>';
            row++;
        }
        output += '</li>';
        
        countPX = 0;
        widthDiff = 0;
        head = json.head - json.ini;
        getware.ui.content.aff.inner[json.windowId] = '';
        for(i = head; i < json.name.length; i++) {
            
            if(i == json.name.length - 1)
                json.size[i] = width - (widthDiff + countPX);
            else widthDiff += json.size[i];
            // INI
            if(i == head) getware.ui.content.aff.inner[json.windowId] += '<li class="ui-widget-content dialog-field">';

            getware.ui.content.aff.inner[json.windowId] += '<div id="' + row + '" name="' + row + '" class="ui-content dialog-row" style="padding:0px;width:' + json.size[i] + 'px;">';
                    
            value = json.data[i];
            let extra =  'ondblclick="javascript:getware.ui.content.aff.dblclick(' + json.windowId + ',\'' + row + 'xIDx\');" ';
            getware.ui.content.aff.inner[json.windowId] += getware.ui.content.html(json, row + 'xIDx', i, extra);
            getware.ui.content.aff.inner[json.windowId] += '</div>';
            countPX++;
            row++;
                    
            // END
            if(i == json.name.length - 1)
                getware.ui.content.aff.inner[json.windowId] += '</li>';
        }
        output += '</td>';
        output += '</tr>';
        output += '</table>';

        output+='<p align="center">';
        getware.ui.content.aff.id[json.windowId] = [-1, json.head];
        output += '<input type="button" value="Agregar" onclick="javascript:getware.ui.content.aff.add(\'' + json.module + '\', ' + json.windowId + ');" />';
        output += '<input type="button" value="Eliminar" onclick="javascript:getware.ui.content.aff.remove(' + json.windowId + ');" />';
        output += '</p>';
        /*****************
        * END OF ADD XX
        ******************/
        return output;
    },
    uxx: function(json) {
        /*****************
        * MAKE XX: UPLOAD
        ******************/
       let row = json.ini;
       let head = json.head;
       let width = json.width - 27;
       head = json.head - json.ini;
       // ROW
       let output = '<form id="' + json.windowId + '">';
       output += '<table id="row-' + json.windowId + '" class="dialog-main" cellspacing="0" cellpadding="0" width="' + width + '">';
       output += '<tr>';
       output += '<td>';

       getware.ui.content.aff.inner[json.windowId] = '<li class="ui-widget-content dialog-field">';
       getware.ui.content.aff.inner[json.windowId] += '<div id="' + row + '" name="' + row + '" class="ui-content dialog-row" style="padding:0px;width:calc(100% - 1px);">';
       getware.ui.content.aff.inner[json.windowId] += '<input id="0xIDx" name="0xIDx" type="file" class="file" value="" accept="' + json.accept + '" />';
       getware.ui.content.aff.inner[json.windowId] += '</div>';
       getware.ui.content.aff.inner[json.windowId] += '</li>';
       
       output += '</td>';
       output += '</tr>';
       output += '</table>';
       output += '</form>';

       output+='<p align="center">';
       getware.ui.content.aff.id[json.windowId] = [-1, json.head];
       output += '<input type="button" value="Agregar" onclick="javascript:getware.ui.content.aff.add(\'' + json.module + '\', ' + json.windowId + ');" />';
       output += '<input type="button" value="Eliminar" onclick="javascript:getware.ui.content.aff.remove(' + json.windowId + ');" />';
       output += '</p>';
       /*****************
       * END OF UPLOAD XX
       ******************/
       return output;
    },
    render: function(module, windowId, element = false) {

        if(typeof element === 'boolean') {
            element = $('div[id=ui-window-' + windowId + ']');
        } else if(typeof element === 'string') {
            element = $('div[id=ui-window-' + windowId + '] ' + element);
        }

        $(element).find('input[type=button]').button();
        
        $(element).find('select').multiselect({minWidth: 180});

        $(element).find('img[type=view]').each(function() {
            this.addEventListener('click', function(event) {
                event.preventDefault();
                let label = $(this).parent().find('label[id=filter]').html();
                let random = $(this).parent().find('input').attr('name');
                getware.ui.submenu.get(windowId, this.id, module, label, random);
            });
        });
        
        $(element).find('img[type=add]').each(function() {
            this.addEventListener('click', function(event) {
                event.preventDefault();
                let label = $(this).parent().find('label[id=add]').html();
                if(label != '') {
                    url = 'module=admin&admin=' + label + '&add';
                    getware.get(url);
                } else return;
            });
        });

        $(element).find('input[type=text][view]').each(function() {
            this.addEventListener('keydown', function(event) {
                if(event.altKey && event.key == '+') {
                    let label = $(this).parent().find('label[id=add]').html();
                    if(label != '') {
                        url = 'module=admin&admin=' + label + '&add';
                        getware.get(url);
                    } else return;
                }
            });
            
            let label = $(this).parent().find('label[id=filter]').html();
            let filter = $(element).find(' input[id=' + label + ']').val();
            let url = 'index.php?module=admin&admin=' + module + '&autocomplete=' + this.id + '&filter=' + filter + '&ajax='
            $(this).autocomplete({
                source: url,
                minLength: 0,
                select: function(event, ui) {
                    $(this).val(ui.item.value);
                    if(ui.item.info) {
                        $('div[id=' + this.id + ']').html(ui.item.info);
                    }
                    return false;
                }
            });
        });
    }
}
