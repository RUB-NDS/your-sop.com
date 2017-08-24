<?php
include(__DIR__ . "/../config.php");
header("Cache-Control: no-cache, must-revalidate"); /* HTTP/1.1 */
header("Expires: -1"); /* Always invalid */

include(__DIR__ . "/../util/cors.php");
header("Content-type: image/svg+xml");
?>
<svg id="svgroot" xmlns="http://www.w3.org/2000/svg" height="111" width="111">
	<rect width="111" height="111" style="fill:rgb(255,0,0);" />
</svg>