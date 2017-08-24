<?php
include("../config.php");
header("Cache-Control: no-cache, must-revalidate"); /* HTTP/1.1 */
header("Expires: -1"); /* Always invalid */

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
function js_script() {<?php
switch($operation) {
	case "read": ?>
		
	var data = {};
	data.id = '<?php echo $func ?>';
	data.value = 'no';
	data.additionalInfo = 'JS-Script-Source:\n\n'+js_script.toString() + '\n\nScript defined in HD:';
	try {
		var p = window.parent;
		if (p.frames.length > 0) {
			data.value = 'partial';
		}
		if (document.documentElement.innerHTML.length > 0) {
			data.value = 'yes(DOM)';
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
		description = 'JS-Script-Source: ';
		description += js_script.toString();
		var text = document.createElement("acronym");
		text.setAttribute("title", description);
		text.appendChild(document.createTextNode('yes(DOM)'));
		var target = document.getElementById('<?php echo $func ?>');
		target.innerHTML='';
		target.appendChild(text);
	}
	catch (ex) {
		var data = {};
		data.id = '<?php echo $func ?>';
		data.value = 'partial';
		data.additionalInfo = 'Partial write access on window.parent.location is always possible!\n\nJS-Script-Source:\n\n'+ js_script.toString() + '\n\nScript defined in HD:';
		var message = JSON.stringify(data);
		window.parent.postMessage(message, "<?php echo "$PROTOCOL$SERVER_A"; ?>");
	}
	
	<?php
		break;
	default: ?>

	var data = {
		'id': '<?php echo $func ?>',
		'value': 'yes',
		'additionalInfo': 'JS-Script-Source:\n\n' + js_script.toString() + '\n\nScript defined in HD:'
	};
	var message = JSON.stringify(data);
	window.parent.postMessage(message, "<?php echo "$PROTOCOL$SERVER_A"; ?>");<?php
		break;
}?>
document.free = true;
	
}
call(js_script); /* modify to also allow different executions. */