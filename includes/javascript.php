<?php

if(file_exists($_GET['file'])) {
 include('javascriptpacker.php');
 $script=file_get_contents($_GET['file']);
 $packer=new JavaScriptPacker($script,'Normal',true,false);
 $packed=$packer->pack();
 header('Accept-Ranges: bytes');
 header('Content-Length: '.strlen($packed));
 print $packed;
} else exit($_GET['file']);

?>
