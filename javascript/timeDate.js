function getDate() {
    today = new Date();
    date =  "" + (today.getMonth() + 1) + " / " + today.getDate() + " / " + (today.getYear() - 100);
    document.getElementById("data").innerHTML = date;
}

var timerID = null;
var timerRunning = false;


function showTime() {
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();
    var timeValue = "" + ((hours > 12) ? hours -12 : hours);

    timeValue += ((minutes < 10) ? ":0" : ":") + minutes;
    timeValue += ((seconds < 10) ? ":0" : ":") + seconds;
    timeValue += ((hours >= 12) ? " P.M." : " A.M.");

    document.getElementById("zegarek").innerHTML = timeValue;
    timerID = setTimeout("showTime()", 1000);
    timerRunning = true;
}

function stopClock() {
    if (timerRunning)
        clearTimeout(timerID);
    timerRunning = false;
}

function startClock() {
    stopClock();
    getDate();
    showTime();
}
