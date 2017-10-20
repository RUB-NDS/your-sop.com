<?php
if (!isset($URL_A) || !isset($URL_B)) { die(); }
?>

<h2><h2><button onclick="if (document.getElementById('ed_mp3_ee_audio').style.display != 'none') { document.getElementById('ed_mp3_ee_audio').style.display = 'none'; } else { document.getElementById('ed_mp3_ee_audio').style.display = 'table'; }; ed_mp3_ee_audio();">&#x21C5;</button> EE: &lt;audio&gt;</h2></h2>

<table class="bordered" id="ed_mp3_ee_audio" style="display:none">
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
    <td>&lt;audio&gt;</td>
    <td style="text-decoration:overline">ED</td>
    <td id="test_HD_A_AUDIO_ED_A_r" style="cursor:help">no</td>
    <td id="test_HD_A_AUDIO_ED_A_w" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_HD_A_AUDIO_ED_A_x" style="cursor:help" title="Not applicable">n.a.</td>
</tr>
<tr>
    <td style="text-decoration:overline">HD</td>
    <td>&lt;audio&gt;</td>
    <td style="text-decoration:underline">ED</td>
    <td id="test_HD_A_AUDIO_ED_B_r" style="cursor:help">no</td>
    <td id="test_HD_A_AUDIO_ED_B_w" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_HD_A_AUDIO_ED_B_x" style="cursor:help" title="Not applicable">n.a.</td>
</tr>
<tr>
    <td style="text-decoration:overline">ED</td>
    <td>&lt;audio&gt;</td>
    <td style="text-decoration:overline">HD</td>
    <td id="test_ED_A_AUDIO_HD_A_r" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_ED_A_AUDIO_HD_A_w" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_ED_A_AUDIO_HD_A_x" style="cursor:help" title="Not applicable">n.a.</td>
</tr>
<tr>
    <td style="text-decoration:overline">ED</td>
    <td>&lt;audio&gt;</td>
    <td style="text-decoration:underline">HD</td>
    <td id="test_ED_A_AUDIO_HD_B_r" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_ED_A_AUDIO_HD_B_w" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_ED_A_AUDIO_HD_B_x" style="cursor:help" title="Not applicable">n.a.</td>
</tr>
</table>

<script>
<?php
foreach(array(array("A", "$URL_A"), array("B", "$URL_B")) as $case){
	$name = $case[0];
	$url  = $case[1];
	$id = "test_HD_A_AUDIO_ED_".$name."_r";
?>
function <?php echo $id . "_onload"; ?>(audio, id) {
    audio.muted = true; /* don't do a sound */
    audio.play(); /* play and pause, makes duration more reliable */
    audio.pause();
    if(audio.duration >= 4) {
        set(id, "partial");
    }
    
    document.free = true;
}

function <?php echo $id; ?>(){
	var audio = document.createElement('audio');
	var id = getFunctionName();
	audio.onloadeddata = function(){ 
		var args = Array();
        args.push(audio);
        args.push(id);
        call(<?php echo $id . "_onload"; ?>, args);
        depleteQueue();
	};
	var source = document.createElement('source');
	source.src = '<?php echo $url; ?>audio/mp3.php?exec=<?php echo urlencode($_GET["exec"]). "&to=ED " . urlencode($name);?>';
	source.type = 'audio/mpeg';
	audio.appendChild(source);
	
    audio.controls = true;
    audio.style.display = "none";

	document.getElementById("loadbar").appendChild(audio);
    document.free = true;
} 
<?php
array_push($executions["ed_mp3_ee_audio"], $id);
}
?>

</script>