<?php
error_reporting(E_ALL);
include(__DIR__ . "/config.php");

$_SESSION['write'] = 1;

$executions = array("ed_css_ee_link" => array(), "ed_html_ee_iframe" => array(), "ed_jpg_png_ee_canvas" => array(), "ed_jpg_png_ee_img" => array(), "ed_js_ee_script" => array(), "ed_svg_ee_canvas" => array(), "ed_svg_ee_iframe_object_embed" => array(), "ed_mp4_ogg_ee_video" => array(), "ed_mp4_ogg_ee_canvas" => array(), "ed_webvtt_ee_track" => array());
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Formally defining the Same-Origin Policy for the HTML Context</title>
	</head>
	<link rel="stylesheet" href="style.css">
	<script>
	if (top.location.href.split("?")[0] != '<?php echo $MAIN_FILE; ?>') {
		top.location.href = '<?php echo $MAIN_FILE; ?>';
	}

	function set(id,value,additionalInfo) {
		if (id.match("[a-zA-Z_0-9]+") != id) {
			return; /* little XSS protection */
		}
		description = eval(id).toString();
		if ( additionalInfo != undefined ) {
			description = additionalInfo + "\n\n" + description;
		}
		var target = document.getElementById(id);
		if (target.textContent != null && target.textContent.indexOf('no') < 0) {
			value = target.textContent;
		}
		target.setAttribute("title", description);
		
		target.textContent=value;
	}
	function setDirectly(id,value,description) {
		var target = document.getElementById(id);
		if (target.textContent != null && target.textContent.indexOf('no') < 0) {
			value = target.textContent;
		}
		target.setAttribute("title", description);
		
		target.textContent=value;
	}
	function getFunctionName() {
		return arguments.callee.caller.toString().match(/function ([^\(]+)/)[1];
	}
	function postMessageEvt (evt) {
		data = JSON.parse(evt.data);
		if(data.directly != null) {
			setDirectly(data.id,data.value,data.additionalInfo)
		} else {
			set(data.id,data.value,data.additionalInfo);
		}
	}
	if (window.addEventListener) {
		/* For standards-compliant web browsers */
		window.addEventListener("message", postMessageEvt, false);
		/*window.addEventListener("hashchange", function() {
			var id = location.hash.substr(1);
			var td = document.getElementById(id);
			td.firstElementChild.textContent = "partial"; 
		}, false);*/
	}
	else {
		window.attachEvent("onmessage", postMessageEvt);
	}
	
	window.onhashchange = function(evt) {
		/* Not working in IE
		var newURL = evt.newURL;
		var hashPos = newURL.indexOf("#");
		var hash = newURL.substr(hashPos);
		var testCase = hash.substr(14); // 14 = len("#partialwrite_")
		*/
		var testCase = location.hash.substr(14);
		var td = document.getElementById(testCase).textContent = "partial"; 
	};
function generateReport() {
		var results = {};
		var tests = document.querySelectorAll("td[id]");
		for(var i=0; i<tests.length; ++i) {
			var key = tests[i].id;		
			key = key.replace("CANVAS_with_png", "CANVAS with PNG");
			key = key.replace("CANVAS_with_svg", "CANVAS with SVG");
			key = key.replace("cross_origin_anonymous", "Cross-origin: anonymous");
			key = key.replace("cross_origin_(not_set)", "Cross-origin: (not set)");
			key = key.replace("cross_origin__not_set_", "Cross-origin: (not set)");
			key = key.replace("cross_origin_use_credentials", "Cross-origin: use credentials");
			key = key.replace("origin_A", "Access-Control-Allow-Origin: your-sop.com");
			key = key.replace("origin_B", "Access-Control-Allow-Origin: other-domain.org");
			key = key.replace("origin_(not_set)", "Access-Control-Allow-Origin: (not set)");
			key = key.replace("origin__not_set_", "Access-Control-Allow-Origin: (not set)");


			key = key.replace("origin_wildcard", "Access-Control-Allow-Origin: *");
			key = key.replace("credentials_true", "Use-Credentials: true");
			key = key.replace("credentials_false", "Use-Credentials: false");
			key = key.replace("credentials_(not_set)", "Use-Credentials: (not set)");
			key = key.replace("credentials__not_set_", "Use-Credentials: (not set)");			
			key = key.replace("sandbox_allow_scripts_allow_same_origin_allow_top_navigation", "Sandbox attribute: allow-scripts allow-same-origin allow-top-navigation");
			key = key.replace("sandbox_allow_scripts_allow_top_navigation", "Sandbox attribute: allow-top-navigation");
			key = key.replace("sandbox_allow_scripts_allow_same_origin", "Sandbox attribute: allow-scripts allow-same-origin");
			key = key.replace("sandbox_allow_top_navigation", "Sandbox attribute: allow-top-navigation");
			key = key.replace("sandbox_allow_same_origin", "Sandbox attribute: allow-same-origin");		
			key = key.replace("sandbox_allow_scripts", "Sandbox attribute: allow-scripts");	
			key = key.replace("sandbox_empty_value", "Sandbox-attribute: (empty value)");	
			key = key.replace("sandbox_not_set", "Sandbox-attribute: (not set)");
			
			results[key] = {};
			
			results[key]["result"] = tests[i].textContent;
			/*results[key]["description"] = tests[i].title; */
		}
		/*document.write(JSON.stringify(results)); */
		var jsonReport = JSON.stringify(results);
		var element = document.createElement('a');
		element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(jsonReport));
		element.setAttribute('download', "report.json");
		element.style.display = 'none';
		document.body.appendChild(element);
		element.click();
		document.body.removeChild(element);		
	};
	</script>
	<body>
	<h1 id="title">Same-Origin Policy Testing Tool</h1>
	<div>
	<p>The Same-Origin Policy (SOP) controls the interaction between the host document (HD) and an embedded document (ED), and is the main line of defense against numerous kinds of web attacks. Thus, a clear understanding of this access control policy is of prime importance. Unfortunately, there is no formal specification of the Same-Origin Policy, in contrast to other important concepts like web origins (RFC 6454) or the Document Object Model (W3C DOM). In the literature, the SOP is mostly described in terms of boolean (allow/deny) decisions based on web origins (protocol, domain, port), but these descriptions are inconsistent.</p>
	
