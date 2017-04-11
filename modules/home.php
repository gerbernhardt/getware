<?php
/*
 * Keep It Simple, Stupid!
 * Filename: modules/home.php
 * by German Bernhardt
 * E-mail: <german.bernhardt@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License.
 */
if(!preg_match('/index.php/',$_SERVER['PHP_SELF'])) header('Location: ./')&&exit();
print '<section class="main">';
print '<ul class="ch-grid">';
$sql='SELECT x.* FROM movies AS x LIMIT 20';
if($result=mysqli_query($_DB['session'],$sql)){
 while($fetch=$result->fetch_array()) {print '<li>';
	print '<a href="javascript:player.make('.$fetch['id'].');">';
	print ' <div class="ch-item" style="background-image:url('.$fetch['image'].');">';
	print '  <div class="ch-info"  title="'.$fetch['description'].'"><h3>'.$fetch['title'].'</h3></div>';
	print ' </div>';
	print '</a>';
 }
}

print '</ul>';
print '</section>';
?>
