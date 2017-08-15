<?php
session_start();

$PROTOCOL="http://";
$SERVER_A="a.com";
$SERVER_B="b.com";
$PATH="/";

$URL_A="$PROTOCOL$SERVER_A$PATH";
$URL_B="$PROTOCOL$SERVER_B$PATH";

$MAIN_FILE = "${URL_A}index.php"
?>
