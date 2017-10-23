<?php
session_start();

$PROTOCOL="http://";

$SERVER_A="your-sop.com";

/* Suborigin test cases must have the same server B and different headers */
if ($_GET['exec'] === "suborigin") {
	$SERVER_B="your-sop.com";
} else {
	$SERVER_B="other-domain.org";
}

$PATH="/";


$URL_A="$PROTOCOL$SERVER_A$PATH";
$URL_B="$PROTOCOL$SERVER_B$PATH";

$MAIN_FILE = "${URL_A}index.php"
?>
