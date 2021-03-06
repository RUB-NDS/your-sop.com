<?php 
	include(__DIR__ . "/config.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Modeling the Same-Origin Policy for DOM Access with ABAC</title>
		<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
		<link rel="stylesheet" href="style.php">
		<style>
		.displaysettings { display: none;}
		.centering { margin: auto; }
		</style>
	</head>
	<body>
<?php
	include(__DIR__ . "/header.php");
?>
<hr>
<div style="display: flex">
	<span style="float: left;  width: 450px;"><button onclick="top.location='<?php echo $MAIN_FILE; ?>'" style="color:red">Your SOP</button> <select style="height:100%" name="exec" onchange="top.location.href = 'http://your-sop.com/stats-' + this.value + '.php'"><option>Statistics</option><option value="usenix">Usenix Security 2017</option><option value="general">Updated Stats</option><option  value="suborigin">Suborigin</option></select></span> <span><button onclick="console.log('Calculating ...'); var rows = removeNA();mark();shownumbers(rows);console.log('... ready');">Only display differences</button> <button onclick="unmark()">Show all</button><button onclick="alert('Colorizing with red (no), green (yes), blue (partial)');colorize()">Colorize</button></span> 
</div>
<div style="center" id="numbers">
</div>
<hr>
<table id="tableSOP" class="bordered">
  <tr>
    <th class="centering">FROM</th>
	<th>EE</th>
	<th class="centering">TO</th>
	<th >DETAILS</th>
	<th class="centering">RIGHT</th>
	<th><i>Recommendation</i><p style="font-size: 70%;">(based on majority)</p></th>
	<th>Windows<br>GC 48</th>
	<th>Android<br>GC 48</th>
	<th>Windows<br>FF 44</th>
	<th>Android<br>FF 44</th>
	<th>Windows<br>IE 11</th>
	<th>Windows<br>Edge 20</th>
	<th>OSX<br>Safari 9</th>
	<th>iOS<br>Safari 9</th>
	<th>Windows<br>Opera 35</th>
	<th>Windows<br>Chromodo 45</th>
  </tr>
 </table>
  
<script>
window.recommended = {};
$.getJSON( "json/windows-Chrome-48.0.2564.97.json", function ( data ) {
  var items = [];
  var i;
  var content;
  
    function replaceterms(testcase, value) {
    value = value.replace("no*", "no");
	if ( !(testcase in window.recommended)){
		window.recommended[testcase] = {};
	}
	if ( value in window.recommended[testcase]){
		window.recommended[testcase][value]++;
	} else {
		window.recommended[testcase][value] = 1;
	}
	sum = 0;
	ourreccommendation_count = 0;
	ourreccommendation_value = value;
	for (key in window.recommended[testcase]) {
		var current = window.recommended[testcase][key];
		sum += current;
		if (window.recommended[testcase][key] == ourreccommendation_count) {
			ourreccommendation_value += " / " + key;
		} else if (window.recommended[testcase][key] > ourreccommendation_count) {
			ourreccommendation_count = window.recommended[testcase][key];
			ourreccommendation_value = key;
		}
	}
	if (sum == 10) { /* All results contained */
	  var testcase_browser = testcase+"recommended";
	  document.getElementById(testcase_browser).textContent = ourreccommendation_value;  
		
	} 
    return value;
  }
  
  $.each( data, function( testcase, value ) {
	     array  = [];
		 
		 	
			
		 array = array.concat(testcase.split('_'));

	content = "<tr id='"+ testcase +"'><td class='centering'>";
		if (array[1] == 'HD' && array[2] == 'A') { content += '<span style="text-decoration:overline">HD</span>'; } else if (array[1] == 'HD' && array[2] == 'B') { content += '<span style="text-decoration:underline">HD</span>'; } else if (array[1] == 'ED' && array[2] == 'A') { content += '<span style="text-decoration:overline">ED</span>'; } else if (array[1] == 'ED' && array[2] == 'B') { content += '<span style="text-decoration:underline">ED</span>'; } else { content += array[1] + array[2]; }
	  content += "</td>";
	  content += "<td>" + array[3] + "</td>"+
	  "<td class='centering'>";
	  if (array[4] == 'HD' && array[5] == 'A') { content += '<span style="text-decoration:overline">HD</span>'; } else if (array[4] == 'HD' && array[5] == 'B') { content += '<span style="text-decoration:underline">HD</span>'; } else if (array[4] == 'ED' && array[5] == 'A') { content += '<span style="text-decoration:overline">ED</span>'; } else if (array[4] == 'ED' && array[5] == 'B') { content += '<span style="text-decoration:underline">ED</span>'; } else { content += array[4] + array[5]; }
	  content += "</td>" + 
	  "<td>";
	  for (i=7; i<array.length; i++) { 
	    if (array[i] != "w" && array[i] != "r" && array[i] != "x" ) {
		  content += array[i]+"<br>"; 
		 }
	  } 
	  content += "</td><td class='centering'>";
	    if (array[6] != "w" && array[6] != "r" && array[6] != "x" ) {
		  content += array[array.length-1]; 
		 } else {
		  content += array[6];
		 }
	
	
	  content += "</td>";	  
	  content += "<td id='" + testcase + "recommended' style='background-color: #EFEFEF'></td>";
	  content += "<td id='" + testcase + "windowsGC48'>" + replaceterms(testcase, value.result) +  "</td>"; /* print result from current function (Windows GC48) */
	  content += "<td title='androidGC48' id='" + testcase + "androidGC48'></td>";
	  content += "<td title='FF43' id='" + testcase + "FF43'></td>";
	  content += "<td title='androidFF44' id='" + testcase + "androidFF44'></td>";
	  content += "<td title='IE11' id='" + testcase + "IE11'></td>";
	  content += "<td title='Edge20' id='" + testcase + "Edge20'></td>";
	  content += "<td title='OSXSafari9' id='" + testcase + "OSXSafari9'></td>";
	  content += "<td title='iosSafari9' id='" + testcase + "iosSafari9'></td>";
	  content += "<td title='Opera35' id='" + testcase + "Opera35'></td>";
	  content += "<td title='Chromodo45' id='" + testcase + "Chromodo45'></td>";
	  content += "</tr>";
	  
	  $('#tableSOP').append(content);
  });
  
  $.getJSON( "json/windows-Firefox-43.0.json", function ( data ) {
  $.each( data, function( testcase, value ) {
		  var testcase_browser = testcase+"FF43";
		  document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result); 
  });
  });



  $.getJSON( "json/windows-chromodo-45.json", function ( data ) {
  $.each( data, function( testcase, value ) {
		  var testcase_browser = testcase+"Chromodo45";
		  document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);  
  });
  });

  $.getJSON( "json/windows-opera-35.json", function ( data ) {
  $.each( data, function( testcase, value ) {
		  var testcase_browser = testcase+"Opera35";
		  document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);  
  });
  });

  $.getJSON( "json/windows-IE11.json", function ( data ) {
  $.each( data, function( testcase, value ) {
		  var testcase_browser = testcase+"IE11";
		  document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result); 
  });
  });

  $.getJSON( "json/windows-edge-20.json", function ( data ) {
  $.each( data, function( testcase, value ) {
		  var testcase_browser = testcase+"Edge20";
		  document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);  
  });
  });

