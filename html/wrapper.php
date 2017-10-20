<?php
include(__DIR__ . "/../config.php");
header("Cache-Control: no-cache, must-revalidate"); /* HTTP/1.1 */
header("Expires: -1"); /* Always invalid */

include(__DIR__ . "/../util/cors.php");
include(__DIR__ . "/../util/suborigin-from.php");
header("Content-type: text/html");

if (!isset($_GET['from'])) die();
if (!isset($_GET['ee'])) die();
if (!isset($_GET['to'])) die();
if (!isset($_GET['sandbox'])) die();

$from    = $_GET['from'];
$ee      = $_GET['ee'];
$to      = $_GET['to'];
$sandbox = $_GET['sandbox'];

$id = "test_${from}_${ee}_${to}_html_sandbox_${sandbox}_w";
$id = str_replace([" ", "-",], "_", $id);
$id = str_replace(["(", ")",], "", $id);

$notSet = "(not set)";
$emptyValue = "(empty value)";
$sc = "allow-scripts";
$so = "allow-same-origin";
$tn = "allow-top-navigation";
$sandboxAttributes = [$notSet, $emptyValue, $sc, $so, $tn, "$sc $so", "$sc $tn", "$sc $so $tn"];

?>
<html>
	<head><title>ED: HTML</title></head>
	<body>
	<h1 id="h1">ED: HTML Wrapper Page</h1>
		<script>

		<?php 
		include(__DIR__ . "/../util/call_stack.php");
		?>

		function <?php echo $id ?>() {
			var id = '<?php echo $id ?>';
			var ee = document.createElement("<?php echo $ee ?>");
			ee.onload = function() {
				document.body.removeChild(ee);
			};
			<?php
				if ($sandbox != $notSet) {
			        echo "ee.sandbox='";
					if ($sandbox != $emptyValue) {
						echo $sandbox;
					}
					echo "';\n";
				}
			?>ee.src='<?php 
			$url="$PROTOCOL";
			if ($from === "ED A") {
				$url .= $SERVER_A;
			} else {
				$url .= $SERVER_B;
			}
			$url .= $PATH;
			$url .= "html/html_script.php?operation=write";
			$url .= "&func=$id";
			$url .= "&exec=".urlencode($_GET["exec"]);
			echo $url ?>';
			document.body.appendChild(ee); /* load the content */
			document.free = true;
		}
		<?php
	if (!isset($_GET['newlocation'])) {
	echo "call($id);\n";
	}
	else {
	?>		
			var data = {};
			data.id = '<?php echo $id ?>';
			data.value = 'partial (from wrapper)';
			var message = JSON.stringify(data);
			window.parent.postMessage(message, "<?php echo "$PROTOCOL$SERVER_A"; ?>");
	<?php
	}
	?>
	depleteQueue();
	</script>
	</body>
</html>