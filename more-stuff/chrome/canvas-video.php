<?php
header("Cache-Control: no-cache, must-revalidate"); /* HTTP/1.1 */
header("Expires: -1"); /* Always invalid */
?>
<h3>Testcase | Former result</h3><br>
<a href="?">Origin: same</a> <i>yes(pixel) access</i><br>
<a href="?origin=other">Origin: cross</a> <i>no access: Failed to execute 'getImageData' on 'CanvasRenderingContext2D': The canvas has been tainted by cross-origin data.</i><br>
<a href="?suborigin=your">Origin: same - Suborigin: your</a> <i>yes(pixel) access</i><br>
<a href="?suborigin=other">Origin: same - Suborigin: other</a> <i>yes(pixel) access</i><br>
<a href="?origin=other&suborigin=your">Origin: cross - Suborigin: your</a> <i>no access: Failed to execute 'getImageData' on 'CanvasRenderingContext2D': The canvas has been tainted by cross-origin data.</i><br>
<a href="?origin=other&suborigin=other">Origin: cross - Suborigin: other</a> <i>no access: Failed to execute 'getImageData' on 'CanvasRenderingContext2D': The canvas has been tainted by cross-origin data.</i><br>
<hr>
No crossorigin attribute<br>
<a href="?cors=your">Origin: same - CORS: your</a> <i>yes(pixel) access</i><br>
<a href="?cors=other">Origin: same - CORS: other</a> <i>yes(pixel) access</i><br>
<a href="?origin=other&cors=your">Origin: cross - CORS: your</a> <i>no access: Failed to execute 'getImageData' on 'CanvasRenderingContext2D': The canvas has been tainted by cross-origin data.</i><br>
<a href="?origin=other&cors=other">Origin: cross - CORS: other</a> <i>no access: Failed to execute 'getImageData' on 'CanvasRenderingContext2D': The canvas has been tainted by cross-origin data.</i><br>
<a href="?suborigin=your&cors=your">Origin: same - Suborigin: your - CORS: your</a> <i>yes(pixel) access</i><br>
<a href="?suborigin=your&cors=other">Origin: same - Suborigin: your - CORS: other</a> <i>yes(pixel) access</i><br>
<a href="?suborigin=other&cors=your">Origin: same - Suborigin: other - CORS: your</a> <i>yes(pixel) access</i><br>
<a href="?suborigin=other&cors=other">Origin: same - Suborigin: other - CORS: other</a> <i>yes(pixel) access</i><br>
<a href="?origin=other&suborigin=your&cors=your">Origin: cross - Suborigin: your - CORS: your</a> <i>no access: Failed to execute 'getImageData' on 'CanvasRenderingContext2D': The canvas has been tainted by cross-origin data.</i><br>
<a href="?origin=other&suborigin=your&cors=other">Origin: cross - Suborigin: your - CORS: other</a> <i>no access: Failed to execute 'getImageData' on 'CanvasRenderingContext2D': The canvas has been tainted by cross-origin data.</i><br>
<a href="?origin=other&suborigin=other&cors=your">Origin: cross - Suborigin: other - CORS: your</a> <i>no access: Failed to execute 'getImageData' on 'CanvasRenderingContext2D': The canvas has been tainted by cross-origin data.</i><br>
<a href="?origin=other&suborigin=other&cors=other">Origin: cross - Suborigin: other - CORS: other</a> <i>no access: Failed to execute 'getImageData' on 'CanvasRenderingContext2D': The canvas has been tainted by cross-origin data.</i><br>
<hr>
With crossorigin=anonymous<br>
<a href="?co=anonymous&cors=your">Origin: same - CORS: your</a> <i>yes(pixel) access</i><br>
<a href="?co=anonymous&cors=other">Origin: same - CORS: other</a> <i>yes(pixel) access</i><br>
<a href="?co=anonymous&origin=other&cors=your">Origin: cross - CORS: your</a> <i>no access</i><br>
<a href="?co=anonymous&origin=other&cors=other">Origin: cross - CORS: other</a> <i>no access</i><br>
<a href="?co=anonymous&suborigin=your&cors=your">Origin: same - Suborigin: your - CORS: your</a> <i>yes(pixel) access</i><br>
<a href="?co=anonymous&suborigin=your&cors=other">Origin: same - Suborigin: your - CORS: other</a> <i>yes(pixel) access</i><br>
<a href="?co=anonymous&suborigin=other&cors=your">Origin: same - Suborigin: other - CORS: your</a> <i>yes(pixel) access</i><br>
<a href="?co=anonymous&suborigin=other&cors=other">Origin: same - Suborigin: other - CORS: other</a> <i>yes(pixel) access</i><br>
<a href="?co=anonymous&origin=other&suborigin=your&cors=your">Origin: cross - Suborigin: your - CORS: your</a> <i>no access</i><br>
<a href="?co=anonymous&origin=other&suborigin=your&cors=other">Origin: cross - Suborigin: your - CORS: other</a> <i>no access</i><br>
<a href="?co=anonymous&origin=other&suborigin=other&cors=your">Origin: cross - Suborigin: other - CORS: your</a> <i>no access</i><br>
<a href="?co=anonymous&origin=other&suborigin=other&cors=other">Origin: cross - Suborigin: other - CORS: other</a> <i>no access</i><br>
<hr>
<h3>Current result</h3>
<div id="test">test case not executed</div>

<script>
	var img = document.createElement("video");
	<?php 
	if ($_GET[origin] == "other") {
		/* no access (cross-origin) */
		echo "img.src='http://other-domain.org/bugs/chrome/video.php?fresh='+Math.random()+'&suborigin=".$_GET[suborigin]."&cors=".$_GET[cors]."';";
	} else {
		/* access (same-origin) */
		echo "img.src='http://your-sop.com/bugs/chrome/video.php?fresh='+Math.random()+'&suborigin=".$_GET[suborigin]."&cors=".$_GET[cors]."';";
	}
	
	if (isset($_GET["suborigin"])) {
		header("Suborigin: your");
	}	
	
	if ($_GET["co"] === "anonymous") {
		echo 'img.crossOrigin = "Anonymous";';
	}	
	?>
	
setTimeout(function(){
	try {
		var c = document.createElement("canvas");
		c.width="1000"; c.height="1000";
		var ctx = c.getContext("2d");
		ctx.drawImage(img, 0, 0); /* Put img on canvas at pos 0x0 */
		var pixel = ctx.getImageData(2,3,1,1); /* Read color of 1x1 pixel at position 2/3 */
		var data = pixel.data;
		if (data[0]==255) { 
			document.getElementById("test").textContent= 'yes(pixel) access ';
		} else {
			document.getElementById("test").textContent='no access ';
		}
	} catch (ex) {			
		document.getElementById("test").textContent='no access: ' + ex.message;
	}
}, 1000);
</script>