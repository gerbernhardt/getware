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
?>
<style>
   .hero-image::after {
    display: block;
    position: relative;
    background-image: linear-gradient(to bottom, rgba(255, 255, 255, 0) 0, #fff 100%);
    margin-top: -150px;
    height: 150px;
    width: 90%;
    content: '';
}
</style>
<div class="hero-image">
  <img width="90%" src="images/logos/home.png">
</div>
