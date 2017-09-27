<?php
include(__DIR__ . "/../config.php");
header("Cache-Control: no-cache, must-revalidate"); /* HTTP/1.1 */
header("Expires: -1"); /* Always invalid */

include(__DIR__ . "/../util/cors.php");
header("Content-type: audio/mp3");

echo file_get_contents("audio.mp3");
?>