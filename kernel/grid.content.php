<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel/grid.content.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ../')&&exit();

if(!isset($_MODULE['output']))
    $append = 'false';
else $append = 'true';

$grid = $_MODULE['grid'];

if(isset($grid['edit']))
    $grid['edit'] = $this->make_array($grid['edit']);

$output = '{';
$output .= 'run:"getware.ui.grid.make",';
$output .= 'module:"' . $_GET['admin'] . '",';
$output .= 'window:"td[id=content_center]",';
$output .= 'append:"' . $append . '",';

$type = 'content_center';
$pattern = array('/~/', '/_/');
$replacement = array(' > ', ' ');
if(!isset($_MODULE['title'])) $_MODULE['title'] = preg_replace($pattern, $replacement, $_TABLE['name']);

$output .= 'title:"' . strtoupper($_MODULE['title']) . '",';
$output .= 'menu:' . $this->menu() . ',';
$output .= 'navbar:' . $this->navbar() . ',';

# INIT TITLE
$output .= 'columns:[';
$count = count($grid['field']);
for($i = 0; $i < $count; $i++) {

    $name = $grid['name'][$i];
    $field = $grid['field'][$i];
    $column = $_TABLE['column'];

    $x = $column['eman'][$field];

    $output .= '{';
    $output .= 'name:"' . utf8_encode(ucwords($name)) . '",';
    $output .= 'field:"' . utf8_encode($field) . '",';

    $x = $column['eman'][$field];

    if(preg_match('/tinyint|int|double|float/i', $column['type'][$x])) {
        $align = 'right';
        if(isset($grid['align'][$name])) {
            $align = $grid['align'][$name];
        } elseif(preg_match('/REFERENCE/i', $column['function'][$x])) {
            $align = 'left';
        }
        $output .= 'align:"' . $align . '",';
    }

    if(isset($grid['edit'][$field])) {
        $sync = $column['eman'][$field];
        $output .= 'edit:"' . $KERNEL->row_type($sync) . '",';
    } else $output .= 'edit:0,';
    $output .= 'width:' . ($grid['size'][$i] * 10) . '}';
    if($i < $count - 1) $output .= ',';

}
$output .= '],';
# END TITLE

# INIT RESULT
$output .= 'data:[';
if($result = mysqli_query($_DB['session'], $sql)) {
    
    $count_rows = mysqli_num_rows($result);
    for($i = 0; $fetch = $result->fetch_array(); $i++) {
        $output .= '["' . $fetch[0] . '",';
        for($j = 1; $j <= $count; $j++) {
            if($fetch[$j] == '') $fetch[$j] = 'NULL';
            #
            # MULTIPLEX  SELECT DIMENSIONAL
            #
            $eman = $column['eman'][$grid['field'][$j - 1]];
            if(preg_match('/REFERENCES/i', $column['function'][$eman])) {
                $comment = $column['comment'][$eman];
                $field = 'CONCAT(';
                $count_c = count($comment);
                for($k = 1; $k < $count_c; $k++) {
                    //$column['comment'][$x][0]='afiliados_sexos#afiliado#sexo#sexos';
                    //$column['comment'][$x][1]='nombre';
                    // SUB REGISTROS
                    if(preg_match('/\[*\]/', $comment[$k])) {
                    $reg = preg_replace('#(.*?)\[.*#si','\1', $comment[$k]);
                    $subreg = explode(':', preg_replace('#.*\[(.*?)\]#si','\1', $comment[$k]));
                    $field .= '(SELECT xx.' . $subreg[1] . ' FROM `' . $subreg[0] . '` AS xx WHERE x.' . $reg . '=xx.id)';
                    } else $field .= 'x.' . $comment[$k];

                    $field .= ',\'' . $column['separator'][$eman][$k] . '\'';
                    if($k < $count_c - 1) $field .= ',';
                }
                $field .= ')';

                $table = explode('#', $comment[0]);
                $sql = 'SELECT ' . $field . ' FROM `' . $table[3] . '` AS x';
                $sql .= ' INNER JOIN `' . $table[0] . '` AS x0 ON x0.' . $table[2] . ' = x.id AND x0.' . $table[1] . ' = ' . $fetch['id'];
                if($result_r = mysqli_query($_DB['session'], $sql)) {
                    $data = [];
                    while($fetch_r = mysqli_fetch_array($result_r)) {
                        $data[] = '"' . $fetch_r[0] . '"';
                    }
                    $data = implode(',', $data);
                    if($data != '')
                        $output .= '[' . $data . ']';
                    else $output .= '"NULL"';
                } else $KERNEL->alert(mysqli_error($_DB['session']));
                #
                # END OF MULTIPLEX
                #
            } else $output .= '"' . $fetch[$j] . '"';
            if($j < $count) $output .= ',';
        }
        // GRID EXTRA
        if(function_exists('grid_extra')) $output .= grid_extra($fetch);
        // GRID TOOLTIP
        if(function_exists('grid_tooltip')) $output .= grid_tooltip($fetch);

        $output .= ']';
        if($i < $count_rows - 1) $output .= ',';
    }
}

$output .= ']';
if(isset($_MODULE['extra']))
    $output .= ',extra:[' . $_MODULE['extra'] . ']';
$output .= '}';

if(!isset($_MODULE['output']))
    $_MODULE['output'] = $output;
else $_MODULE['output'] .= ',' . $output;

?>