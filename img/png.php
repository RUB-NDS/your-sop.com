<?php
include("../config.php");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

include("../util/cors.php");
header("Content-type: image/png");

$img = imagecreate(111, 111);
$background = imagecolorallocate($img, 255, 0, 0);
imagepng($img);
imagedestroy($img);
?>