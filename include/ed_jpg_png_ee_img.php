<?php
 if (!isset($URL_A) || !isset($URL_B)) { die(); }
?>

<h2><h2><button onclick="if (document.getElementById('ed_jpg_png_ee_img').style.display != 'none') { document.getElementById('ed_jpg_png_ee_img').style.display = 'none'; } else { document.getElementById('ed_jpg_png_ee_img').style.display = 'table'; }; ed_jpg_png_ee_img();">&#x21C5;</button> EE: &lt;img&gt;</h2></h2>

<table class="bordered" id="ed_jpg_png_ee_img" style="display:none">
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
    <td>&lt;img&gt;</td>
    <td style="text-decoration:overline">ED</td>
    <td id="test_HD_A_IMG_ED_A_r" style="cursor:help">no</td>
    <td id="test_HD_A_IMG_ED_A_w" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_HD_A_IMG_ED_A_x" style="cursor:help" title="Not applicable">n.a.</td>
</tr>
<tr>
    <td style="text-decoration:overline">HD</td>
    <td>&lt;img&gt;</td>
    <td style="text-decoration:underline">ED</td>
    <td id="test_HD_A_IMG_ED_B_r" style="cursor:help">no</td>
    <td id="test_HD_A_IMG_ED_B_w" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_HD_A_IMG_ED_B_x" style="cursor:help" title="Not applicable">n.a.</td>
</tr>
<tr>
    <td style="text-decoration:overline">ED</td>
    <td>&lt;img&gt;</td>
    <td style="text-decoration:overline">HD</td>
    <td id="test_ED_A_IMG_HD_A_r" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_ED_A_IMG_HD_A_w" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_ED_A_IMG_HD_A_x" style="cursor:help" title="Not applicable">n.a.</td>
</tr>
<tr>
    <td style="text-decoration:overline">ED</td>
    <td>&lt;img&gt;</td>
    <td style="text-decoration:underline">HD</td>
    <td id="test_ED_A_IMG_HD_B_r" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_ED_A_IMG_HD_B_w" style="cursor:help" title="Not applicable">n.a.</td>
    <td id="test_ED_A_IMG_HD_B_x" style="cursor:help" title="Not applicable">n.a.</td>
</tr>
</table>

<script>
<?php
foreach(array(array("A", "$URL_A"), array("B", "$URL_B")) as $case){
	$name = $case[0];
	$url  = $case[1];
	$id = "test_HD_A_IMG_ED_".$name."_r";
?>
function <?php echo $id; ?>(){
	var img = document.createElement('img');
	var id = getFunctionName();
	img.onload = function(){ 
		set(id,(img.width==111)?'partial':'no');
	};
	img.src='<?php echo $url; ?>img/png.php?func='+id;
} 
<?php
array_push($executions["ed_jpg_png_ee_img"], $id);
}
?>

</script>
