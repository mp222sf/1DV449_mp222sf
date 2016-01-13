var picButton = document.getElementById("pClick");
var searchButton = document.getElementById("searchButt");
var searchInput = document.getElementById("searchInp");
var allStations = document.getElementsByClassName("stationLink");
var connParagraph = document.getElementById("info");
var connInterval;

console.log(allStations);

searchButton.addEventListener("click", function(){

	if (doesConnectionExist())
	{
		window.location = "?search=" + searchInput.value;
	}
	else {
		connParagraph.innerHTML = "Connection lost. Please try again.";
		connParagraph.className = "blink_me";
		connInterval = setInterval(gotConnectionBack, 5000);
	}
	
	
});

for (i = 0; i < allStations.length; i++) {

    allStations[i].addEventListener("click", function(){

		if (doesConnectionExist())
		{
			for (j = 0; j < this.href.length; j++) {
				if (this.href.substr(j, 1) == "#")
				{
					console.log();
					window.location = "?" + this.href.substr(j + 1);
				}
			}
		}
		else {
			connParagraph.innerHTML = "Connection lost. Please try again.";
			connParagraph.className = "blink_me";
			connInterval = setInterval(gotConnectionBack, 5000);
		}
	});
}

searchInput.onkeypress = function(e){
	if (!e) e = window.event;
	var keyCode = e.keyCode || e.which;
	
	if (keyCode == '13'){
		if (doesConnectionExist())
		{
			window.location = "?search=" + searchInput.value;
		}
		else {
			connParagraph.innerHTML = "Connection lost. Please try again.";
			connParagraph.className = "blink_me";
			connInterval = setInterval(gotConnectionBack, 5000);
		}
	}
}


function doesConnectionExist() {
    var xhr = new XMLHttpRequest();
    var file = "http://maps.google.com/mapfiles/ms/icons/orange-dot.png";
    var randomNum = Math.round(Math.random() * 10000);
     
    xhr.open('HEAD', file + "?rand=" + randomNum, false);
     
    try {
        xhr.send();
         
        if (xhr.status >= 200 && xhr.status < 304) {
            return true;
        } else {
            return false;
        }
    } catch (e) {
        return false;
    }
}

function gotConnectionBack()
{
	if (doesConnectionExist())
	{
		clearInterval(connInterval);
		connParagraph.innerHTML = "";
	}
}
