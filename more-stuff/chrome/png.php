<?php
header("Cache-Control: no-cache, must-revalidate"); /* HTTP/1.1 */
header("Expires: -1"); /* Always invalid */

header("Content-type: image/png");

if ($_GET[suborigin] == "other") {
	/* no access (cross-origin) */
	header("Suborigin: other");
} elseif ($_GET[suborigin] == "your") {
	/* access (same-origin) */
	header("Suborigin: your");
}	

if ($_GET[cors] == "your") {
	header("Access-Control-Allow-Suborigin: your");
	header("Access-Control-Allow-Origin: http-so://your.your-sop.com");
} elseif ($_GET[cors] == "other") {
	header("Access-Control-Allow-Suborigin: other");
	header("Access-Control-Allow-Origin: http-so://other.your-sop.com");
}
				
$img = imagecreate(111, 111);
$background = imagecolorallocate($img, 255, 0, 0);
imagepng($img);
imagedestroy($img);
?>