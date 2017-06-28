<?php
include("../config.php");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

include("../util/cors.php");
header("Content-type: image/svg+xml");
?>
<svg id="svgroot" xmlns="http://www.w3.org/2000/svg" height="111" width="111">
	<rect width="111" height="111" style="fill:rgb(255,0,0);" />
</svg>