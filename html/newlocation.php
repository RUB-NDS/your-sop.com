<?php
include(__DIR__ . "/../config.php");
header("Cache-Control: no-cache, must-revalidate"); /* HTTP/1.1 */
header("Expires: -1"); /* Always Invalid */

include(__DIR__ . "/../util/cors.php");
header("Content-type: text/html");

if (!isset($_GET['func'])) {
	$func = "UNDEFINED";
} else {
	$func = htmlentities($_GET['func']);
}
?>
<html>
	<head><title>ED: HTML</title></head>
	<body>
	<h1 id="h1">ED: HTML</h1>
		<script>var data = {};
		data.id = '<?php echo $func ?>';
		data.value = 'partial (from wrapper)';
		var message = JSON.stringify(data);
		window.parent.postMessage(message, "<?php echo "$PROTOCOL$SERVER_A"; ?>");
	</script>
	</body>
</html>