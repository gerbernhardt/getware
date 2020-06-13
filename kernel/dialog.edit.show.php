<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel/dialog.edit.show.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/', $_SERVER['PHP_SELF'])) header('Location: ../')&&exit();
if(!isset($_GET['save'])) {
    if(!is_array($_GET['edit'])) $CORE->secure_get('edit');
    $sql = 'SELECT x.* FROM `' . $_TABLE['name'] . '` AS x WHERE x.id IN(' . implode(',', $_GET['edit']) . ')';
    if($result['j'] = mysqli_query($_DB['session'], $sql)) {
        $_MODULE['output'] = '';
        for($j = 0; $fetch['j'] = mysqli_fetch_array($result['j']); $j++) {
            $_MODULE['output'] .= $KERNEL->dialog->header(_EDIT);
            $_MODULE['output'] .= 'append:"edit",id:"' . $fetch['j']['id'] . '",';
            if($upload) $_MODULE['output'] .= 'buttom:"addfile",';
            $name = 'name:[';
            $type = 'type:[';
            $data = 'data:[';
            $filter = 'filter:[';
            for($i = $_ADMIN['ini']; $i < $_ADMIN['end']; $i++) {
                $name .= '"' . utf8_encode(strtoupper(str_replace('_', ' ', $_TABLE['column']['name'][$i]))) . '"';
                $type .= '"' . $KERNEL->row_type($i) . '"';
                if($_TABLE['column']['function'][$i] == 'REFERENCE') {
                    #
                    # MULTIPLEX  SELECT
                    #
                    $field = 'CONCAT(';
                    for($k = 1; $k < count($_TABLE['column']['comment'][$i]); $k++) {
                        // SUB REGISTROS
                        if(preg_match('/\[*\]/', $_TABLE['column']['comment'][$i][$k])) {
                            $reg = preg_replace('#(.*?)\[.*#si','\1', $_TABLE['column']['comment'][$i][$k]);
                            $subreg = explode(':', preg_replace('#.*\[(.*?)\]#si','\1', $_TABLE['column']['comment'][$i][$k]));
                            $field .= '(SELECT xx . ' . $subreg[1] . ' FROM `' . $subreg[0] . '` AS xx WHERE x . ' . $reg . '=xx.id)';
                        } else $field .= 'x . ' . $_TABLE['column']['comment'][$i][$k];

                        $field .= ',\'' . $_TABLE['column']['separator'][$i][$k] . '\'';
                        if($k < count($_TABLE['column']['comment'][$i]) - 1) $field .= ',';
                    }
                    $field .= ')';

                    $sql = 'SELECT ' . $field . ' FROM `' . $_TABLE['column']['comment'][$i][0] . '` AS x WHERE x.id=\'' . $fetch['j'][$i] . '\'';
                    if($result['i'] = mysqli_query($_DB['session'], $sql)) {
                        if($fetch['i'] = mysqli_fetch_array($result['i']))
                            $data .= '"' . $fetch['i'][0] . '"';
                        else $data .= '"NULL"';
                    } else $KERNEL->alert(mysqli_error($_DB['session']));

                } elseif($_TABLE['column']['function'][$i] == 'REFERENCES') {
                    #
                    # MULTIPLEX  SELECT DIMENSIONAL
                    #
                    $field = 'CONCAT(';
                    for($k = 1; $k < count($_TABLE['column']['comment'][$i]); $k++) {
                        //$_TABLE['column']['comment'][$i][0] = 'afiliados_sexos#afiliado#sexo#sexos';
                        //$_TABLE['column']['comment'][$i][1] = 'nombre';
                        // SUB REGISTROS
                        if(preg_match('/\[*\]/', $_TABLE['column']['comment'][$i][$k])) {
                            $subreg = explode(':', preg_replace('#.*\[(.*?)\]#si','\1', $_TABLE['column']['comment'][$i][$k]));
                            $field .= '(SELECT xx . ' . $subreg[1] . ' FROM `' . $subreg[0] . '` AS xx WHERE x . ' . $reg . '=xx.id)';
                        } else $field .= 'x . ' . $_TABLE['column']['comment'][$i][$k];

                        $field .= ',\'' . $_TABLE['column']['separator'][$i][$k] . '\'';
                        if($k < count($_TABLE['column']['comment'][$i]) - 1) $field .= ',';
                    }
                    $field .= ')';

                    $table = explode('#', $_TABLE['column']['comment'][$i][0]);
                    // SELECT
                    $sql = 'SELECT x.id,' . $field . ' FROM `' . $table[3] . '` AS x';
                    $sql .= ' INNER JOIN `' . $table[0] . '` AS x0 ON x0 . ' . $table[2] . '=x.id AND x0 . ' . $table[1] . ' = ' . $fetch['j']['id'];
                    $data_select = array();
                    $data_not_select = array();
                    $data_out = array();
                    if($result['i'] = mysqli_query($_DB['session'], $sql)) {
                        while($fetch['i'] = mysqli_fetch_array($result['i'])) {
                            $data_out[] = '"' . $fetch['i'][0] . '"';
                            $data_select[] = '"' . $fetch['i'][1] . '"';
                        }
                    } else $KERNEL->alert(mysqli_error($_DB['session']));
                    // NO SELECT
                    $sql = 'SELECT ' . $field . ' FROM `' . $table[3] . '` AS x';
                    $implode = implode(',', $data_out);
                    if($implode)
                        $sql .= ' WHERE x.id NOT IN('.implode(',', $data_out) . ')';
                    if($result['i'] = mysqli_query($_DB['session'], $sql)) {
                        while($fetch['i'] = mysqli_fetch_array($result['i'])) {
                            $data_not_select[] = '"' . $fetch['i'][0] . '"';
                        }
                    } else $KERNEL->alert(mysqli_error($_DB['session']));
                    $data .= '[[' . implode(',', $data_select) . '],[' . implode(',', $data_not_select) . ']]';
                } else $_TABLE['column']['name'][$i] == 'password' ? $data .= '""' : $data .= '"' . $fetch['j'][$i] . '"';
                if(isset($_MODULE['submenu']['filter'][$_TABLE['column']['name'][$i]])) {
                    $x = $_MODULE['submenu']['filter'][$_TABLE['column']['name'][$i]];
                    $filter .= '"'.$_TABLE['column']['eman'][$x].'"';
                } else $filter .= '""';
                #
                # END OF REFERENCES
                #
                if($i < $_ADMIN['end'] - 1) {
                    $name .= ',';
                    $type .= ',';
                    $data .= ',';
                    $filter .= ',';
                }
            }
            if(!isset($_MODULE['add'])) $_MODULE['add'] = '';
            $_MODULE['output'] .= $name . '],' . $type . '],' . $data . '],' . $filter . ']' . $_MODULE['add'] . '}';
            if($j < mysqli_num_rows($result['j']) - 1) $_MODULE['output'] .= ',';
        }
    }
    $KERNEL->json_print();
    exit();
}
?>