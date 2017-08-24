<?php
if (!isset($URL_A) || !isset($URL_B)) { die(); }
?>

<h2><button onclick="if (document.getElementById('ed_js_ee_script').style.display != 'none') { document.getElementById('ed_js_ee_script').style.display = 'none'; } else { document.getElementById('ed_js_ee_script').style.display = 'table'; }; ed_js_ee_script();">&#x21C5;</button> EE: &lt;script&gt;</h2>
	
<table class="bordered" id="ed_js_ee_script" style="display:none">
	<tr>
		<th>FROM</th>
		<th>EE</th>
		<th>TO</th>
		<th>r</th>
		<th>w</th>
		<th>x</th>
	</tr>
<?php
foreach(array("HD A") as $from) {
	foreach(array("ED A", "ED B") as $to) {
		    $ee  = "script";
			$base = "test_${from}_${ee}_${to}";
			$base = str_replace(array(" ", "-"), "_", $base);
			$idr = "${base}_r";
			$idw = "${base}_w";
			$idx = "${base}_x";
?>
	<tr>
		<td><?php if ($from == 'HD A') { echo '<span style="text-decoration:overline">HD</span>'; } elseif ($from == 'ED A') { echo '<span style="text-decoration:overline">ED</span>'; } elseif ($from == 'HD B') { echo '<span style="text-decoration:underline">HD</span>'; } elseif ($from == 'ED B') { echo '<span style="text-decoration:underline">ED</span>'; } else { echo '?'; } ?></td>
		<td>&lt;<?php echo $ee ?>&gt;</td>
		<td><?php if ($to == 'HD A') { echo '<span style="text-decoration:overline">HD</span>'; } elseif ($to == 'ED A') { echo '<span style="text-decoration:overline">ED</span>'; } elseif ($to == 'HD B') { echo '<span style="text-decoration:underline">HD</span>'; } elseif ($to == 'ED B') { echo '<span style="text-decoration:underline">ED</span>'; } else { echo '?'; } ?></td>
		<td id="<?php echo $idr ?>" style="cursor:help">no</td>
		<td id="<?php echo $idw ?>" style="cursor:help">no</td>
		<td id="<?php echo $idx ?>" style="cursor:help">no</td>
		<script><?php
		foreach(array($idr, $idw, $idx) as $id) {
			$jsfunc = "func".rand(100000,999999);
				?>
		function <?php echo $id . "_onload" ?>(ee, id) {
			<?php
			switch ($id) {
				case $idr:
			?>var source = <?php echo $jsfunc ?>.toString();
			if(source.indexOf("secret") > 0) {
				set(id, 'partial', 'Function source code of loaded script:\n' + source);
			} else {
				set(id, 'no', 'Could not find "secret":\n'+source);
			}
					<?php
					break;
				case $idw:
			?>var oldSecret = <?php echo $jsfunc ?>();
			<?php echo $jsfunc ?> = function() { return 1; };				
			var newSecret = <?php echo $jsfunc ?>();
			if (oldSecret == 42 && newSecret == 1)  {
				set(id, 'partial');
			}
		    <?php
					break;
				case $idx:
					break;
			}			
			?>document.body.removeChild(ee);
			document.free = true;
		};

		function <?php echo $id ?>() {
			var id = getFunctionName();
			set(id, 'no');
			var ee = document.createElement("<?php echo $ee ?>");
			ee.onload = function() {
				var args = Array();
				args.push(ee);
				args.push(id);
				call(<?php echo $id . "_onload" ?>, args);
			};						
			ee.src='<?php 
			$url="$PROTOCOL";
			if ($from === "ED A") {
				$url .= $SERVER_A;
			} else {
				$url .= $SERVER_B;
			}
			$url .= $PATH;
			
			$url .= "js/js";
			if ($id == $idx) { 
				$url .= "_script";
			}
			$url .= ".php?operation=$jsfunc";
			$url .= "&func=$id";
			echo $url ?>';
			document.body.appendChild(ee); /* load the content */
			document.free = true;
		}
		<?php  array_push($executions["ed_js_ee_script"], $id); 
		}
		?>
		</script>
		<?php
	}
}
foreach(array("ED A", "ED B") as $from) {
	foreach(array("HD A") as $to) {
		    $ee  = "script";
			$base = "test_${from}_${ee}_${to}";
			$base = str_replace(array(" ", "-"), "_", $base);
			$idr = "${base}_r";
			$idw = "${base}_w";
			$idx = "${base}_x";
?>
	<tr>
		<td><?php if ($from == 'HD A') { echo '<span style="text-decoration:overline">HD</span>'; } elseif ($from == 'ED A') { echo '<span style="text-decoration:overline">ED</span>'; } elseif ($from == 'HD B') { echo '<span style="text-decoration:underline">HD</span>'; } elseif ($from == 'ED B') { echo '<span style="text-decoration:underline">ED</span>'; } ?></td>
		<td>&lt;<?php echo $ee ?>&gt;</td>
		<td><?php if ($to == 'HD A') { echo '<span style="text-decoration:overline">HD</span>'; } elseif ($to == 'ED A') { echo '<span style="text-decoration:overline">ED</span>'; } elseif ($to == 'HD B') { echo '<span style="text-decoration:underline">HD</span>'; } elseif ($to == 'ED B') { echo '<span style="text-decoration:underline">ED</span>'; } else { echo '?'; } ?></td>
		<td id="<?php echo $idr ?>" style="cursor:help">no</td>
		<td id="<?php echo $idw ?>" style="cursor:help">no</td>
		<td id="<?php echo $idx ?>" style="cursor:help" title="Not applicable">n.a.</td>
		<script><?php
		foreach(array($idr, $idw) as $id) {
				?>
		function <?php echo $id . "_onload"; ?>(ee) {
			document.body.removeChild(ee);
			document.free = true;
		} 

		function <?php echo $id ?>() {
			var id = getFunctionName();
			set(id, 'no');
			var ee = document.createElement("<?php echo $ee ?>");
			ee.onload = function() {
				var args = Array();
				args.push(ee);
				call(<?php echo $id . "_onload"; ?>, args);
			};						
			ee.src='<?php 
			$url="$PROTOCOL";
			if ($from === "ED A") {
				$url .= $SERVER_A;
			} else {
				$url .= $SERVER_B;
			}
			$url .= $PATH;
			$url .= "js/js_script.php?operation=";
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
			echo $url ?>';
			document.body.appendChild(ee); /* load the content */
			document.free = true;
		}
		<?php  array_push($executions["ed_js_ee_script"], $id); 
		}
		?>
		</script>
		<?php
	}
}
?>

</table>