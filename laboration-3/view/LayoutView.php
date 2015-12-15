<?php

class LayoutView {

	public function render($trafic) {
		echo '<!DOCTYPE html>
				<html>
				<head>
					<meta charset="utf-8">
					<title>1DV449 - Laboration 3</title>
					<link rel="stylesheet" type="text/css" href="view/style.css?v1.0.0">
				</head>
				<body>

					<h1>1DV449 - Laboration 3</h1>
					<a href="index.php?cat=0" class="textMargin">Vägtrafik</a><a href="index.php?cat=1" class="textMargin">Kollektivtrafik</a><a href="index.php?cat=2" class="textMargin">Planerad störning</a><a href="index.php?cat=3" class="textMargin">Övrigt</a><a href="index.php">Alla kategorier</a>
					<div id="container">
					 	<div id="containerLeft">
							' . 
								$this->getTraficMessageList($trafic)
							. '
					 	</div>
					 	<div id="containerRight">
					 		<h2>Karta</h2>
						 	<div id="map">
						 		
						 	</div>
						 	<h3>Prioriteringar</h3>
						 	<img src="http://maps.google.com/mapfiles/ms/icons/red-dot.png" title="Mycket allvarlig händelse" alt="Mycket allvarlig händelse" />
						 	<img src="http://maps.google.com/mapfiles/ms/icons/orange-dot.png" title="Stor händelse" alt="Stor händelse" />
						 	<img src="http://maps.google.com/mapfiles/ms/icons/yellow-dot.png" title="Störning" alt="Störning" />
						 	<img src="http://maps.google.com/mapfiles/ms/icons/blue-dot.png" title="Mindre störning" alt="Mindre störning" />
						 	<img src="http://maps.google.com/mapfiles/ms/icons/green-dot.png" title="Information" alt="Information" />
						 </div>
					</div>
					
						' . $this->googleMaps($trafic) . '
					</body>
				</html>
		';
	}

	private function getTraficMessageList($trafic)
	{
		$returnString = "<h2>Trafikinformation";

		$qs = $this->getQS();

		if ($qs != null && $qs >= 0 && $qs <= 3)
		{
			$returnString .= " för " . $this->getCategoryName($qs);
		}

		$returnString .= "</h2>";

		if (count($trafic) > 0)
		{
			foreach ($trafic as $t) {
				$returnString .= '<p id="message' . $t->getId() . '">' . $t->getCreatedDate()->format('Y-m-d H:i') . ' - ' . $t->getTitle() . '</p>';
			}
		}
		else {
			$returnString .= '<i>Ingen trafikinformation tillgänglig för denna kategori.';
		}

		return $returnString;
	}

	private function googleMaps($trafic)
	{
		$markers = "";
		$counter = 0;

		foreach ($trafic as $t) {
			$markers .= 'var marker' . $t->getId() . ' = new google.maps.Marker({
				      position: {lat: ' . $t->getLatitude() . ', lng: ' . $t->getLongitude() . '},
				      map: map,
				      animation: google.maps.Animation.DROP,
				      icon: "http://maps.google.com/mapfiles/ms/icons/' . $this->getPriority($t->getPriority()) . '-dot.png",
				      title: "' . $t->getTitle() . '"
				    });

					marker' . $t->getId() . '.addListener("click", function() {
					    var test = window.open("", "TraficMessageInformation", "width=500, height=300");
					    test.document.body.innerHTML = "";
					    test.document.write("<h1>' . $t->getTitle() . '</h1><p><b>Datum:</b> ' . $t->getCreatedDate()->format('Y-m-d H:i') . '</p><p><b>Beskrivning:</b> ' . $this->removeEmptyEndChar($t->getDescription()) . '</p><p><b>Kategori:</b> ' . $this->getCategoryName($t->getCategory()) . '</p><p><b>Prioritet:</b> ' . $this->getPriorityName($t->getPriority()) . '</p>");
					  });

					document.getElementById("message' . $t->getId() . '").addEventListener("click", function(){
					    if (marker' . $t->getId() . '.getAnimation() !== null) {
						    marker' . $t->getId() . '.setAnimation(null);
						} else {
							' . $this->removeBounceFromAllMarkers($trafic) . '
							' . $this->removeActiveFromAllMessages($trafic) . '
							document.getElementById("message' . $t->getId() . '").className = "active";
							map.setZoom(6);
    						map.setCenter(marker' . $t->getId() . '.getPosition());
					    	marker' . $t->getId() . '.setAnimation(google.maps.Animation.BOUNCE);
						}
					});
					
					';
			$counter++;
		}

		return "<script type='text/javascript'>

				  var map;
				  function initMap() {
				    var myLatLng = {lat: 62.440670, lng: 17.367777};

				    map = new google.maps.Map(document.getElementById('map'), {
				      center: myLatLng,
				      zoom: 4
				    });

				    " . $markers . "
				  }

				</script>
				<script async defer
				  src='https://maps.googleapis.com/maps/api/js?key=AIzaSyBEVs3ATKsLGIejp2WDgZHGcjOg7C4UuhA&callback=initMap'>
				</script>";
	}

	private function removeBounceFromAllMarkers($trafic)
	{
		$returnString = "";
		foreach ($trafic as $t) {
			$returnString .= 'marker' . $t->getId() . '.setAnimation(null);';
		}

		return $returnString;
	}

	private function removeEmptyEndChar($description)
	{
		$toReturn = $description;
		if (substr($toReturn, count($toReturn) - 1, 1) == " ")
		{
			$toReturn = substr($toReturn, 0, count($toReturn) -2);
		}
		
		$toReturn = str_replace(array("\n"), '', $toReturn);

		return $toReturn;
	}

	private function removeActiveFromAllMessages($trafic)
	{
		$returnString = "";
		foreach ($trafic as $t) {
			$returnString .= 'document.getElementById("message' . $t->getId() . '").className = "";';
		}

		return $returnString;
	}

	private function getCategoryName($id)
	{
		if ($id == 0)
		{
			return "Vägtrafik";
		}
		if ($id == 1)
		{
			return "Kollektivtrafik";
		}
		if ($id == 2)
		{
			return "Planerad störning";
		}
		return "Övrigt";
	}

	private function getPriority($id)
	{
		if ($id == 1)
		{
			return "red";
		}
		if ($id == 2)
		{
			return "orange";
		}
		if ($id == 3)
		{
			return "yellow";
		}
		if ($id == 5)
		{
			return "blue";
		}
		return "green";
	}

	private function getPriorityName($id)
	{
		if ($id == 1)
		{
			return "Mycket allvarlig händelse";
		}
		if ($id == 2)
		{
			return "Stor händelse";
		}
		if ($id == 3)
		{
			return "Störning";
		}
		if ($id == 5)
		{
			return "Mindre störning";
		}
		return "Information";
	}

	

	private function getQS()
	{
		if (isset($_GET["cat"]))
		{
			return $_GET["cat"];
		}
		return null;
	}
}
