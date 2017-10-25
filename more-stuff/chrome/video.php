<?php
header("Cache-Control: no-cache, must-revalidate"); /* HTTP/1.1 */
header("Expires: -1"); /* Always invalid */

header("Content-type: video/ogg");

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
				
echo file_get_contents("movie.ogg");
?>