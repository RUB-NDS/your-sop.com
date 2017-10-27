<?php
if (!isset($URL_A) || !isset($URL_B)) { die(); }
?>
	
<h2><button onclick="if (document.getElementById('ed_mp4_ogg_ee_canvas').style.display != 'none') { document.getElementById('ed_mp4_ogg_ee_canvas').style.display = 'none'; } else { document.getElementById('ed_mp4_ogg_ee_canvas').style.display = 'table'; }; ed_mp4_ogg_ee_canvas();">&#x21C5;</button> EE: &lt;canvas&gt;</h2>
<table class="bordered" id="ed_mp4_ogg_ee_canvas" style="display:none">
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
					<td>&lt;<?php echo $ee; ?>&gt;</td>
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
					$id = "test_${from}_${ee}_with_mp4ogg_${to}_r_cross_origin_${crossOrigin}_origin_${origin}_credentials_${credentials}";
					$id = str_replace(array(" ", "-","(",")"), "_", $id);
					echo $id;
					?>" style="cursor:help">no</td>
					<script>
function <?php echo $id . "_onload"; ?>(video, id) {
	try {
		var c = document.createElement("canvas");
		c.width=video.videoWidth; c.height=video.videoHeight;
		var ctx = c.getContext("2d");
		ctx.drawImage(video, 0, 0); 
		var pixel = ctx.getImageData(2,3,1,1);
		var data = pixel.data;
		set(id, (data[0] == 253 || data[0] == 254 || data[0] == 255)?'yes':'no'); /* different Browsers = different pixel values WTF */
	} catch (ex) {			
		set(id, 'no*', ex.message); /* SOP violation? */
	}
	document.getElementById("loadbar").removeChild(video);
	document.free = true;
}

function <?php echo $id; ?>() {
	var id = getFunctionName();
	set(id, 'no*', "video.onloadeddata not executed"); /* fallback if img.onload is not executed */
	var video = document.createElement("video");
	<?php
	if($crossOrigin != $notSet) { 
		echo "video.crossOrigin = '".$crossOrigin."';\n";	} 
	?>
	video.onloadeddata = function() {
		args = Array();
		args.push(video);
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
	$param = "?origin=$origin";
	$param .= "&credentials=$credentials";
	$param .= "&func=$id";
	$param .= "&exec=".urlencode($_GET["exec"]);
	$param .= "&to=".urlencode($to);
	?>

	var source2 = document.createElement('source');
	source2.src = '<?php echo $url; ?>video/ogg.php<?php echo $param; ?>';
	source2.type = 'video/ogg';
	video.appendChild(source2);

	var source = document.createElement('source');
	source.src = '<?php echo $url; ?>video/mp4.php<?php echo $param; ?>';
	source.type = 'video/mp4';
	video.appendChild(source);
	
    video.controls = "true";
	video.style.display = "none";

	document.getElementById("loadbar").appendChild(video);
	document.free = true;
		
}
<?php  array_push($executions["ed_mp4_ogg_ee_canvas"], $id);  ?>
					</script>
				</tr>
				<?php
			}
		}
	}
}
?>
</table>