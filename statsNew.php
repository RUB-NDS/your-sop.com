<?php 
	include(__DIR__ . "/config.php");
?>
<!DOCTYPE html>
<html><head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Modeling the Same-Origin Policy for DOM Access with ABAC</title>
		<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
<?php
	include(__DIR__ . "/header.php");
?>
<hr>
<div style="display: flex">
	<span style="float: left;  width: 450px;"><button onclick="window.location='http://your-sop.com/index.php'" style="color:red">Your SOP</button></span> 
	<span><button onclick="unmark()">Show all</button> <button onclick="console.log('Calculating ...'); var rows = removeNA();mark();shownumbers(rows);console.log('... ready');">Only display differences</button> <button onclick="colorize()" title="Colorizing with red (no) , green (yes) and blue (partial)">Colorize</button> <button onclick="filter()">Do not display  yes*</button></span> 
</div>
<div style="center" id="numbers">
</div>
<hr>
<?php
$right ='<span title="Right" class="dots">&#9998;</span>';
$chromium = '<span title="Chromium" class="dots">CR</span>';
$opera = '<span title="Opera" class="dots">OP</span>';
$safari = '<span title="Safari" class="dots">SA</span>';
$comodoDragon = '<span title="Comodo Dragon" class="dots">CD</span>';
$msedge = '<span title="Microsoft Edge" class="dots">ME</span>';
$brave = '<span title="Brave" class="dots">BR</span>';
$yandex = '<span title="Yandex" class="dots">YD</span>';
$vivaldi = '<span title="Vivaldi" class="dots">VV</span>';
?>
<table id="tableSOP" class="bordered">
  <tr>
    <th class="centering">FROM</th>
	<th>EE</th>
	<th class="centering">TO</th>
	<th>DETAILS</th>
	<th class="centering"><?=$right?></th>
	<th><span title="Recommendation based on majority" class="dots">≥</span></th>
	<th><span title="Linux" class="dots">🐧</span> <span title="Google Chrome" style="dots">GC</span> 61 Native</th>
	<th><span title="Linux" class="dots">🐧</span> <span title="Google Chrome" class="dots">GC</span> 61 JS</th>
	<th><span title="Windows" class="dots"><i>⊞</i></span> <span title="Google Chrome" class="dots">GC</span> 61 Native</th>
	<th><span title="Windows" class="dots"><i>⊞</i></span> <span title="Google Chrome" class="dots">GC</span> 61 JS</th>
	<th><span title="Linux" class="dots">🐧</span> <span title="Firefox" class="dots">FF</span> 55 Native</th>
	<th><span title="Linux" class="dots">🐧</span> <span title="Firefox" class="dots">FF</span> 55 JS</th>
	<th><span title="Linux" class="dots">🐧</span> <?=$chromium?> 61 Native</th>
	<th><span title="Linux" class="dots">🐧</span> <?=$chromium?> 61 JS</th>
	<th><span title="Windows" class="dots"><i>⊞</i></span> <span title="Firefox" class="dots">FF</span> 55 Native</th>
  	<th><span title="Windows" class="dots"><i>⊞</i></span> <span title="Firefox" class="dots">FF</span> 55 JS</th>
  	<th><span title="Windows" class="dots"><i>⊞</i></span> <?=$opera?> 47 Native</th>
  	<th><span title="Windows" class="dots"><i>⊞</i></span> <?=$opera?> 47 JS</th>
  	<th><span title="Windows" class="dots"><i>⊞</i></span> <?=$brave?> 0.18.36 Native</th>
  	<th><span title="Windows" class="dots"><i>⊞</i></span> <?=$brave?> 0.18.36 JS</th>
  	<th><span title="Windows" class="dots"><i>⊞</i></span> <?=$msedge?> 40 Native</th>
  	<th><span title="Windows" class="dots"><i>⊞</i></span> <?=$msedge?> 40 JS</th>
  	<th><span title="Windows" class="dots"><i>⊞</i></span> <?=$comodoDragon?> 58 Native</th>
  	<th><span title="Windows" class="dots"><i>⊞</i></span> <?=$comodoDragon?> 58 JS</th>
  	<th><span title="macOS" class="dots">🍏</span> <?=$safari?> 11.0 Native</th>
  	<th><span title="macOS" class="dots">🍏</span> <?=$safari?> 11.0 JS</th>
  	<th><span title="iOS" class="dots">iOS</span> <?=$safari?> 11.0 Native</th>
  	<th><span title="iOS" class="dots">iOS</span> <?=$safari?> 11.0 JS</th>
  	<th><span title="macOS" class="dots">🍏</span> <?=$yandex?> 17.9 Native</th>
  	<th><span title="macOS" class="dots">🍏</span> <?=$yandex?> 17.9 JS</th>
  	<th><span title="macOS" class="dots">🍏</span> <?=$vivaldi?> 1.12 Native</th>
  	<th><span title="macOS" class="dots">🍏</span> <?=$vivaldi?> 1.12 JS</th>
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

    $.getJSON("jsonNew/linux-Chrome-61.0-js.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "linuxGC61JS";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/windows-Chrome-61.0-native.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "windowsGC61Native";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/windows-Chrome-61.0-js.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "windowsGC61JS";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/linux-Firefox-55.0-native.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "linuxFF55Native";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/linux-Firefox-55.0-js.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "linuxFF55JS";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/linux-Chromium-61-native.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "linuxChromium61Native";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/linux-Chromium-61-js.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "linuxChromium61JS";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/windows-Firefox-55.0-native.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "windowsFF55Native";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/windows-Firefox-55.0-js.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "windowsFF55JS";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/windows-Opera-47.0-native.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "windowsOpera47Native";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/windows-Opera-47.0-js.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "windowsOpera47JS";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/windows-Brave-0.18.36-native.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "windowsBrave018Native";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/windows-Brave-0.18.36-js.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "windowsBrave018JS";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/windows-Edge-40-native.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "windowsEdge40Native";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/windows-Edge-40-js.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "windowsEdge40JS";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/windows-ComodoDragon-58-native.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "windowsComodoDragon58Native";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });


    $.getJSON("jsonNew/windows-ComodoDragon-58-js.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "windowsComodoDragon58JS";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/osx-Safari-11.0-native.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "osxSafari11Native";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/osx-Safari-11.0-js.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "osxSafari11JS";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/ios-Safari-11.0-native.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "iosSafari11Native";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/ios-Safari-11.0-js.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "iosSafari11JS";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/osx-Yandex-17.9.1.888-native.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "osxYandex17Native";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/osx-Yandex-17.9.1.888-js.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "osxYandex17JS";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

    $.getJSON("jsonNew/osx-Vivaldi-1.12.955.38-Native.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "osxVivaldi112Native";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });
    
    $.getJSON("jsonNew/osx-Vivaldi-1.12.955.38-JS.json", function(data) {
        $.each(data, function(testcase, value) {
            var testcase_browser = testcase + "osxVivaldi112JS";
            document.getElementById(testcase_browser).textContent += replaceterms(testcase, value.result);
        });
    });

}); /* make the same results invisible for the user */

function mark() {
    console.log("Making same entries invisible to the user.");
    var trRow;
    for (var i = 1; i < document.querySelectorAll("tr").length; ++i) {
        trRow = document.querySelectorAll("tr")[i];
        if (
        trRow.querySelectorAll("td")[6].textContent === trRow.querySelectorAll("td")[7].textContent && trRow.querySelectorAll("td")[7].textContent === trRow.querySelectorAll("td")[8].textContent && trRow.querySelectorAll("td")[8].textContent === trRow.querySelectorAll("td")[9].textContent && trRow.querySelectorAll("td")[9].textContent === trRow.querySelectorAll("td")[10].textContent && trRow.querySelectorAll("td")[10].textContent === trRow.querySelectorAll("td")[11].textContent && trRow.querySelectorAll("td")[11].textContent === trRow.querySelectorAll("td")[12].textContent && trRow.querySelectorAll("td")[12].textContent === trRow.querySelectorAll("td")[13].textContent && trRow.querySelectorAll("td")[13].textContent === trRow.querySelectorAll("td")[14].textContent && trRow.querySelectorAll("td")[14].textContent === trRow.querySelectorAll("td")[15].textContent && trRow.querySelectorAll("td")[15].textContent === trRow.querySelectorAll("td")[16].textContent && trRow.querySelectorAll("td")[16].textContent === trRow.querySelectorAll("td")[17].textContent && trRow.querySelectorAll("td")[17].textContent === trRow.querySelectorAll("td")[18].textContent && trRow.querySelectorAll("td")[18].textContent === trRow.querySelectorAll("td")[19].textContent && trRow.querySelectorAll("td")[19].textContent === trRow.querySelectorAll("td")[20].textContent && trRow.querySelectorAll("td")[20].textContent === trRow.querySelectorAll("td")[21].textContent && trRow.querySelectorAll("td")[21].textContent === trRow.querySelectorAll("td")[22].textContent && trRow.querySelectorAll("td")[22].textContent === trRow.querySelectorAll("td")[23].textContent && trRow.querySelectorAll("td")[23].textContent === trRow.querySelectorAll("td")[24].textContent && trRow.querySelectorAll("td")[24].textContent === trRow.querySelectorAll("td")[25].textContent && trRow.querySelectorAll("td")[25].textContent === trRow.querySelectorAll("td")[26].textContent && trRow.querySelectorAll("td")[26].textContent === trRow.querySelectorAll("td")[27].textContent && trRow.querySelectorAll("td")[27].textContent === trRow.querySelectorAll("td")[28].textContent && trRow.querySelectorAll("td")[28].textContent === trRow.querySelectorAll("td")[29].textContent && trRow.querySelectorAll("td")[29].textContent === trRow.querySelectorAll("td")[30].textContent && trRow.querySelectorAll("td")[30].textContent === trRow.querySelectorAll("td")[31].textContent

        ) {
            trRow.setAttribute("class", "displaysettings");
        }
    }
}

function colorize() {
    var cells = document.querySelectorAll("td");
    for (var i = 1; i < document.querySelectorAll("td").length; ++i) {
        if (cells[i].textContent.indexOf("yes") === 0) {
            cells[i].style.backgroundColor = "green";
        } else if (cells[i].textContent.indexOf("partial") === 0) {
            cells[i].style.backgroundColor = "RoyalBlue";
        } else if (cells[i].textContent.indexOf("no") === 0) {
            cells[i].style.backgroundColor = "OrangeRed";
        }
    }
}

function unmark() {
    location.reload();
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
            if (typeof trSelection[i].querySelectorAll("td")[x] !== 'undefined') {
                if (document.querySelectorAll("tr")[i].querySelectorAll("td")[x].textContent === "n.a.") {
                    document.querySelectorAll("tr")[i].remove();
                }
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