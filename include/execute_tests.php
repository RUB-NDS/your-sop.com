<?php
if (!isset($executions)) { die(); }
?>

<script>

	function cleanEEs() {
		document.getElementById("loadbar").innerHTML="Finished Loading";
	}
	<?php
/* --- Implementation Marker: Way of Calling ---- */
/* We might use this place to use a get parameter to choose from a way of calling a function. */
/* Since if we do this in PHP we can use setTimeout() of JS to secure the content is loaded properly. */
/* But we might aswell use something that is safe like a "mutex" */
/* Problems might be:  */
/* 		- In different situations timing might proove to be not accurate */
/* 		- JavaScript might have nothing like a mutex */
/* Therefore a solution like a call queue could be helpful. */
/* This queue uses a mutex and continiously checks if the URL is "free" */
/* sleeps using Promises */
/* Queue is global and contains function names only, source is fetched with .toString() */
/*  */
/* Open Questions: */
/* 		- Should the function run in an infinite loop */
/*		- Should it be called and again use sth mutex like ?  */
/* 		- Promises to build Mutex ?  */

	foreach($executions as $group => $tests) {
		echo "function ".$group."() {\n";
		foreach ($tests as $test) {
			echo "	call(".$test.");\n";
			/*echo "	".$test."();\n"; */
		}
		if ($group == "ed_html_ee_iframe") {

			echo "\n        redoSpecialTests();\n";
		}
		echo "window.".$group." = function () {};\n";
		echo "depleteQueue();\n";
		echo "}\n";
	}
	?>

	function test(image) {
		if(!document.imags) {
			document.imags = Array();
		}
		document.imags.push(["foo", image]);
	}
	
	function sleep(timeout) {
		return new Promise(resolve => setTimeout(resolve, timeout));
	}

	function call(func, args = []) {
		if(!document.callQueue) {
			document.callQueue = Array();
		}
		document.callQueue.push([func, args]); 
	}

	/* This solution only works for javascript so far. The protocol could be set with a PHP variable. */
	/* Maybe modify existing code such that every test case sets free = true in its last line by default */
	async function depleteQueue() { /* async: allows await to be used */
		if(!document.working || document.working == false) {
			document.working = true;
		}
		else {
			return 0;
		}
		var func;
		if(!document.free) {
			document.free = true;
		}
		var i = 0;
		while (i < document.callQueue.length) {
			if (document.callQueue[i][0]) { /* function name */
 				var code = document.callQueue[i][0].toString(); /* get source code */
				while (document.free == false)
				{
					await sleep(10);
				}
				document.free = false;
				/* The following line should be set according to the execution that is preferred via PHP */
				window.location = "javascript:" + code + document.callQueue[i][0].name + ".apply(null, document.callQueue[" + i + "][1]);"; /* todo: Modify source such that last line sets free = true */
				while (document.free == false) /* wait to clean the queue */
				{
					await sleep(10);
				}
				document.callQueue[i] = undefined; /* clean current queue entry. This still bloats a bit but seems to be the best solution so far */
				i++;
			}
			
		}
		document.working = false;
	}

	<?php

	echo "function redoSpecialTests() {\n";
	$tests = $executions["ed_html_ee_iframe"];
	
	function allow_top_navigation_w($val) {
		return strpos($val, "est_ED") && strpos($val, "allow_top_navigation_w") && strpos($val, "allow_scripts");
	}
	
	$redo = array_filter($tests, "allow_top_navigation_w");
	
	$i = 1;
	foreach($redo as $test) {
		/*echo "	".$test."();\n"; */
		echo "	setTimeout(".$test.", ".(500+$i*1500).");\n";		
		$i++;
	}
	foreach($redo as $test) {
		/*echo "	".$test."();\n"; */
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