$.getJSON( "json/osx-safari-9.json", function ( data ) {
  $.each( data, function( testcase, value ) {
		  var testcase_browser = testcase+"OSXSafari9";
		  document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);  
  });
});

$.getJSON( "json/android-GC48.json", function ( data ) {
  $.each( data, function( testcase, value ) {
		  var testcase_browser = testcase+"androidGC48";
		  document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);  
  });
});

$.getJSON( "json/android-ff44.json", function ( data ) {
  $.each( data, function( testcase, value ) {
		  var testcase_browser = testcase+"androidFF44";
		  document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);  
  });
});

$.getJSON( "json/ios-safari-9.json", function ( data ) {
  $.each( data, function( testcase, value ) {
		  var testcase_browser = testcase+"iosSafari9";
		  document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);  
  });
});


});
/* make the same results invisible for the user */
function mark () {
  console.log("Making same entries invisible to the user.");
  var trRow;
  for (var i=1; i<document.querySelectorAll("tr").length; ++i) {
  trRow = document.querySelectorAll("tr")[i];
    if ( 
    trRow.querySelectorAll("td")[6].textContent === trRow.querySelectorAll("td")[7].textContent && 
    trRow.querySelectorAll("td")[7].textContent === trRow.querySelectorAll("td")[8].textContent && 
    trRow.querySelectorAll("td")[8].textContent === trRow.querySelectorAll("td")[9].textContent && 
    trRow.querySelectorAll("td")[9].textContent === trRow.querySelectorAll("td")[10].textContent && 
    trRow.querySelectorAll("td")[10].textContent === trRow.querySelectorAll("td")[11].textContent && 
    trRow.querySelectorAll("td")[11].textContent === trRow.querySelectorAll("td")[12].textContent && 
    trRow.querySelectorAll("td")[12].textContent === trRow.querySelectorAll("td")[13].textContent && 
    trRow.querySelectorAll("td")[13].textContent === trRow.querySelectorAll("td")[14].textContent &&
	trRow.querySelectorAll("td")[14].textContent === trRow.querySelectorAll("td")[15].textContent
	) {
      trRow.setAttribute("class", "displaysettings");
    }
  }
}

function colorize() {
	console.log("Colorizing with red (no) , green (yes) and blue (partial)");
	var cells = document.querySelectorAll("td");
	for(var i=1; i < document.querySelectorAll("td").length; ++i) {
		if (cells[i].textContent.indexOf("yes") === 0) {
			cells[i].style.backgroundColor = "green";
		} else if(cells[i].textContent.indexOf("partial") === 0) {
			cells[i].style.backgroundColor = "RoyalBlue";
		} else if(cells[i].textContent.indexOf("no") === 0){
			cells[i].style.backgroundColor = "OrangeRed";
		}
	}
}

function unmark () {
  location.reload();
}

function shownumbers(rows) {
  var selectorAllLength = document.querySelectorAll(".displaysettings").length;
  document.getElementById("numbers").innerHTML = "<center><h3>You have detected " + 
  (rows - selectorAllLength) + 
  " differences within " + rows + " applicable test cases (" + 
  Math.round((rows - selectorAllLength) *
  10000 / rows) / 100 + "%).</h3></center>";
 }
 
 function removeNA() {
     var trSelection = document.querySelectorAll("tr");
     /* Check 4 Columns to be sure that the script counts right */
     for (x = 5; x <9; x++) {
     console.log("Checking column "+x+" for not applicable (n.a.) cases");
     for (i = 0; i < document.querySelectorAll("tr").length; i++) {
       if(typeof trSelection[i].querySelectorAll("td")[x] !== 'undefined') {
         if (document.querySelectorAll("tr")[i].querySelectorAll("td")[x].textContent === "n.a.") {
             document.querySelectorAll("tr")[i].remove();
         }
        }
     }
     }
     console.log("Not applicable test cases removed.");
     return document.querySelectorAll('tr').length-1;
 }
  
</script>
	<p align="right"><a href="https://www.nds.rub.de/chair/contact/">Contact</a> | <a href="#title">Jump to the top</a></p>
</body>
</html>