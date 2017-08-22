<?php
include("../config.php");
header("Cache-Control: no-cache, must-revalidate"); /* HTTP/1.1 */
header("Expires: -1"); /* Always invalid */

include("../util/cors.php");
header("Content-type: text/html");
header('Content-Type: text/css');
?>
h1 {
    color: red;
}
