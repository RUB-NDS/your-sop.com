<?php 
	include(__DIR__ . "/config.php");
?>
<!DOCTYPE html>
<html><head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Same-Origin Policy: Evaluation in Modern Browsers</title>
		<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
		<link rel="stylesheet" href="style.php">
	</head>
	<body>
<?php
	include(__DIR__ . "/header.php");
?>
<hr>
<div style="display: flex">
	<span style="float: left;  width: 450px;"><button onclick="window.location='http://your-sop.com/index.php'" style="color:red">Your SOP</button> <select style="height:100%" name="exec" onchange="top.location.href = 'http://your-sop.com/stats-' + this.value + '.php'"><option>Statistics</option><option value="usenix">Usenix Security 2017</option><option value="general">Updated Stats</option><option  value="suborigin">Suborigin</option></select></span> 
	<span><button onclick="location.reload()">Show all</button> <button onclick="console.log('Calculating ...'); var rows = removeNA();mark();shownumbers(rows);console.log('... ready');">Only display differences</button> <button onclick="colorize()" title="Colorizing with red (no) , green (yes) and blue (partial)">Colorize</button> <button onclick="filter()">Do not display  yes*</button></span> 
</div>
<div style="center" id="numbers">
</div>
<hr>
<?php
// general stuff
$right ='<span title="Right" class="dots">&#9998;</span>';
$greaterthan = '<span title="Recommendation based on majority" class="dots">≥</span>';
$native = '<span title="Native" class="dots">NA</span>';
$javascript = '<span title="JavaScript" class="dots">JS</span>';
$nightly = '<span title="Nightly" class="dots">NL</span>';
//browsers
$chromium = '<span title="Chromium" class="dots">CR</span>';
$opera = '<span title="Opera" class="dots">OP</span>';
$safari = '<span title="Safari" class="dots">SA</span>';
$comodoDragon = '<span title="Comodo Dragon" class="dots">CD</span>';
$msedge = '<span title="Microsoft Edge" class="dots">ME</span>';
$brave = '<span title="Brave" class="dots">BR</span>';
$yandex = '<span title="Yandex" class="dots">YD</span>';
$vivaldi = '<span title="Vivaldi" class="dots">VV</span>';
$googlechrome = '<span title="Google Chrome" class="dots">GC</span>';
$firefox = '<span title="Firefox" class="dots">FF</span>';
// operating systems
$windows = '<span title="Windows" class="dots"><i>⊞</i></span>';
$macos = '<span title="macOS" class="dots">🍏</span>';
$linux = '<span title="Linux" class="dots">🐧</span>';
$ios = '<span title="iOS" class="dots">iOS</span>';
?>
<table id="tableSOP" class="bordered">
  <tr>
    <th class="centering">FROM</th>
	<th>EE</th>
	<th class="centering">TO</th>
	<th>DETAILS</th>
	<th class="centering"><?=$right?></th>
	<th><?=$greaterthan?></th>
	<th><?=$macos?> <?=$googlechrome?> 61 <?=$native?></th>
	<th><?=$macos?> <?=$googlechrome?> 61 <?=$javascript?></th>
	<th><?=$macos?> <?=$googlechrome?> 61 Suborigin</th>
  </tr>
 </table>

<script>
window.recommended = {};
$.getJSON("json/suborigin/macos-Chrome-61.0-experimental-native.json", function(data) {
    var items = [];
    var i;
    var content;

    function replaceterms(testcase, value) {
        value = value.replace("no*", "no");
        value = value.replace("yes", "yes(pix)");
        if (!(testcase in window.recommended)) {
            window.recommended[testcase] = {};
        }
        if (value in window.recommended[testcase]) {
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
            var testcase_browser = testcase + "recommended";
            document.getElementById(testcase_browser).textContent = ourreccommendation_value;

        }
        return value;
    }

    $.each(data, function(testcase, value) {
        array = [];



        array = array.concat(testcase.split('_'));

        content = "<tr id='" + testcase + "'><td class='centering'>";
        if (array[1] == 'HD' && array[2] == 'A') {
            content += '<span class="overline">HD</span>';
        } else if (array[1] == 'HD' && array[2] == 'B') {
            content += '<span class="underline">HD</span>';
        } else if (array[1] == 'ED' && array[2] == 'A') {
            content += '<span class="overline">ED</span>';
        } else if (array[1] == 'ED' && array[2] == 'B') {
            content += '<span class="underline">ED</span>';
        } else {
            content += array[1] + array[2];
        }
        content += "</td>";
        content += "<td>" + array[3] + "</td>" + "<td class='centering'>";
        if (array[4] == 'HD' && array[5] == 'A') {
            content += '<span class="overline">HD</span>';
        } else if (array[4] == 'HD' && array[5] == 'B') {
            content += '<span class="underline">HD</span>';
        } else if (array[4] == 'ED' && array[5] == 'A') {
            content += '<span class="overline">ED</span>';
        } else if (array[4] == 'ED' && array[5] == 'B') {
            content += '<span class="underline">ED</span>';
        } else {
            content += array[4] + array[5];
        }
        content += "</td>" + "<td>";
        for (i = 6; i < array.length; i++) {
            if (array[i] != "w" && array[i] != "r" && array[i] != "x" && array[i] != "html") {

                var temp;
                temp = array[i];
                temp = temp.replace("Cross-origin: (not set)", "");
                temp = temp.replace("Access-Control-Allow-Origin: (not set)", "");
                temp = temp.replace("Use-Credentials: (not set)", "");
                temp = temp.replace("Cross-origin", "<span title='Cross-origin' style='border-bottom: 1px dotted black;'>CO</span>");
                temp = temp.replace("Access-Control-Allow-Origin", "<span title='Access-Control-Allow-Origin' style='border-bottom: 1px dotted black;'>ACAO</span>");
                temp = temp.replace("Use-Credentials", "<span title='Use-Credentials / Access-Control-Allow-Credentials' class='dots'>UC</span>");

                if (temp != "") {
                    content += temp + "<br>";
                }

            }
        }
        content += "</td><td class='centering'>";
        if (array[6] != "w" && array[6] != "r" && array[6] != "x") {
            content += array[array.length - 1];
        } else {
            content += array[6];
        }

        content += "</td>";
        content += "<td id='" + testcase + "recommended' style='background-color: #EFEFEF'></td>";
        content += "<td title='macos-Chrome-61.0-experimental-native' id='" + testcase + "macos-Chrome-61.0-experimental-native'>" + replaceterms(testcase, value.result) + "</td>";
        content += "<td title='macos-Chrome-61.0-experimental-js' id='" + testcase + "macos-Chrome-61.0-experimental-js'></td>";
        content += "<td title='macos-Chrome-61.0-experimental-suborigin' id='" + testcase + "macos-Chrome-61.0-experimental-suborigin'></td>";
        content += "</tr>";

        $('#tableSOP').append(content);
    });

    function addbrowserData (file, testcaseSuffix) {
    	$.getJSON(file, function(data) {
        	$.each(data, function(testcase, value) {
            	var testcase_browser = testcase + testcaseSuffix;
            	document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        	});
    	});
    }


	addbrowserData ("json/suborigin/macos-Chrome-61.0-experimental-js.json", "macos-Chrome-61.0-experimental-js");
	addbrowserData ("json/suborigin/macos-Chrome-61.0-experimental-suborigin.json", "macos-Chrome-61.0-experimental-suborigin");
    
}); /* make the same results invisible for the user */

