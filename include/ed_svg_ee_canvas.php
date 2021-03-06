<?php
if (!isset($URL_A) || !isset($URL_B)) { die(); }
?>

<h2><button onclick="if (document.getElementById('ed_svg_ee_canvas').style.display != 'none') { document.getElementById('ed_svg_ee_canvas').style.display = 'none'; } else { document.getElementById('ed_svg_ee_canvas').style.display = 'table'; }; ed_svg_ee_canvas();">&#x21C5;</button> EE: &lt;img&gt; and &lt;canvas&gt;</h2>

<table class="bordered" id="ed_svg_ee_canvas" style="display:none">
<tr>
    <th>FROM</th>
	<th>EE</th>
	<th>TO</th>
    <th>cross-origin</th>
	<th>Access-Control-Allow-Origin</th>
	<th>Use-Credentials</th>
	<th>r</th>
</tr>

<?php

$from="HD A";
$ee="CANVAS";
$notSet = "(not set)";

foreach(array("ED A", "ED B") as $to) {
	foreach(array($notSet, "anonymous", "use-credentials") as $crossOrigin) {
		foreach(array($notSet,"A","B","wildcard") as $origin) {
			foreach(array($notSet,"true","false") as $credentials) {
				?>
				<tr>	
					<td><?php if ($from == 'HD A') { echo '<span style="text-decoration:overline">HD</span>'; } elseif ($from == 'ED A') { echo '<span style="text-decoration:overline">ED</span>'; } elseif ($from == 'HD B') { echo '<span style="text-decoration:underline">HD</span>'; } elseif ($from == 'ED B') { echo '<span style="text-decoration:underline">ED</span>'; } else { echo '?'; } ?></td>
					<td>&lt;<?php echo $ee ?>&gt;</td>
					<td><?php if ($to == 'HD A') { echo '<span style="text-decoration:overline">HD</span>'; } elseif ($to == 'ED A') { echo '<span style="text-decoration:overline">ED</span>'; } elseif ($to == 'HD B') { echo '<span style="text-decoration:underline">HD</span>'; } elseif ($to == 'ED B') { echo '<span style="text-decoration:underline">ED</span>'; } else { echo '?'; } ?></td>
					<td><?php echo $crossOrigin; ?></td>
					<td><?php 
					switch ($origin) {
						case 'A':
							echo $SERVER_A;
							break;
						case 'B':
							echo $SERVER_B;
							break;
						default:
							echo $origin;
							break;
					} 
					?></td>
					<td><?php echo $credentials; ?></td>
					<td id="<?php 
					$id = "test_${from}_${ee}_with_svg_${to}_r_cross_origin_${crossOrigin}_origin_${origin}_credentials_${credentials}";
					$id = str_replace(array(" ", "-",")","("), "_", $id);
					echo $id;
					?>" onclick="manualExecution(<?php echo $id; ?>)" style="cursor:help">no</td>
					<script>
function <?php echo $id . "_onload"; ?>(img, id) {
	try {
		var c = document.createElement("canvas");
		c.width=img.width; c.height=img.width;
		var ctx = c.getContext("2d");
		ctx.drawImage(img, 0, 0); /* Put img on canvas at pos 0x0 */
		var pixel = ctx.getImageData(50,50,1,1); /* Read color of 1x1 pixel at position 50/50 */
		var data = pixel.data;
		set(id, (data[0]==255)?'yes':'no');
	} catch (ex) {			
		set(id, 'no*', ex.message); /* SOP violation? */
	}
	document.free = true;
}

function <?php echo $id; ?>() {
	var id = getFunctionName();
	set(id, 'no*', 'img.onload not executed'); /* fallback if img.onload is not executed */
	var img = document.createElement("img");
	<?php
	if($crossOrigin != $notSet) {
		echo "img.crossOrigin = '".$crossOrigin."';\n";
	}
	?>
img.onload = function() {
	var args = Array();
	args.push(img);
	args.push(id);
	call(<?php echo $id . "_onload"; ?>, args);
	depleteQueue();
};
	<?php
	$url="$PROTOCOL";
	if ($to === "ED A") {
		$url .= $SERVER_A;
	} else {
		$url .= $SERVER_B;
	}
	$url .= $PATH;
	$url .= "img/svg.php";
	$url .= "?origin=$origin";
	$url .= "&to=".urlencode($to);
	$url .= "&credentials=$credentials";
	$url .= "&func=$id";
	$url .= "&exec=".urlencode($_GET["exec"]);
	echo "img.src='${url}';";
	echo "document.free = true;";
	?>
		
}
<?php  array_push($executions["ed_svg_ee_canvas"], $id);  ?>
					</script>
				</tr>
				<?php
			}
		}
	}
}
?>
</table>