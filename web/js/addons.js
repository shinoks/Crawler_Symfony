var milliseconds = new Date().getTime();
var sessionTime = {{ session.czas_sesji }}*1000
var remainingTimeS = Math.round(((sessionTime- milliseconds)/1000));
            
setInterval(function(){document.getElementById("remainingTime").innerHTML ='System wyloguje Ciê za '+remainingTimeS+' sekund '; remainingTimeS = remainingTimeS-1}, 1000);

