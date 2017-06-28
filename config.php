<?php
session_start();

$PROTOCOL="http://";
$SERVER_A="your-sop.com";
$SERVER_B="other-sop.com";
$PATH="/";

$URL_A="$PROTOCOL$SERVER_A$PATH";
$URL_B="$PROTOCOL$SERVER_B$PATH";

$MAIN_FILE = "${URL_A}index.php"
?>
