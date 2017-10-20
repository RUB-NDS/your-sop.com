<?php
if (!isset($URL_A) || !isset($URL_B)) { die(); }
?>

<h2><button onclick="if (document.getElementById('ed_svg_ee_iframe_object_embed').style.display != 'none') { document.getElementById('ed_svg_ee_iframe_object_embed').style.display = 'none'; } else { document.getElementById('ed_svg_ee_iframe_object_embed').style.display = 'table'; }; ed_svg_ee_iframe_object_embed();">&#x21C5;</button> EE: &lt;iframe&gt; &lt;object&gt; and &lt;embed&gt;</h2>
	
<table class="bordered" id="ed_svg_ee_iframe_object_embed" style="display:none">
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
		foreach(array("iframe", "object", "embed") as $ee) {
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
		<td id="<?php echo $idx ?>" style="cursor:help">no</td>
		<script><?php
				foreach(array($idr, $idw) as $id) {
				?>
		function <?php echo $id . "_onload";?>(ee, id) {
			try {
					var svgDoc = ee.getSVGDocument();<?php
					switch($id) {
						case $idr:?>
							
					var firstChildName = svgDoc.documentElement.firstElementChild.nodeName;
					/* check if svg first child name is "rect" */
					set(id, (firstChildName==="rect")?'yes(DOM)':'no');
							<?php
							break;
						case $idw;?>
							
					var firstChild = svgDoc.documentElement.firstElementChild;
					svgDoc.documentElement.removeChild(firstChild);
					var firstChild = svgDoc.documentElement.firstElementChild;
					/* check if element could be removed */
					set(id, (firstChild==null)?'yes(DOM)':'no');
							
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
			$url .= "img/svg.php";
			$url .= "?func=$id";
			$url .= "&to=".urlencode($to);
			$url .= "&exec=".urlencode($_GET["exec"]);
			$src = ($ee==="object")?"data":"src";
			?>
			document.free = true;
		}

		function <?php echo $id ?>() {
			var id = getFunctionName();
			set(id, 'no*', '<?php echo $ee ?>.onload not executed)');
			var ee = document.createElement("<?php echo $ee ?>");
			ee.width=0;
			ee.height=0;
			ee.onload = function() {
				var args = Array();
				args.push(ee);
				args.push(id);
				call(<?php echo $id . "_onload";?>, args);
				depleteQueue();
			};				
			ee.<?php echo $src ?>='<?php echo $url ?>';
			document.getElementById("loadbar").appendChild(ee); /* load the content */
			document.free = true;
		}
		<?php array_push($executions["ed_svg_ee_iframe_object_embed"], $id); 
		}
		?>
		
		function <?php echo $idx ?>() {
			var id = getFunctionName();
			set(id, 'no');
			var ee = document.createElement("<?php echo $ee ?>");	
			ee.width=0;
			ee.height=0;
			ee.<?php echo $src ?>='<?php 
			$url="$PROTOCOL";
			if ($to === "ED A") {
				$url .= $SERVER_A;
			} else {
				$url .= $SERVER_B;
			}
			$url .= $PATH;
			$url .= "img/svg_script.php"; /* if executed, a postMessage will be sent */
			$url .= "?func=$idx";
			$url .= "&to=".urlencode($to);
			$url .= "&exec=".urlencode($_GET["exec"]);
			$src = ($ee==="object")?"data":"src";	
			echo $url ?>';
			document.getElementById("loadbar").appendChild(ee); /* load the content */
			document.free = true;
		}
		<?php  array_push($executions["ed_svg_ee_iframe_object_embed"], $idx);  ?>
		</script>
	</tr><?php
		}
	}
}
foreach(array("ED A", "ED B") as $from) {
	foreach(array("HD A") as $to) {
		foreach(array("iframe", "object", "embed") as $ee) {
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
		<td id="<?php echo $idx ?>" style="cursor:help" title="Not applicable">n.a.</td>
		<script><?php
				foreach(array($idr, $idw) as $id) {
				?>
		function <?php echo $id ?>() {
			var id = getFunctionName();
			set(id, 'no');
			var ee = document.createElement("<?php echo $ee ?>");
			ee.widht=0;
			ee.height=0;
			ee.<?php 
			$url="$PROTOCOL";
			if ($from === "ED A") {
				$url .= $SERVER_A;
			} else {
				$url .= $SERVER_B;
			}
			$url .= $PATH;
			$url .= "img/svg_script.php?operation=";
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
			$src = ($ee==="object")?"data":"src";
			echo $src ?>='<?php echo $url ?>';
			document.getElementById("loadbar").appendChild(ee); /* load the content */
			document.free = true;
		}
		<?php  array_push($executions["ed_svg_ee_iframe_object_embed"], $id);  
		}
		?>
		</script>
		<?php
		}
	}
}
?>

</table>