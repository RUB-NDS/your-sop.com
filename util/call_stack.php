<?php
if (!isset($URL_A) || !isset($URL_B)) { die(); }
?>

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
			if (document.callQueue[i]) { /* function name */
 				var code = document.callQueue[i][0].toString(); /* get source code */
				while (document.free == false)
				{
					await sleep(10);
				}
				document.free = false;
				/* The following line should be set according to the execution that is preferred via PHP */
				<?php 
				if (isset($_GET['exec']) && $_GET['exec'] === 'js') {
					echo 'window.location = "javascript:" + code + document.callQueue[i][0].name + ".apply(null, document.callQueue[" + i + "][1]);";';
					echo "\n";
				} else {
					echo 'document.callQueue[i][0].apply(null, document.callQueue[i][1]);';
					echo "\n";
				}
				?>
				console.log("Executed: " + document.callQueue[i][0].name);
				while (document.free == false) /* wait to clean the queue */
				{
					await sleep(10);
				}
				document.callQueue[i] = undefined; /* clean current queue entry. This still bloats a bit but seems to be the best solution so far */
			}
			i++;
			
		}
		document.working = false;
	}