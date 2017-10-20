<?php
if (!isset($SERVER_B) && !isset($SERVER_A)) { die(); }

if (isset($_GET['to']) && $SERVER_B=$SERVER_A) {
	$toOrigin = substr($_GET['to'],3,1);
	switch ($toOrigin) {
		case "A":
			header('Suborigin: your');
			break;
		case "B":
			header('Suborigin: other');
			break;
	}
}
?>