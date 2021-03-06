<?php
/*
 * Keep It Simple, Stupid!
 * Filename: kernel/dialog.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();

class kernel_dialog{
    
    function autocomplete() {
        global $_DB, $_ADMIN, $_TABLE, $CORE, $_MODULE, $KERNEL;
        if(isset($_GET['submenu'])) {
            $x = intval($_GET['submenu']);
            include('kernel/dialog.submenu.php');
        }elseif(isset($_GET['autocomplete']) && isset($_GET['term'])) {
            $x = intval($_GET['autocomplete']);
            include('kernel/dialog.autocomplete.php');
        }
    }

    # DIALOG JSON GENERATOR
    function header($sync) {
        global $_ADMIN, $_TABLE, $_MODULE;

        $pattern = array('/~/', '/_/');
        $replacement = array(' > ', ' ');

        if(!isset($_MODULE['title']))
            $_MODULE['title'] = preg_replace($pattern, $replacement, $_TABLE['name']);

        if(isset($_MODULE['json']['extra']))
            $output = $_MODULE['json']['extra'];
        else $output='{run:"getware.ui.dialog.make",';

        $output .= 'title:"' . strtoupper($sync . ' ' . $_MODULE['title']) . '",';
        $output .= 'module:"' . $_GET['admin'] . '",';
        $output .= 'ini:' . $_ADMIN['ini'] . ',';
        
        if(!isset($_MODULE['render']))
            $output .= 'afterAppend:"getware.ui.content.render",';
        
        # ROWS BR
        if(isset($_MODULE['br']))
            $output .= 'br:'.$_MODULE['br'].',';
        else $output .= 'br:12,';

        # WINDOW WIDTH
        if(isset($_MODULE['width']))
            $output .= 'width:'.$_MODULE['width'] . ',';
        else $output .= 'width:354,';
        
        return $output;
    }

}

$KERNEL->dialog=new kernel_dialog;
include('kernel/dialog.upload.php');
include('kernel/dialog.add.php');
include('kernel/dialog.axx.php');
include('kernel/dialog.edit.php');
include('kernel/dialog.remove.php');

?>