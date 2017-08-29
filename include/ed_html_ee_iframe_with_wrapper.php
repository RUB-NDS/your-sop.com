<?php
if (!isset($URL_A) || !isset($URL_B)) { die(); }
?>

<h2>EE: &lt;iframe&gt; and Sandboxed &lt;iframe&gt;</h2>
	
<table class="bordered">
	<tr>
		<th>FROM</th>
		<th>EE</th>
		<th>TO</th>
		<th>sandbox attribute</th>
		<th>r</th>
		<th>w</th>
		<th>x</th>
	</tr>
<?php
$notSet = "(not set)";
$emptyValue = "(empty value)";
$sc = "allow-scripts";
$so = "allow-same-origin";
$tn = "allow-top-navigation";
$sandboxAttributes = [$notSet, $emptyValue, $sc, $so, $tn, "$sc $so", "$sc $tn", "$sc $so $tn"];
/*$sandboxAttributes = [$notSet]; */

foreach(["HD A"] as $from) {
	foreach(["ED A", "ED B"] as $to) {
		$ee = "iframe";
		foreach($sandboxAttributes as $sandbox) {
			$base = "test_${from}_${ee}_${to}_html_sandbox_${sandbox}";
			$base = str_replace([" ", "-",], "_", $base);
			$base = str_replace(["(", ")",], "", $base);
			$idr = "${base}_r";
			$idw = "${base}_w";
			$idx = "${base}_x";
?>
	<tr>
		<td><?php echo $from ?></td>
		<td><?php echo $ee ?></td>
		<td><?php echo $to ?></td>
		<td><?php echo $sandbox ?></td>
		<td id="<?php echo $idr ?>" style="cursor:help">no</td>
		<td id="<?php echo $idw ?>" style="cursor:help">no</td>
		<td id="<?php echo $idx ?>" style="cursor:help">no</td>
		<script><?php
				foreach( [$idr, $idw] as $id) {
				?>
		function <?php echo $id . "_onload"; ?>(ee, id) {
			try {
				var htmlDoc = ee.contentDocument;<?php
				switch($id) {
					case $idr:?>
						
				var htmlSource = htmlDoc.documentElement.innerHTML;
				/* check if html contains "ED: HTML" */
				set(id, (htmlSource.indexOf("ED: HTML") > 0)?'yes(DOM)':'no');
						<?php
						break;
					case $idw;?>
						
				htmlDoc.body.innerHTML="new content";
				var htmlSource = htmlDoc.documentElement.innerHTML;
				/* check if element could be removed */
				set(id, (htmlSource.indexOf("new content") > 0)?'yes(DOM)':'no');
						
						<?php
						break;
				}
				?>
				
			} catch (ex) {			
				set(id, 'no*', ex.message); /* SOP violation? */
			}
			<?php
			$url="$PROTOCOL";
			if ($to === "ED A") {
				$url .= $SERVER_A;
			} else {
				$url .= $SERVER_B;
			}
			$url .= $PATH;
			$url .= "html/html.php";
			$url .= "?func=$id";
			$url .= "&exec=".urlencode($_GET["exec"]);
			?>
			document.free = true;
		}

		function <?php echo $id; ?>() {
			var id = getFunctionName();
			set(id, 'no*', '<?php echo $ee ?>.onload not executed)');
			var ee = document.createElement("<?php echo $ee ?>");
			ee.widht=0;
			ee.height=0;
			ee.onload = function() {
				var args = Array();
				args.push(ee);
				args.push(id);
				call(<?php echo $id . "_onload"; ?>, args);
			};
			<?php
				if ($sandbox != $notSet) {
			        echo "ee.sandbox='";
					if ($sandbox != $emptyValue) {
						echo $sandbox;
					}
					echo "';";
				}
			?>ee.src='<?php echo $url ?>';
			document.getElementById("loadbar").appendChild(ee); /* load the content */
			document.free = true;
		}
		<?php array_push($executions["ed_html_ee_iframe_with_wrapper"], $id); 
		}
		?>
		
		function <?php echo $idx ?>() {
			var id = getFunctionName();
			set(id, '-', 'No useful testcase');
		}
		<?php echo $idx."();\n"; ?>
		</script>
	</tr><?php
		}
	}
}
foreach(["ED A", "ED B"] as $from) {
	foreach(["HD A"] as $to) {
		$ee = "iframe";
		foreach($sandboxAttributes as $sandbox) {
			$base = "test_${from}_${ee}_${to}_html_sandbox_${sandbox}";
			$base = str_replace([" ", "-",], "_", $base);
			$base = str_replace(["(", ")",], "", $base);
			$idr = "${base}_r";
			$idw = "${base}_w";
			$idx = "${base}_x";
?>
	<tr>
		<td><?php echo $from ?></td>
		<td><?php echo $ee ?></td>
		<td><?php echo $to ?></td>
		<td><?php echo $sandbox ?></td>
		<td id="<?php echo $idr ?>" style="cursor:help">no</td>
		<td id="<?php echo $idw ?>" style="cursor:help">no</td>
		<td id="<?php echo $idx ?>" style="cursor:help">no</td>
		<script><?php
				foreach( [$idr, $idx] as $id) {
				?>
		function <?php echo $id ?>() {
			var id = getFunctionName();
			set(id, 'no');
			var ee = document.createElement("<?php echo $ee ?>");
			ee.width=0px;
			ee.height=0px;
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
			$url .= "html/html_script.php?operation=";
			switch ($id) {
				case $idr:
					$url .= "read";
					break;
				default:
					$url .="execute";
					break;
			}
			$url .= "&func=$id";
			$url .= "&exec=".urlencode($_GET["exec"]);
			echo $url ?>';
			document.getElementById("loadbar").appendChild(ee); /* load the content */
			document.free = true;
		}
		<?php array_push($executions["ed_html_ee_iframe_with_wrapper"], $id); 
			}
		$id = $idw;
		?>function <?php echo $id ?>() {
			var id = getFunctionName();
			set(id, 'no');
			var ee = document.createElement("<?php echo $ee ?>");
			ee.width=0;
			ee.height=0;
			ee.src='<?php 
			$url= $PROTOCOL.$SERVER_A.$PATH."html/wrapper.php";
			$url .= "?from=".urlencode($from);
			$url .= "&ee=".urlencode($ee);
			$url .= "&to=".urlencode($to);
			$url .= "&sandbox=".urlencode($sandbox);
			$url .= "&exec=".urlencode($_GET["exec"]);			
			echo $url ?>';
			document.getElementById("loadbar").appendChild(ee);
			document.free = true;
		}
		<?php array_push($executions["ed_html_ee_iframe_with_wrapper"], $id); ?> 
		</script>
		<?php
		}
	}
}
?>

</table>