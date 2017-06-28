<?php
if (!isset($executions)) { die(); }
?>

<script>

function cleanEEs() {
	document.getElementById("loadbar").innerHTML="Finished Loading";
}
<?php
foreach($executions as $group => $tests) {
	echo "function ".$group."() {\n";
	foreach ($tests as $test) {
		echo "	".$test."();\n";
	}
	if ($group == "ed_html_ee_iframe") {
		
		echo "\n        redoSpecialTests();\n";
	}
	echo "window.".$group." = function () {};\n";
	echo "}\n";
}
?>
	
<?php

echo "function redoSpecialTests() {\n";
	$tests = $executions["ed_html_ee_iframe"];
	
	function allow_top_navigation_w($val) {
		return strpos($val, "est_ED") && strpos($val, "allow_top_navigation_w") && strpos($val, "allow_scripts");
	}
	
	$redo = array_filter($tests, "allow_top_navigation_w");
	
	$i = 1;
	foreach($redo as $test) {
		//echo "	".$test."();\n";
		echo "	setTimeout(".$test.", ".(500+$i*1500).");\n";		
		$i++;
	}
	foreach($redo as $test) {
		//echo "	".$test."();\n";
		echo "	setTimeout(".$test.", ".(500+$i*1500).");\n";		
		$i++;
	}
echo "}";

?>
	
function allTestGroups() {
<?php
	foreach($executions as $group => $tests) {
		echo "	".$group."();\n";
	}
?>
}
</script>