<?php 
	include(__DIR__ . "/config.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Modeling the Same-Origin Policy for DOM Access with ABAC</title>
		<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
		<link rel="stylesheet" href="style.css">
		<style>
		.displaysettings { display: none;}
		.centering { margin: auto; }
		</style>
	</head>
	<body>
	<h1 id="title">Same-Origin Policy: Evaluation in Modern Browsers</h1>
	<div>
				<p>The term Same-Origin Policy (SOP) is used to denote a complex set of rules that govern the interaction of different Web Origins within a web application. A subset of these SOP rules controls the interaction between the host document and an embedded document, and this subset is the target of our research (SOP-DOM). In contrast to other important concepts like Web Origins (RFC 6454) or the Document Object Model (DOM), there is no formal specification of the SOP-DOM.</p>
				<p>In an empirical study, we ran 544 different test cases on each of the 10 major web browsers. We show that in addition to Web Origins, access rights granted by SOP-DOM depend on at least three attributes; the type of the embedding element (EE), and sandbox, and CORS attributes. We also show that due to the lack of a formal specification, different browser behaviors could be detcted in about 23% of our test cases. The issues discovered in Internet Explorer and Edge are acknowledged by Microsoft (MSRC Case 32703). We discuss our findings it in terms of read, write, and execute rights in different access control models.
<ul>
<li><a href="https://www.usenix.org/conference/usenixsecurity17/technical-sessions/presentation/schwenk">USENIX Security ’17 description</a></li>
<li><a href="https://www.usenix.org/system/files/conference/usenixsecurity17/sec17-schwenk.pdf">Paper as a PDF file</a></li>
<li><a href="https://www.usenix.org/biblio/export/bibtex/203852">BibTex</a></li>
<li><a href="https://github.com/RUB-NDS/your-sop.com">GitHub</a></li>
</ul></p>
</div>
<hr>
<div style="display: flex">
	<span style="float: left;  width: 450px;"><button onclick="window.location='<?php echo $MAIN_FILE; ?>'" style="color:red">Your SOP</button></span> <span><button onclick="console.log('Calculating ...'); var rows = removeNA();mark();shownumbers(rows);console.log('... ready');">Only display differences</button> <button onclick="unmark()">Show all</button></span> 
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
	<th>Linux<br>GC 61 Native</th>
	<th>Linux<br>GC 61 JS</th>
	<th>Linux<br>FF 55 Native</th>
	<th>Linux<br>FF 55 JS</th>
  </tr>
 </table>
  
<script>
window.recommended = {};
$.getJSON( "jsonNew/linux-Chrome-61.0-native.json", function ( data ) {
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
	  for (i=6; i<array.length; i++) { 
	    if (array[i] != "w" && array[i] != "r" && array[i] != "x" && array[i] != "html" ) {
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
	  content += "<td id='" + testcase + "linuxGC61Native'>" + replaceterms(testcase, value.result) +  "</td>";
	  content += "<td id='" + testcase + "linuxGC61JS'></td>";
	  content += "<td id='" + testcase + "linuxFF55Native'></td>";
	  content += "<td id='" + testcase + "linuxFF55JS'></td>";
	  content += "</tr>";
	  
	  $('#tableSOP').append(content);
  });

  $.getJSON( "jsonNew/linux-Chrome-61.0-js.json", function ( data ) {
  $.each( data, function( testcase, value ) {
		  var testcase_browser = testcase+"linuxGC61JS";
		  document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result); 
  });
  });

  $.getJSON( "jsonNew/linux-Firefox-55.0-native.json", function ( data ) {
  $.each( data, function( testcase, value ) {
		  var testcase_browser = testcase+"linuxFF55Native";
		  document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result); 
  });
  });

  $.getJSON( "jsonNew/linux-Firefox-55.0-js.json", function ( data ) {
  $.each( data, function( testcase, value ) {
		  var testcase_browser = testcase+"linuxFF55JS";
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
    trRow.querySelectorAll("td")[8].textContent === trRow.querySelectorAll("td")[9].textContent /*&& 
    trRow.querySelectorAll("td")[9].textContent === trRow.querySelectorAll("td")[10].textContent && 
    trRow.querySelectorAll("td")[10].textContent === trRow.querySelectorAll("td")[11].textContent && 
    trRow.querySelectorAll("td")[11].textContent === trRow.querySelectorAll("td")[12].textContent && 
    trRow.querySelectorAll("td")[12].textContent === trRow.querySelectorAll("td")[13].textContent && 
    trRow.querySelectorAll("td")[13].textContent === trRow.querySelectorAll("td")[14].textContent &&
	trRow.querySelectorAll("td")[14].textContent === trRow.querySelectorAll("td")[15].textContent*/
	) {
      trRow.setAttribute("class", "displaysettings");
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
	<p align="right"><a href="https://www.hackmanit.de/impressum-en.html">Contact</a> | <a href="#title">Jump to the top</a></p>
</body>
</html>