<?php
if (!isset($URL_A) || !isset($URL_B)) { die(); }
?>

<h2><h2><button onclick="if (document.getElementById('ed_mp4_ogg_ee_video').style.display != 'none') { document.getElementById('ed_mp4_ogg_ee_video').style.display = 'none'; } else { document.getElementById('ed_mp4_ogg_ee_video').style.display = 'table'; }; ed_mp4_ogg_ee_video();">&#x21C5;</button> EE: &lt;video&gt;</h2></h2>

<table class="bordered" id="ed_mp4_ogg_ee_video" style="display:none">
<tr>
    <th>FROM</th>
    <th>EE</th>
    <th>TO</th>
    <th>r</th>
    <th>w</th>
    <th>x</th>
</tr>
<tr>
    <td style="text-decoration:overline">HD</td>
    <td>&lt;video&gt;</td>
    <td style="text-decoration:overline">ED</td>
    <td id="test_HD_A_VIDEO_ED_A_r" style="cursor:help">no</td>
    <td id="test_HD_A_VIDEO_ED_A_w" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_HD_A_VIDEO_ED_A_x" style="cursor:help" title="Not applicable">n.a.</td>
</tr>
<tr>
    <td style="text-decoration:overline">HD</td>
    <td>&lt;video&gt;</td>
    <td style="text-decoration:underline">ED</td>
    <td id="test_HD_A_VIDEO_ED_B_r" style="cursor:help">no</td>
    <td id="test_HD_A_VIDEO_ED_B_w" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_HD_A_VIDEO_ED_B_x" style="cursor:help" title="Not applicable">n.a.</td>
</tr>
<tr>
    <td style="text-decoration:overline">ED</td>
    <td>&lt;video&gt;</td>
    <td style="text-decoration:overline">HD</td>
    <td id="test_ED_A_VIDEO_HD_A_r" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_ED_A_VIDEO_HD_A_w" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_ED_A_VIDEO_HD_A_x" style="cursor:help" title="Not applicable">n.a.</td>
</tr>
<tr>
    <td style="text-decoration:overline">ED</td>
    <td>&lt;video&gt;</td>
    <td style="text-decoration:underline">HD</td>
    <td id="test_ED_A_VIDEO_HD_B_r" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_ED_A_VIDEO_HD_B_w" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_ED_A_VIDEO_HD_B_x" style="cursor:help" title="Not applicable">n.a.</td>
</tr>
</table>

<script>
<?php
foreach(array(array("A", "$URL_A"), array("B", "$URL_B")) as $case){
	$name = $case[0];
	$url  = $case[1];
	$id = "test_HD_A_VIDEO_ED_".$name."_r";
?>
function <?php echo $id . "_onloadeddata"; ?>(video, id) {
    set(id, (video.videoWidth == 320) ? 'partial' : 'no');
    document.getElementById("loadbar").removeChild(video);
    document.free = true;
}

function <?php echo $id; ?>(){
	var video = document.createElement('video');
	var id = getFunctionName();
	video.onloadeddata = function(){ 
		var args = Array();
        args.push(video);
        args.push(id);
        call(<?php echo $id . "_onloadeddata"; ?>, args);
	};
	var source = document.createElement('source');
	source.src = '<?php echo $url; ?>video/mp4.php?func=<?php echo $id . "&exec=" . urlencode($_GET["exec"]);?>';
	source.type = 'video/mp4';
	video.appendChild(source);
	
	source.src = '<?php echo $url; ?>video/ogg.php?func=<?php echo $id . "&exec=" . urlencode($_GET["exec"]);?>';
	source.type = 'video/ogg';
	video.appendChild(source);

	video.style.display = "none";

	document.getElementById("loadbar").appendChild(video);
    document.free = true;
} 
<?php
array_push($executions["ed_mp4_ogg_ee_video"], $id);
}
?>

</script>