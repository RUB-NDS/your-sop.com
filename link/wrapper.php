<?php
include(__DIR__ . "/../config.php");
header("Cache-Control: no-cache, must-revalidate"); /* HTTP/1.1 */
header("Expires: -1"); /* Always invalid */

include(__DIR__ . "/../util/cors.php");
header("Content-type: text/html");

$ee = "LINK";
$notSet = "(not set)";
$anonymous = "anonymous";
$usecredentials = "use-credentials";

$arrayCrossOrigin = array($notSet, $anonymous, $usecredentials);
$arrayOrigin = array($notSet,"A","B","wildcard");
$arrayCredentials = array($notSet,"true","false");

function readVar($name, $allowedValues) {
	if (!isset($_GET[$name])) {
		die();
	}
	$value = $_GET[$name];
	if (!in_array($value, $allowedValues, true)) {
		die();
	}
	/*echo  "<p>".$value."</p>"; */
	return $value;
}

if (!isset($_GET['func'])) {
	die();
} else {
	$id = $_GET['func'];
}

	
$from = readVar("from", array("ED A", "ED B", "HD A"));
$to = readVar("to", array("ED A", "ED B", "HD A"));
$credentials = readVar("credentials", $arrayCredentials);
$origin = readVar("origin", $arrayOrigin);
$crossOrigin = readVar("crossOrigin", $arrayCrossOrigin);
$operation = readVar("operation", array("read", "write", "execute"));
?>
<html>
	<head>
		<title>ED: CSS</title>
	</head>
	<body>
	<h1 id="h1">CSS</h1>
	<table>
	<tr><td>FROM:</td><td><?php echo $from; ?></td></tr>
	<tr><td>TO:</td><td><?php echo $to ?></td></tr>
	<tr><td>CREDENTIALS:</td><td><?php echo $credentials ?></td></tr>
	<tr><td>ORIGIN:</td><td><?php echo $origin ?></td></tr>
	<tr><td>CROSSORIGIN ATTRIBUTE:</td><td><?php echo $crossOrigin ?></td></tr>
	<tr><td>OPERATION:</td><td><?php echo $operation ?></td></tr>
	<tr><td>ID:</td><td><?php echo $id ?></td></tr>
	</table>
	<script>


	function sleep(timeout) {
		return new Promise(resolve => setTimeout(resolve, timeout));
	}

	function call(func, args = []) {
		if(!document.callQueue) {
			document.callQueue = Array();
		}
		document.callQueue.push([func, args]); 
	}

	/* This solution only works for javascript so far. The protocol could be set with a PHP variable. */
	/* Maybe modify existing code such that every test case sets free = true in its last line by default */
	async function depleteQueue() { /* async: allows await to be used */
		if(!document.working || document.working == false) {
			document.working = true;
		}
		else {
			return 0;
		}
		var func;
		if(!document.free) {
			document.free = true;
		}
		var i = 0;
		while (i < document.callQueue.length) {
			if (document.callQueue[i]) { /* function name */
 				var code = document.callQueue[i][0].toString(); /* get source code */
				while (document.free == false)
				{
					await sleep(10);
				}
				document.free = false;
				/* The following line should be set according to the execution that is preferred via PHP */
				<?php 
				if (isset($_GET['exec']) && $_GET['exec'] === 'js') {
					echo 'window.location = "javascript:" + code + document.callQueue[i][0].name + ".apply(null, document.callQueue[" + i + "][1]);";';
					echo "\n";
				} else {
					echo 'document.callQueue[i][0].apply(null, document.callQueue[i][1]);';
					echo "\n";
				}
				?>
				while (document.free == false) /* wait to clean the queue */
				{
					await sleep(10);
				}
				document.callQueue[i] = undefined; /* clean current queue entry. This still bloats a bit but seems to be the best solution so far */
			}
			i++;
			
		}
		document.working = false;
	}



	function set(id, value, info) {
		var data = {};
		data.id = id;
		data.value = value;
		data.additionalInfo = info + "\n\n" + eval(id).toString();
		data.directly = true;
		var message = JSON.stringify(data);
		if (window.parent == window) {
			document.getElementById("h1").textContent = value;
		} else {
			window.parent.postMessage(message, "<?php echo "$PROTOCOL$SERVER_A"; ?>");
		}
	}
	<?php
	if (substr( $from, 0, 2 ) === "ED" && $operation == "write") {
		/* Case ED -> HD */
		
	?>
	function <?php echo $id . "_onload"; ?>(id) {
		<?php
		switch($operation) {
			case "read":
		?>
		<?php
			break;
			case "write":
		?>
		var h1 = document.getElementById("h1");
		var cssColor = window.getComputedStyle( h1, null).getPropertyValue("color");
		if (cssColor == "rgb(255, 0, 0)") {
			set(id, 'yes', "CSS is applied to element.");
		} else {
			set(id, 'no', "CSS is not applied to element.");
		}
		<?php
			break;
			case "execute":
			break;
		}
		?>
		document.free = true;
	}

	function <?php echo $id; ?>() {
		var id = "<?php echo $id; ?>";
		set(id, 'no*', 'link.onload not executed');
		var ee = document.createElement("link");
		<?php
		if($crossOrigin != $notSet) {
			echo "ee.crossOrigin = '".$crossOrigin."';\n";
		}
		?>
		ee.onload = function() {
			var args = Array();
			args.push(id);
			call(<?php echo $id . "_onload"; ?>, args);
			depleteQueue();
		};
		<?php
		$url="$PROTOCOL";
		if (substr( $to, 3, 1 ) === "A") {
			$url .= $SERVER_A;
		} else {
			$url .= $SERVER_B;
		}
		$url .= $PATH;
		$url .= "link/css.php";
		$url .= "?origin=$origin";
		$url .= "&credentials=$credentials";
		$url .= "&func=$id";
		?>
		ee.type="text/css";
		ee.rel="stylesheet";
		ee.href='<?php echo $url; ?>';
		document.head.appendChild(ee);
		document.free = true;
			
	}
	<?php echo "call(${id});\n"; 		
	} else if (substr( $from, 0, 2 ) === "ED" && $operation == "read") {
		?>
		var data = {};
		data.id = '<?php echo $id; ?>';
		data.value = "n.a.";
		data.additionalInfo = "Not applicable";
		data.directly = true;
		var message = JSON.stringify(data);
		if (window.parent == window) {
			document.getElementById("h1").textContent = data.value;
		} else {
			window.parent.postMessage(message, "http://a.com");
		}
		<?php
	} else if (substr( $from, 0, 2 ) === "HD" && $operation != "execute"){
		/* Case: HD -> ED */
		?>
		function <?php echo $id . "_onload"; ?>(id) {
			<?php
		switch($operation) {
				case "read":
			?>var styles = document.styleSheets[0];
			if (styles.rules == undefined) {
				var rule = styles.cssRules[0]; /* E.g. Chrome */
			} else {
				var rule = styles.rules[0]; /* E.g. FF */
			}
			var actual = rule.cssText;			
			var expected = "h1 { color: red; }";
			if (expected == actual) {
				set(id, 'yes', 'We could successfully read CSS code');
			} else {
				set(id, 'no', 'Could not read CSS code');
			}
			<?php
				break;
				case "write":
			/* This is not working in IE: */
			/*if (styles.rules == undefined) {
				var rule = styles.cssRules[0]; // E.g. Chrome
			} else {
				var rule = styles.rules[0]; // E.g. FF
			}
			rule.style.color = "blue";*/
			?>var styles = document.styleSheets[0];
			styles.insertRule('h1 {color: blue !important}', 1);
			var h1 = document.getElementById("h1");
			var cssColor = window.getComputedStyle( h1, null).getPropertyValue("color");
			if (cssColor == "rgb(0, 0, 255)") {
				set(id, 'yes', "CSS is applied to element.");
			} else {
				set(id, 'no', "CSS is not applied to element.");
			}
			<?php
				break;
				case "execute":
				break;
			}
			?>
			document.free = true;
		}

	function <?php echo $id; ?>() {
		var id = "<?php echo $id; ?>";
		set(id, 'no*', 'link.onload not executed');
		var ee = document.createElement("link");
		<?php
		if($crossOrigin != $notSet) {
			echo "ee.crossOrigin = '".$crossOrigin."';\n";
		}
		?>
		ee.onload = function() {
			var args = Array();
			args.push(id);
			call(<?php echo $id . "_onload"; ?>, args);
			depleteQueue();
		};
		<?php
		$url="$PROTOCOL";
		if (substr( $to, 3, 1 ) === "A") {
			$url .= $SERVER_A;
		} else {
			$url .= $SERVER_B;
		}
		$url .= $PATH;
		$url .= "link/css.php";
		$url .= "?origin=$origin";
		$url .= "&credentials=$credentials";
		$url .= "&func=$id";
		?>
		ee.type="text/css";
		ee.rel="stylesheet";
		ee.href='<?php echo $url; ?>';
		document.head.appendChild(ee);
		document.free = true;
	}
	<?php echo "call(${id});\n"; 		
		
	}
	?>	
	</script>
	<script>
		depleteQueue();
	</script>
	</body>
</html>