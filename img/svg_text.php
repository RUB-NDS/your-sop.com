<?php
include("../config.php");
header("Cache-Control: no-cache, must-revalidate"); /* HTTP/1.1 */
header("Expires: -1"); /* Always invalid */

include("../util/cors.php");
header("Content-type: image/svg+xml");
?>
<svg xmlns="http://www.w3.org/2000/svg">
	<rect width="50" height="50" style="fill:rgb(255,0,0);"/>
	<text x="10" y="30">red</text>
</svg>