<?php
if (!isset($URL_A) || !isset($URL_B)) { die(); }
?>
	
<h2><button onclick="if (document.getElementById('ed_css_ee_link').style.display != 'none') { document.getElementById('ed_css_ee_link').style.display = 'none'; } else { document.getElementById('ed_css_ee_link').style.display = 'table'; }; ed_css_ee_link();">&#x21C5;</button> EE: &lt;link&gt;</h2>
<table class="bordered" id="ed_css_ee_link" style="display:none">
<tr>
    <th>FROM</th>
	<th>EE</th>
	<th>TO</th>
    <th>cross-origin</th>
	<th>Access-Control-Allow-Origin</th>
	<th>Use-Credentials</th>
	<th>r</th>
	<th>w</th>
	<th>x</th>
</tr>

<?php

$from="HD A";
$ee="LINK";
$notSet = "(not set)";
$anonymous = "anonymous";
$usecredentials = "use-credentials";

$arrayCrossOrigin = array($notSet, $anonymous, $usecredentials);
$arrayOrigin = array($notSet,"A","B","wildcard");
$arrayCredentials = array($notSet,"true","false");

$arrayToED = array("ED A", "ED B");
$arrayToHD = array("HD A");
foreach (array_merge($arrayToHD, $arrayToED) as $from) {
if (substr( $from, 0, 2 ) === "HD") { $arrayTo = $arrayToED; } else { $arrayTo = $arrayToHD; }
foreach($arrayTo as $to) {
	foreach($arrayCrossOrigin as $crossOrigin) {
		foreach($arrayOrigin as $origin) {
			foreach($arrayCredentials as $credentials) {
				
					$baseid = "test_${from}_${ee}_${to}_cross_origin_${crossOrigin}_origin_${origin}_credentials_${credentials}";
					$baseid = str_replace(array(" ", "-","(",")"), "_", $baseid);
					$idr = $baseid."_r";
					$idw = $baseid."_w";
					$idx = $baseid."_x";
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
					<td><?php /*if($credentials == 'true') { echo "&#10003;"; } elseif ($credentials == 'false') { echo "X"; } else { echo $credentials; } */  echo $credentials; ?></td>
					<td id="<?php echo $idr; ?>" style="cursor:help" title="No usefull testcase">no</td>
					<td id="<?php echo $idw; ?>" style="cursor:help" title="No usefull testcase">no</td>
					<td id="<?php echo $idx; ?>" style="cursor:help" title="Not applicable">n.a.</td>
					<script>
					<?php
					if (substr( $from, 0, 2 ) === "ED") {
						$wrappers = array($idw);
					} else {
						$wrappers = array($idr, $idw);
					}
				foreach($wrappers as $id) {
?>function <?php echo $id; ?>() {
	var wrapper = document.createElement("iframe");
	wrapper.width=0;
	wrapper.height=0;
	<?php
	$url="$PROTOCOL";
	$url .= $SERVER_A;
	$url .= $PATH;
	$url .= "link/wrapper.php";
	$url .= "?from=".urlencode($from);
	$url .= "&to=".urlencode($to);
	$url .= "&origin=$origin";
	$url .= "&credentials=$credentials";
	$url .= "&crossOrigin=$crossOrigin";
	switch($id) {
		case $idr:
				$url .= "&operation=read";
			break;
		case $idw:
				$url .= "&operation=write";
			break;
		case $idx:
				$url .= "&operation=execute";
			break;
	}
	$url .= "&func=$id";
	?>wrapper.src="<?php echo $url; ?>";
	document.getElementById("loadbar").appendChild(wrapper);
}
<?php  array_push($executions["ed_css_ee_link"], $id);  
	}
?>
					</script>
				</tr>
				<?php
			}
		}
	}
}
}
?>
</table>