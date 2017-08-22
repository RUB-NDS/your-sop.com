<?php
include("../config.php");
header("Cache-Control: no-cache, must-revalidate"); /* HTTP/1.1 */
header("Expires: -1"); /* Always invalid */

include("../util/cors.php");
header("Content-type: text/html");

if (!isset($_GET['operation'])) {
	$operation = "UNDEFINED";
} else {
	$operation = $_GET['operation'];
}

/* because of the complexity of partial write, we need to add some delay */
/* so that window.onhashchange in top can handle all hash changes */
if ($operation == "write") {
	/*time_nanosleep(rand(1,2),rand(0,999999999)); */
	$counter = $_SESSION['write']++;
	/*time_nanosleep(0,150000000*$counter); */
}

if (!isset($_GET['func'])) {
	$func = "UNDEFINED";
} else {
	$func = htmlentities($_GET['func']);
}

?>
<html>
	<head><title>ED: HTML</title></head>
	<body>
	<h1 id="h1">ED: HTML with JavaScript</h1>
	<script>
	function html_script() {<?php
	switch($operation) {
		case "read": ?>
			
		var data = {};
		data.id = '<?php echo $func ?>';
		data.value = 'no';
		data.additionalInfo = 'HTML-Script-Source:\n\n'+html_script.toString() + '\n\nScript defined in HD:';
		try {
			var p = window.parent;
			if (p.frames.length > 0) {
				data.value = 'partial';
				if (p.document.documentElement.innerHTML.length > 0) {
					data.value = 'yes(DOM)';
				}
			}
			
		}
		catch (ex) {
			data.additionalInfo = ex.message + "\n\n" + data.additionalInfo + '\n\nScript defined in HD:';
		} finally {
			var message = JSON.stringify(data);
			window.parent.postMessage(message, "<?php echo "$PROTOCOL$SERVER_A"; ?>");
		}
			
		<?php
			break;
		case "write": ?>
		try {
			var pDoc = window.top.document;
			description = 'HTML-Script-Source: ';
			description += html_script.toString();
			var text = pDoc.createElement("acronym");
			text.setAttribute("title", description);
			text.appendChild(pDoc.createTextNode('yes(DOM)'));
			var target = pDoc.getElementById('<?php echo $func ?>');
			target.innerHTML='';
			target.appendChild(text);
		}
		catch (ex) {
			var data = {};
			data.id = '<?php echo $func ?>';
			try {
				<!-- window.parent.location = "<?php echo $URL_A."html/newlocation.php?func=".$func ?>"; -->
				top.location = "<?php echo $MAIN_FILE."#partialwrite_".$func ?>";
				/* data.value = 'partial'; */
				data.value = 'no';
				data.additionalInfo = 'HTML-Script-Source:\n\n'+ html_script.toString() + '\n\nScript defined in HD:';
			}
			catch (ex) {
				data.value = 'no*';
				data.additionalInfo = ex.message+"\n\nHTML-Script-Source:\n\n'+ html_script.toString() + '\n\nScript defined in HD:';";
			} finally {
				var message = JSON.stringify(data);
				window.top.postMessage(message, "<?php echo "$PROTOCOL$SERVER_A"; ?>");
			}
		}
		
		<?php
			break;
		default: ?>
	
		var data = {
			'id': '<?php echo $func ?>',
			'value': 'yes',
			'additionalInfo': 'HTML-Script-Source:\n\n' + html_script.toString() + '\n\nScript defined in HD:'
		};
		var message = JSON.stringify(data);
		window.parent.postMessage(message, "<?php echo "$PROTOCOL$SERVER_A"; ?>")<?php
			break;
	}?>
		
	}
	html_script();
	</script>
	</body>
</html>