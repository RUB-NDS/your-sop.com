<?php
if (!isset($URL_A) || !isset($URL_B)) { die(); }
	
if (isset($_GET['origin'])) {
	$case = $_GET['origin'];
	switch ($case) {
		case "A":
			header("Access-Control-Allow-Origin: $PROTOCOL$SERVER_A");
			break;
		case "B":
			header("Access-Control-Allow-Origin: $PROTOCOL$SERVER_B");		
			break;
		case "wildcard":
			header("Access-Control-Allow-Origin: *");
			break;
	}
}
	
if (isset($_GET['credentials'])) {
	$case = $_GET['credentials'];
	switch ($case) {
		case "true":
			header('Access-Control-Allow-Credentials: true');
			break;
		case "false":
			header('Access-Control-Allow-Credentials: false');	
			break;
	}
}
?>