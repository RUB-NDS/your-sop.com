<?php
include(__DIR__ . "/../config.php");
header("Cache-Control: no-cache, must-revalidate"); /* HTTP/1.1 */
header("Expires: -1"); /* Always invalid */

include(__DIR__ . "/../util/cors.php");
header("Content-type: image/svg+xml");

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
<svg id="svgroot" xmlns="http://www.w3.org/2000/svg" height="111" width="111">
	<rect width="111" height="111" style="fill:rgb(255,0,0);" />
	<script>

	<?php 
	include(__DIR__ . "/../util/call_stack.php");
	?>

	function svg_script() {<?php
	switch($operation) {
		case "read": ?>
			
		var data = {};
		data.id = '<?php echo $func ?>';
		data.value = 'no';
		data.additionalInfo = 'SVG-Script-Source:\n\n'+svg_script.toString() + '\n\nScript defined in HD:';
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
			var pDoc = window.parent.document;
			description = 'SVG-Script-Source: ';
			description += svg_script.toString();
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
			data.value = 'partial';
			data.additionalInfo = 'Partial write access on window.parent.location is always possible!\n\nSVG-Script-Source:\n\n'+ svg_script.toString() + '\n\nScript defined in HD:';
			var message = JSON.stringify(data);
			window.parent.postMessage(message, "<?php echo "$PROTOCOL$SERVER_A"; ?>");
		}
		
		<?php
			break;
		default: ?>
	
		var data = {
			'id': '<?php echo $func ?>',
			'value': 'yes',
			'additionalInfo': 'SVG-Script-Source:\n\n' + svg_script.toString() + '\n\nScript defined in HD:'
		};
		var message = JSON.stringify(data);
		window.parent.postMessage(message, "<?php echo "$PROTOCOL$SERVER_A"; ?>");<?php
		break;
	}?>
	document.free = true;
	}
	call(svg_script);
	depleteQueue();
	</script>
</svg>