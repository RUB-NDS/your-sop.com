<?php
include(__DIR__ . "/../config.php");
header("Cache-Control: no-cache, must-revalidate"); /* HTTP/1.1 */
header("Expires: -1"); /* Always invalid */

include(__DIR__ . "/../util/cors.php");
header("Content-type: text/vtt");
?>
WEBVTT FILE

1
00:00:00.500 --> 00:00:05.000
Teststring for WebVTT