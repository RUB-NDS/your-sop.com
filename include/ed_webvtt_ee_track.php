<?php
if (!isset($URL_A) || !isset($URL_B)) { die(); }
?>

<h2><h2><button onclick="if (document.getElementById('ed_webvtt_ee_track').style.display != 'none') { document.getElementById('ed_webvtt_ee_track').style.display = 'none'; } else { document.getElementById('ed_webvtt_ee_track').style.display = 'table'; }; ed_webvtt_ee_track();">&#x21C5;</button> EE: &lt;track&gt;</h2></h2>

<table class="bordered" id="ed_webvtt_ee_track" style="display:none">
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
    <td>&lt;track&gt;</td>
    <td style="text-decoration:overline">ED</td>
    <td id="test_HD_A_TRACK_ED_A_r" style="cursor:help">no</td>
    <td id="test_HD_A_TRACK_ED_A_w" style="cursor:help">no</td>
    <td id="test_HD_A_TRACK_ED_A_x" style="cursor:help" title="Not applicable">n.a.</td>
</tr>
<tr>
    <td style="text-decoration:overline">HD</td>
    <td>&lt;track&gt;</td>
    <td style="text-decoration:underline">ED</td>
    <td id="test_HD_A_TRACK_ED_B_r" style="cursor:help">no</td>
    <td id="test_HD_A_TRACK_ED_B_w" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_HD_A_TRACK_ED_B_x" style="cursor:help" title="Not applicable">n.a.</td>
</tr>
<tr>
    <td style="text-decoration:overline">ED</td>
    <td>&lt;track&gt;</td>
    <td style="text-decoration:overline">HD</td>
    <td id="test_ED_A_TRACK_HD_A_r" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_ED_A_TRACK_HD_A_w" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_ED_A_TRACK_HD_A_x" style="cursor:help" title="Not applicable">n.a.</td>
</tr>
<tr>
    <td style="text-decoration:overline">ED</td>
    <td>&lt;track&gt;</td>
    <td style="text-decoration:underline">HD</td>
    <td id="test_ED_A_TRACK_HD_B_r" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_ED_A_TRACK_HD_B_w" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_ED_A_TRACK_HD_B_x" style="cursor:help" title="Not applicable">n.a.</td>
</tr>
</table>

<script>
<?php
foreach(array(array("A", "$URL_A"), array("B", "$URL_B")) as $case){
	$name = $case[0];
	$url  = $case[1];
	$id = "test_HD_A_TRACK_ED_".$name."_r";
?>
function <?php echo $id . "_onload"; ?>(video, track, id) {
    var access;
    if(track.track.cues[0] && track.track.cues[0].text == 'Teststring for WebVTT') {
        access = 'yes';
    } else {
        access = 'no';
    }
    set(id, access);
    document.getElementById("loadbar").removeChild(video);
    document.free = true;
}

function <?php echo $id; ?>(){
	var video = document.createElement('video');
	var id = getFunctionName();
    
    var source2 = document.createElement('source');
    source2.src = '<?php echo $url; ?>video/ogg.php?func=<?php echo $id . "&exec=" . urlencode($_GET["exec"]);?>';
    source2.type = 'video/ogg';
    video.appendChild(source2);
    
    var source = document.createElement('source');
    source.src = '<?php echo $url; ?>video/mp4.php?func=<?php echo $id . "&exec=" . urlencode($_GET["exec"]);?>';
    source.type = 'video/mp4';
    video.appendChild(source);

    var track = document.createElement('track');
    track.src = '<?php echo $url; ?>track/webvtt.php?func=<?php echo $id . "&exec=" . urlencode($_GET["exec"]);?>';
    track.default = "true";
    video.appendChild(track);

    video.style.display = "none";

	video.onloadeddata = function(){ 
		var args = Array();
        args.push(video);
        args.push(track);
        args.push(id);
        call(<?php echo $id . "_onload"; ?>, args);
        depleteQueue();
	};
	document.getElementById("loadbar").appendChild(video);
    document.free = true;
} 
<?php
array_push($executions["ed_webvtt_ee_track"], $id);
}
?>

<?php
foreach(array(array("A", "$URL_A")) as $case){
    $name = $case[0];
    $url  = $case[1];
    $id = "test_HD_A_TRACK_ED_".$name."_w";
?>
function <?php echo $id . "_onload"; ?>(video, track,  id) {
    var access = "no";
    if(track.track.cues[0]) {
        track.track.cues[0].text = "Overwritten text";
        if(track.track.cues[0].text === "Overwritten text") {
            access = 'yes';
        } else {
            access = 'no';
        }
    }
    set(id, access);
    document.getElementById("loadbar").removeChild(video);
    document.free = true;
}

function <?php echo $id; ?>(){
    var video = document.createElement('video');
    var id = getFunctionName();
    
    var source2 = document.createElement('source');
    source2.src = '<?php echo $url; ?>video/ogg.php?func=<?php echo $id . "&exec=" . urlencode($_GET["exec"]);?>';
    source2.type = 'video/ogg';
    video.appendChild(source2);
    
    var source = document.createElement('source');
    source.src = '<?php echo $url; ?>video/mp4.php?func=<?php echo $id . "&exec=" . urlencode($_GET["exec"]);?>';
    source.type = 'video/mp4';
    video.appendChild(source);

    var track = document.createElement('track');
    track.src = '<?php echo $url; ?>track/webvtt.php?func=<?php echo $id . "&exec=" . urlencode($_GET["exec"]);?>';
    track.default = "true";
    video.appendChild(track);

    video.onloadeddata = function(){ 
        var args = Array();
        args.push(video);
        args.push(track);
        args.push(id);
        call(<?php echo $id . "_onload"; ?>, args);
        depleteQueue();
    };

    video.style.display = "none";

    document.getElementById("loadbar").appendChild(video);
    document.free = true;
} 
<?php
array_push($executions["ed_webvtt_ee_track"], $id);
}
?>

</script>