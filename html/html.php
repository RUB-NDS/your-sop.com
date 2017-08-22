<?php
include("../config.php");
header("Cache-Control: no-cache, must-revalidate"); /* HTTP/1.1 */
header("Expires: -1"); /* Always invalid */

include("../util/cors.php");
header("Content-type: text/html");
?>
<html>
	<head><title>ED: HTML</title></head>
	<body>
	<h1 id="h1">ED: HTML</h1>
	</body>
</html>