var map;
function initMap() {
	var myLatLng = {lat: 62.440670, lng: 17.367777};

	var trainLat = document.getElementById("latTS");
	var trainLng = document.getElementById("lngTS");

	map = new google.maps.Map(document.getElementById('map'), {
	  center: myLatLng,
	  zoom: 4
	});

	var markerPos = new google.maps.Marker({
	  position: {lat: parseFloat(trainLat.innerHTML), lng: parseFloat(trainLng.innerHTML)},
	  map: map,
	  animation: google.maps.Animation.DROP,
	  icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
	});

};

