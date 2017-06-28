<?php
include("../config.php");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

include("../util/cors.php");
header("Content-type: application/x-javascript");

if (!isset($_GET['operation'])) {
	$operation = "UNDEFINED";
} else {
	$operation = $_GET['operation'];
}

if (!isset($_GET['func'])) {
	$func = "UNDEFINED";
} else {
	$func = htmlentities($_GET['func']);
}

?>

function <?php echo $operation ?>() {
	<!-- This function has a random name -->
	var secret = 42;
	return secret;
}
