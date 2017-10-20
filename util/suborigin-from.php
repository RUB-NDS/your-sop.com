<?php
if (!isset($SERVER_B) && !isset($SERVER_A)) { die(); }

if (isset($_GET['from']) && $SERVER_B=$SERVER_A) {
	$fromOrigin = substr($_GET['from'],3,1);
	switch ($fromOrigin) {
		case "A":
			header('Suborigin: your');
			break;
		case "B":
			header('Suborigin: other');
			break;
	}
}
?>