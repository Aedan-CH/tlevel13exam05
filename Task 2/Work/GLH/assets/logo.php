<?php
$img = imagecreatefromstring("farmer.jpg"); //or whatever loading function you need
$white = imagecolorallocate($img, 255, 255, 255);
imagecolortransparent($img, $white);
imagepng($img, "topbar.php");