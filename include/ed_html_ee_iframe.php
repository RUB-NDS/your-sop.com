<?php
if (!isset($URL_A) || !isset($URL_B)) { die(); }
?>

<h2><button onclick="if (document.getElementById('ed_html_ee_iframe').style.display != 'none') { document.getElementById('ed_html_ee_iframe').style.display = 'none'; } else { document.getElementById('ed_html_ee_iframe').style.display = 'table'; }; ed_html_ee_iframe();">&#x21C5;</button> EE: &lt;iframe&gt; and Sandboxed &lt;iframe&gt;</h2>
	
<table class="bordered" id="ed_html_ee_iframe" style="display:none">
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
$sandboxAttributes = array($notSet, $emptyValue, $sc, $so, $tn, "$sc $so", "$sc $tn", "$so $tn", "$sc $so $tn");
/*$sandboxAttributes = [$notSet]; */

foreach(array("HD A") as $from) {
	foreach(array("ED A", "ED B") as $to) {
		$ee = "iframe";
		foreach($sandboxAttributes as $sandbox) {
			$base = "test_${from}_${ee}_${to}_html_sandbox_${sandbox}";
			$base = str_replace(array(" ", "-",), "_", $base);
			$base = str_replace(array("(", ")",), "", $base);
			$idr = "${base}_r";
			$idw = "${base}_w";
			$idx = "${base}_x";
?>
	<tr>
		<td><?php if ($from == 'HD A') { echo '<span style="text-decoration:overline">HD</span>'; } elseif ($from == 'ED A') { echo '<span style="text-decoration:overline">ED</span>'; } elseif ($from == 'HD B') { echo '<span style="text-decoration:underline">HD</span>'; } elseif ($from == 'ED B') { echo '<span style="text-decoration:underline">ED</span>'; } else { echo '?'; } ?></td>
		<td>&lt;<?php echo $ee ?>&gt;</td>
		<td><?php if ($to == 'HD A') { echo '<span style="text-decoration:overline">HD</span>'; } elseif ($to == 'ED A') { echo '<span style="text-decoration:overline">ED</span>'; } elseif ($to == 'HD B') { echo '<span style="text-decoration:underline">HD</span>'; } elseif ($to == 'ED B') { echo '<span style="text-decoration:underline">ED</span>'; } else { echo '?'; } ?></td>
		<td><?php echo $sandbox ?></td>
		<td id="<?php echo $idr ?>" onclick="manualExecution(<?php echo $idr; ?>)" style="cursor:help">no</td>
		<td id="<?php echo $idw ?>" onclick="manualExecution(<?php echo $idw; ?>)" style="cursor:help">no</td>
		<td id="<?php echo $idx ?>" onclick="manualExecution(<?php echo $idx; ?>)" style="cursor:help">no</td>
		<script><?php
				foreach(array($idr, $idw, $idx) as $id) {
				?>
		function <?php echo $id . "_onload" ?>(ee, id) {
				<?php
				if ($id == $idx) {
					?>document.getElementById("loadbar").removeChild(ee);<?php
				}
				else {
				?>try {
					var htmlDoc = ee.contentDocument;<?php
					switch($id) {
						case $idr:?>
							
					set(id, (ee.contentWindow.frames.length == 0)?'partial':'no');
					var htmlSource = htmlDoc.documentElement.innerHTML;
					/* check if html contains "ED: HTML" */
					set(id, (htmlSource.indexOf("ED: HTML") > 0)?'yes':'no');
							<?php
							break;
						case $idw;?>
							
					htmlDoc.body.innerHTML="new content";
					var htmlSource = htmlDoc.documentElement.innerHTML;
					/* check if element could be removed */
					set(id, (htmlSource.indexOf("new content") > 0)?'yes':'no');
							
							<?php
							break;
					}
					?>
					
				} catch (ex) {			
					set(id, 'no*', ex.message); /* SOP violation? */
				}
				<?php
				}
				$url="$PROTOCOL";
				if ($to === "ED A") {
					$url .= $SERVER_A;
				} else {
					$url .= $SERVER_B;
				}
				$url .= $PATH;
				$url .= "html/html";
				if ($id == $idx) { $url .= "_script"; }
				$url .=".php";
				$url .= "?func=$id";
				$url .= "&exec=".urlencode($_GET["exec"]);
				$url .= "&to=".urlencode($to);
				?>
				document.free = true;
			}

		function <?php echo $id ?>() {
			var id = getFunctionName();
			set(id, 'no', '<?php 
			if ($id == $idx) {
				echo "Script execution blocked";
			} else {
				echo $ee . "onload not executed";
			}?>');
			var ee = document.createElement("<?php echo $ee ?>");
			ee.width = 0;
			ee.height =0;
			ee.onload = function() {
				var args = Array();
				args.push(ee);
				args.push(id);
				call(<?php echo $id . "_onload" ?>, args);
				depleteQueue();
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
		<?php  array_push($executions["ed_html_ee_iframe"], $id); 
		}
		?>
		</script>
	</tr><?php
		}
	}
}
foreach(array("ED A", "ED B") as $from) {
	foreach(array("HD A") as $to) {
		$ee = "iframe";
		foreach($sandboxAttributes as $sandbox) {
			$base = "test_${from}_${ee}_${to}_html_sandbox_${sandbox}";
			$base = str_replace(array(" ", "-",), "_", $base);
			$base = str_replace(array("(", ")",), "", $base);
			$idr = "${base}_r";
			$idw = "${base}_w";
			$idx = "${base}_x";
?>
	<tr>
		<td><?php if ($from == 'HD A') { echo '<span style="text-decoration:overline">HD</span>'; } elseif ($from == 'ED A') { echo '<span style="text-decoration:overline">ED</span>'; } elseif ($from == 'HD B') { echo '<span style="text-decoration:underline">HD</span>'; } elseif ($from == 'ED B') { echo '<span style="text-decoration:underline">ED</span>'; } else { echo '?'; } ?></td>
		<td>&lt;<?php echo $ee ?>&gt;</td>
		<td><?php if ($to == 'HD A') { echo '<span style="text-decoration:overline">HD</span>'; } elseif ($to == 'ED A') { echo '<span style="text-decoration:overline">ED</span>'; } elseif ($to == 'HD B') { echo '<span style="text-decoration:underline">HD</span>'; } elseif ($to == 'ED B') { echo '<span style="text-decoration:underline">ED</span>'; } else { echo '?'; } ?></td>
		<td><?php echo $sandbox ?></td>
		<td id="<?php echo $idr ?>" onclick="manualExecution(<?php echo $idr; ?>)" style="cursor:help">no</td>
		<td id="<?php echo $idw ?>" onclick="manualExecution(<?php echo $idw; ?>)" style="cursor:help">no</td>
		<td id="<?php echo $idx ?>" onclick="manualExecution(<?php echo $idx; ?>)" style="cursor:help" title="Not applicable">n.a.</td>
		<script><?php
				foreach(array($idr, $idw) as $id) {
				?>
		function <?php echo $id ?>() {
			var id = getFunctionName();
			set(id, 'no');
			var ee = document.createElement("<?php echo $ee ?>");
			ee.width=0;
			ee.height=0;
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
				case $idw:
					$url .= "write";
					break;
				default:
					$url .="execute";
					break;
			}
			$url .= "&func=$id";
			$url .= "&to=".urlencode($from);
			$url .= "&exec=".urlencode($_GET["exec"]);
			echo $url ?>';
			document.getElementById("loadbar").appendChild(ee); /* load the content */
			document.free = true;
		}
		<?php  array_push($executions["ed_html_ee_iframe"], $id); 
		}
		?>
		</script>
		<?php
		}
	}
}
?>

</table>
