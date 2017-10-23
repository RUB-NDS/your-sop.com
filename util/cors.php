<?php
if (!isset($URL_A) || !isset($URL_B)) { die(); }
	
if (isset($_GET['origin'])) {
	$case = $_GET['origin'];
	switch ($case) {
		case "A":
			if($_GET['exec'] === "suborigin") { 
				// https://w3c.github.io/webappsec-suborigins/#cors-ac
				header("Access-Control-Allow-Suborigin: your");
				header("Access-Control-Allow-Origin: http-so://your.your-sop.com");
			} else {
				header("Access-Control-Allow-Origin: $PROTOCOL$SERVER_A");
			}	
			break;
		case "B":
			if($_GET['exec'] === "suborigin") { 
				header("Access-Control-Allow-Suborigin: other");
				header("Access-Control-Allow-Origin: http-so://other.your-sop.com");
			} else {
				header("Access-Control-Allow-Origin: $PROTOCOL$SERVER_B");		
			}
			break;
		case "wildcard":
			header("Access-Control-Allow-Origin: *");
			if($_GET['exec'] === "suborigin") { 
				header("Access-Control-Allow-Suborigin: *");
			}
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