function mark() {
    console.log("Making same entries invisible to the user.");
    var trRow;
    for (var i = 1; i < document.querySelectorAll("tr").length; ++i) {
        trRow = document.querySelectorAll("tr")[i];
        for (var y = 7; y < trRow.querySelectorAll("td").length; y++) {
    		// first entry is in column six; compare the current with the last column entry; do not make the row invisible when an entry has a different value compared to his last one
        	if (trRow.querySelectorAll("td")[y].textContent != trRow.querySelectorAll("td")[y-1].textContent) {
        		 break; // reaks the loop and continues executing the code after the loop
        	}
        	// make the row invisible when even the last to columns (browser test cases) have equal results
        	if (y === (trRow.querySelectorAll("td").length-1)) {
        		trRow.setAttribute("class", "displaysettings");
        	}
        }
    }
}

function colorize() {
    var cells = document.querySelectorAll("td");
    for (var i = 1; i < document.querySelectorAll("td").length; ++i) {
        if (cells[i].textContent.indexOf("yes") === 0) {
            cells[i].style.backgroundColor = "OrangeRed";
        } else if (cells[i].textContent.indexOf("partial") === 0) {
            cells[i].style.backgroundColor = "RoyalBlue";
        } else if (cells[i].textContent.indexOf("no") === 0) {
            cells[i].style.backgroundColor = "green";
        }
    }
}

function shownumbers(rows) {
    var selectorAllLength = document.querySelectorAll(".displaysettings").length;
    document.getElementById("numbers").innerHTML = "<center><h3>You have detected " + (rows - selectorAllLength) + " differences within " + rows + " applicable test cases (" + Math.round((rows - selectorAllLength) * 10000 / rows) / 100 + "%).</h3></center>";
}

function removeNA() {
    var trSelection = document.querySelectorAll("tr"); /* Check 4 Columns to be sure that the script counts right */
    for (x = 5; x < 9; x++) {
        console.log("Checking column " + x + " for not applicable (n.a.) cases");
        for (i = 0; i < document.querySelectorAll("tr").length; i++) {
            if (typeof trSelection[i].querySelectorAll("td")[x] !== 'undefined' && document.querySelectorAll("tr")[i].querySelectorAll("td")[x].textContent === "n.a.") {
                    document.querySelectorAll("tr")[i].remove();
            }
        }
    }
    console.log("Not applicable test cases removed.");
    return document.querySelectorAll('tr').length - 1;
}

// reduce the number of testcases
function filter() {
    //Make yes* majorities invisible
    for (x = 1; x < document.querySelectorAll("tr").length; x++) {
        if (document.querySelectorAll("tr")[x].querySelectorAll("td")[5].textContent.indexOf("yes") === 0) {
            document.querySelectorAll("tr")[x].setAttribute("class", "displaysettings");
        }
    }
}


function deleteRow(btn) {
    var row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
}
</script>
	<p align="right"><a href="#" onclick='alert(document.querySelectorAll("td").length - (document.querySelectorAll("tr").length*6) - (document.querySelectorAll("th").length+6)+" tests with "+(document.querySelectorAll("th").length - 6)+" browsers and "+document.querySelectorAll("tr").length+" test cases.")'>Stats</a> | <a href="https://www.nds.rub.de/chair/contact/">Contact</a> | <a href="#title">Jump to the top</a></p>
</body>
</html>