<p>We show that the access rights granted by the SOP depend on the embedding element (EE, e.g., &lt;iframe&gt;, &lt;script&gt;, and &lt;img&gt;). We describe the  SOP in terms of read, write, and execute rights as a role-based access control (RBAC) model, in which the role is defined by the EE.</p>

<p>We systematically tested the SOP implementation of ten modern browsers with our test bed at www.your-sop.com and we implemented more than 500 different RBAC test cases. Our tests show that standard SOP cases with elements like &lt;img&gt; or &lt;link&gt; are correctly implemented, but in more than 23% of the executed tests – mostly in edge cases, for example, using &lt;canvas&gt; or CORS – we detected a different behavior. 
This confirms the need for a formal specification.</p>

<p><strong>SOP Tool:</strong> Based on your currently used browser, this tool automatically evaluates SOP restriction tables that are based on our formal RBAC model notation. Please click on the buttons to open or hide each table. You can hover on the r/w/x cells to see the used JavaScript code.</p>
</div>
	<hr>
	<div style="display: flex">
	  <span style="float: left;  width: 450px;"><button onclick="window.location='stats.php'">Other SOP's</button></span>
	  <span><button onclick="for (i = 0; i < document.querySelectorAll('table').length; i++) { document.querySelectorAll('table')[i].style.display='none'; }">Hide all</button> <button onclick="allTestGroups(); for (i = 0; i < document.querySelectorAll('table').length; i++) { document.querySelectorAll('table')[i].style.display='table'; }">Display all</button><!--<button onclick="generateReport()">Generate Report</button>--></span>
	</div>
	<hr>
	<h1>ED: JPG and PNG</h1
	<?php 
	include(__DIR__ . "/include/ed_jpg_png_ee_img.php");
	include(__DIR__ . "/include/ed_jpg_png_ee_canvas.php"); 
	/* <h2>EE: &lt;picture&gt;</h2> */
	?>
	
	<h1>ED: Scalable Vector Graphics (SVG)</h1>
	<?php 
	include(__DIR__ . "/include/ed_svg_ee_canvas.php");
	include(__DIR__ . "/include/ed_svg_ee_iframe_object_embed.php"); 
	?>
	
	<h1>ED: JavaScript</h1>
	<?php
	include(__DIR__ . "/include/ed_js_ee_script.php"); 
	?>
	
	<h1>ED: Cascading Style Sheets (CSS)</h1>
	<?php
	include(__DIR__ . "/include/ed_css_ee_link.php");
	?>
	
	<h1>ED: HTML</h1>
	<?php
	include(__DIR__ . "/include/ed_html_ee_iframe.php");
	?>
	
	<h1>ED: MP4 and OGG</h1>
	<?php
	include(__DIR__ . "/include/ed_mp4_ogg_ee_video.php");
	include(__DIR__ . "/include/ed_mp4_ogg_ee_canvas.php");
	?>
	
	<h1>ED: WebVTT</h1>
	<?php
	include(__DIR__ . "/include/ed_webvtt_ee_track.php");
	?>

	<p align="right"><a href="#title">Jump to the top</a></p>
	
	<?php
	include(__DIR__ . "/include/execute_tests.php");
	?>

	<div id="loadbar" style="display: block;"></div>
	</body>
</html>