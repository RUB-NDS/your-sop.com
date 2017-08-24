<?php
include(__DIR__ . "/../config.php");
header("Cache-Control: no-cache, must-revalidate"); /* HTTP/1.1 */
header("Expires: -1"); /* Always invalid */

include(__DIR__ . "/../util/cors.php");
header("Content-type: image/png");

$img = imagecreate(111, 111);
$background = imagecolorallocate($img, 255, 0, 0);
imagepng($img);
imagedestroy($img);
?>