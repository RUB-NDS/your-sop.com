<?php 
	include(__DIR__ . "/config.php");
?>
<!DOCTYPE html>
<html><head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Modeling the Same-Origin Policy for DOM Access with ABAC</title>
		<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
		<link rel="stylesheet" href="style.php">
	</head>
	<body>
<?php
	include(__DIR__ . "/header.php");
?>
<hr>
<div style="display: flex">
	<span style="float: left;  width: 450px;"><button onclick="window.location='http://your-sop.com/index.php'" style="color:red">Your SOP</button></span> 
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
	<th><?=$linux?> <?=$googlechrome?> 61 <?=$native?></th>
	<th><?=$linux?> <?=$googlechrome?> 61 <?=$javascript?></th>
	<th><?=$windows?> <?=$googlechrome?> 61 <?=$native?></th>
	<th><?=$windows?> <?=$googlechrome?> 61 <?=$javascript?></th>
	<th><?=$linux?> <?=$firefox?> 55 <?=$native?></th>
	<th><?=$linux?> <?=$firefox?> 55 <?=$javascript?></th>
	<th><?=$linux?> <?=$chromium?> 61 <?=$native?></th>
	<th><?=$linux?> <?=$chromium?> 61 <?=$javascript?></th>
	<th><?=$windows?> <?=$firefox?> 55 <?=$native?></th>
  	<th><?=$windows?> <?=$firefox?> 55 <?=$javascript?></th>
  	<th><?=$windows?> <?=$opera?> 47 <?=$native?></th>
  	<th><?=$windows?> <?=$opera?> 47 <?=$javascript?></th>
  	<th><?=$windows?> <?=$brave?> 0.18.36 <?=$native?></th>
  	<th><?=$windows?> <?=$brave?> 0.18.36 <?=$javascript?></th>
  	<th><?=$windows?> <?=$msedge?> 40 <?=$native?></th>
  	<th><?=$windows?> <?=$msedge?> 40 <?=$javascript?></th>
  	<th><?=$windows?> <?=$comodoDragon?> 58 <?=$native?></th>
  	<th><?=$windows?> <?=$comodoDragon?> 58 <?=$javascript?></th>
	<th><?=$macos?> <?=$firefox?> 58.0a1 <?=$nightly?> <?=$native?></th>
  	<th><?=$macos?> <?=$safari?> 11.0 <?=$native?></th>
  	<th><?=$macos?> <?=$safari?> 11.0 <?=$javascript?></th>
  	<th><?=$ios?> <?=$safari?> 11.0 <?=$native?></th>
  	<th><?=$ios?> <?=$safari?> 11.0 <?=$javascript?></th>
  	<th><?=$macos?> <?=$yandex?> 17.9 <?=$native?></th>
  	<th><?=$macos?> <?=$yandex?> 17.9 <?=$javascript?></th>
  	<th><?=$macos?> <?=$vivaldi?> 1.12 <?=$native?></th>
  	<th><?=$macos?> <?=$vivaldi?> 1.12 <?=$javascript?></th>
  </tr>
 </table>

