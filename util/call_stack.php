<?php
if (!isset($URL_A) || !isset($URL_B)) { die(); }
?>
	
//<![CDATA[

	function sleep(timeout) { /* simple JavaScript sleep method, requires ECMAScript 6 */
		return new Promise(resolve => setTimeout(resolve, timeout));
	}

	function call(func, args = []) {
		if(!document.callQueue) {
			document.callQueue = Array();
		}
		document.callQueue.push([func, args]); 
	}

	async function depleteQueue() { /* async: allows await to be used */
		if(!document.working || document.working == false) { /* ensure that only one instance of depleteQueue() is running */
			document.working = true;
		}
		else {
			return 0;
		}
		if(!document.free) {
			document.free = true;
		}
		var i = 0;
		while (i < document.callQueue.length) {
			if (document.callQueue[i]) { /* function name */
 				var code = document.callQueue[i][0].toString(); /* get source code */
				while (document.free == false) /* as long as a called function is still running wait */
				{
					await sleep(10);
				}
				document.free = false; /* We are going to call a function, block execution -> only one test case executes at a time */
				/* implement different ways to execute a script below */
				<?php 
				if (isset($_GET['exec']) && $_GET['exec'] === 'js') {
					echo 'window.location = "javascript:" + code + document.callQueue[i][0].name + ".apply(null, document.callQueue[" + i + "][1]);";';
					echo "\n";
				} else {
					echo 'document.callQueue[i][0].apply(null, document.callQueue[i][1]);';
					echo "\n";
				}
				?>
				var startTime = Date.now();
				while (document.free == false) /* wait to clean the queue */
				{
					await sleep(10);
					/* Following code can be used to abort overlong executing (malfunctioning) test cases */
					if(Date.now() - startTime > 10000) {
						console.log("Aborting: " + document.callQueue[i][0].name + " due to too long execution time for: " + window.location);
						document.free = true;
					}

					/* console.log("blocking: " + document.callQueue[i][0].name + " for: " + window.location); */
				}
				document.callQueue[i] = undefined; /* clean current queue entry to prevent double execution */
			}
			i++;	
		}
		document.working = false; /* current instance of depleteQueue finished */
	}

//]]>