<script>
window.recommended = {};
$.getJSON("jsonNew/linux-Chrome-61.0-native.json", function(data) {
    var items = [];
    var i;
    var content;

    function replaceterms(testcase, value) {
        value = value.replace("no*", "no");
        /* value = value.replace("yes", "yes(pix)"); */
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
        content += "<td title='linuxGC61Native' id='" + testcase + "linuxGC61Native'>" + replaceterms(testcase, value.result) + "</td>";
        content += "<td title='linuxGC61JS' id='" + testcase + "linuxGC61JS'></td>";
        content += "<td title='windowsGC61Native' id='" + testcase + "windowsGC61Native'></td>";
        content += "<td title='windowsGC61JS' id='" + testcase + "windowsGC61JS'></td>";
        content += "<td title='linuxFF55Native' id='" + testcase + "linuxFF55Native'></td>";
        content += "<td title='linuxFF55JS' id='" + testcase + "linuxFF55JS'></td>";
        content += "<td title='linuxChromium61Native' id='" + testcase + "linuxChromium61Native'></td>";
        content += "<td title='linuxChromium61JS' id='" + testcase + "linuxChromium61JS'></td>";
        content += "<td title='windowsFF55Native' id='" + testcase + "windowsFF55Native'></td>";
        content += "<td title='windowsFF55JS' id='" + testcase + "windowsFF55JS'></td>";
        content += "<td title='windowsOpera47Native' id='" + testcase + "windowsOpera47Native'></td>";
        content += "<td title='windowsOpera47JS' id='" + testcase + "windowsOpera47JS'></td>";
        content += "<td title='windowsBrave018JS' id='" + testcase + "windowsBrave018Native'></td>";
        content += "<td title='windowsBrave018JS' id='" + testcase + "windowsBrave018JS'></td>";
        content += "<td title='windowsEdge40Native' id='" + testcase + "windowsEdge40Native'></td>";
        content += "<td title='windowsEdge40JS' id='" + testcase + "windowsEdge40JS'></td>";
        content += "<td title='windowsComodoDragon58Native' id='" + testcase + "windowsComodoDragon58Native'></td>";
        content += "<td title='windowsComodoDragon58JS' id='" + testcase + "windowsComodoDragon58JS'></td>";
        content += "<td title='macos-Firefox-Nightly-58.0a1-2017-10-15-native' id='" + testcase + "macos-Firefox-Nightly-58.0a1-2017-10-15-native'></td>";
        content += "<td title='osxSafari11Native' id='" + testcase + "osxSafari11Native'></td>";
        content += "<td title='osxSafari11JS' id='" + testcase + "osxSafari11JS'></td>";
        content += "<td title='iosSafari11Native' id='" + testcase + "iosSafari11Native'></td>";
        content += "<td title='iosSafari11JS' id='" + testcase + "iosSafari11JS'></td>";
        content += "<td title='osxYandex17Native' id='" + testcase + "osxYandex17Native'></td>";
        content += "<td title='osxYandex17JS' id='" + testcase + "osxYandex17JS'></td>";
        content += "<td title='osxVivaldi112Native' id='" + testcase + "osxVivaldi112Native'></td>";
        content += "<td title='osxVivaldi112JS' id='" + testcase + "osxVivaldi112JS'></td>";
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

	addbrowserData ("jsonNew/linux-Chrome-61.0-js.json", "linuxGC61JS");
	addbrowserData ("jsonNew/windows-Chrome-61.0-native.json", "windowsGC61Native");
	addbrowserData ("jsonNew/windows-Chrome-61.0-js.json", "windowsGC61JS");
	addbrowserData ("jsonNew/linux-Firefox-55.0-native.json", "linuxFF55Native");
	addbrowserData ("jsonNew/linux-Firefox-55.0-js.json", "linuxFF55JS");
	addbrowserData ("jsonNew/linux-Chromium-61-native.json", "linuxChromium61Native");
	addbrowserData ("jsonNew/linux-Chromium-61-js.json", "linuxChromium61JS");
	addbrowserData ("jsonNew/windows-Firefox-55.0-native.json", "windowsFF55Native");
	addbrowserData ("jsonNew/windows-Firefox-55.0-js.json", "windowsFF55JS");
	addbrowserData ("jsonNew/windows-Opera-47.0-native.json", "windowsOpera47Native");
	addbrowserData ("jsonNew/windows-Opera-47.0-js.json", "windowsOpera47JS");
	addbrowserData ("jsonNew/windows-Brave-0.18.36-native.json", "windowsBrave018Native");
	addbrowserData ("jsonNew/windows-Brave-0.18.36-js.json", "windowsBrave018JS");
	addbrowserData ("jsonNew/windows-Edge-40-native.json", "windowsEdge40Native");
	addbrowserData ("jsonNew/windows-Edge-40-js.json", "windowsEdge40JS");
	addbrowserData ("jsonNew/windows-ComodoDragon-58-native.json", "windowsComodoDragon58Native");
	addbrowserData ("jsonNew/windows-ComodoDragon-58-js.json", "windowsComodoDragon58JS");
	addbrowserData ("jsonNew/macos-Firefox-Nightly-58.0a1-2017-10-15-native.json", "macos-Firefox-Nightly-58.0a1-2017-10-15-native");
	addbrowserData ("jsonNew/osx-Safari-11.0-native.json", "osxSafari11Native");
	addbrowserData ("jsonNew/osx-Safari-11.0-js.json", "osxSafari11JS");
	addbrowserData ("jsonNew/ios-Safari-11.0-native.json", "iosSafari11Native");
	addbrowserData ("jsonNew/ios-Safari-11.0-js.json", "iosSafari11JS");
	addbrowserData ("jsonNew/osx-Yandex-17.9.1.888-native.json", "osxYandex17Native");
	addbrowserData ("jsonNew/osx-Yandex-17.9.1.888-js.json", "osxYandex17JS");
	addbrowserData ("jsonNew/osx-Vivaldi-1.12.955.38-Native.json", "osxVivaldi112Native");
	addbrowserData ("jsonNew/osx-Vivaldi-1.12.955.38-JS.json", "osxVivaldi112JS");
    
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