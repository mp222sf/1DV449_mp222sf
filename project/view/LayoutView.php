<?php

class LayoutView {

	private static $searchButton = "searchButt";
	private static $searchInput = "searchInp";

	public function render($stationsObj, $weatherObj) {

		echo '<!DOCTYPE html>
				<html>
					<head>
						<meta charset="utf-8">
						<title>1DV449 - Projekt</title>
						<link rel="stylesheet" type="text/css" href="view/style.css?v1.0.0">
					</head>
					<body>
						<p id="info"></p>
						<img id="logoPic" src="view/pics/trainWeather.png" />
						<div class="searchText">
							<p>Sök efter en tågstation:</p>
						</div>
						<div id="searchBox">
							<input id="' . self::$searchInput . '" name="' . self::$searchInput . '" type="text">
							<input type="submit" id="' . self::$searchButton . '" name="' . self::$searchButton . '" value="Sök" class="submit-button" />
						</div>
						<div id="container">
						 	' . $this->choosenStation($stationsObj, $weatherObj) . '
						</div>
						' . $this->googleMaps($stationsObj) . '
					</body>
					<script src="offline.js"></script>
				</html>
		';
	}

	private function stationSearch($foundStations)
	{
		$retstring = "";
		if (count($foundStations) > 0)
		{
			foreach ($foundStations as $fs) 
			{
				$retstring .= '<a class="stationLink" href="#id=' . $fs->getId() . '">' . $fs->getName() . '</a><br>';
			}
		}
		
		return $retstring;
	}

	private function choosenStation($stationsObj, $weatherObj)
	{
		try {
			if ($stationsObj->getFoundStations() == null && $this->getQSSearch() != null)
			{
				return '<div id="containerCenter">
			 				<h2>Fel</h2>
				 			<p>Din sökning innehåller tecken som inte är tillåtna</p>
				 		</div>';
			}
			if ($stationsObj->getFoundStations() != null && count($stationsObj->getFoundStations()) > 0)
			{
				return '<div id="containerCenter">
			 				<h2>Välj en station</h2>
				 			<p class="stationText">' . $this->stationSearch($stationsObj->getFoundStations()) . '</p>
				 		</div>';
			}
			if (is_array($stationsObj->getFoundStations()))
			{
				return '<div id="containerCenter">
				 			<h2>Sökresultat</h2>
					 		Din sökning gav 0 träffar.
					 	</div>';
			}
			if ($this->getQSId() != null)
			{
				if (!is_numeric($this->getQSId()))
				{
					header('Location: index.php');
				}

				$station = $stationsObj->getStationById($this->getQSId());

				return '<div id="containerLeft">
			 			<h2>Tågstation: ' . $station->getName() . '</h2>
			 			' . $this->viewTransfers($stationsObj->getTransfersByStationID($this->getQSId())) . '
				 	</div>
				 	<div id="containerRight">
				 		<div>
				 			<h2>Vädercentral: ' . $weatherObj->getWeather()->getName() . '</h2>
				 			<img src="view/pics/weatherIcons/' . $weatherObj->getWeather()->getIcon() . '.png" title="' . $weatherObj->getWeather()->getDescription() . '" /><br>
				 			Temp: ' . $weatherObj->getWeather()->getTemp() . '<br>
				 			Temp-Min: ' . $weatherObj->getWeather()->getTempMin() . '<br>
				 			Temp-Max: ' . $weatherObj->getWeather()->getTempMax() . '<br>	
				 		</div>
						<div id="map">

						</div>
					</div>';
			}

			return '';
		}
		catch(stationsException $e) {
			return '<div id="containerCenter">
			 			<h2>Något gick fel...</h2>
				 		' . $e->getMessage() . '
				 	</div>';
		}
		catch(transferException $e) {
			return '<div id="containerCenter">
			 			<h2>Något gick fel...</h2>
				 		' . $e->getMessage() . '
				 	</div>';
		}
		catch(weatherException $e) {
			return '<div id="containerCenter">
			 			<h2>Något gick fel...</h2>
				 		' . $e->getMessage() . '
				 	</div>';
		}
	}

	private function viewTransfers($transfers)
	{
		$retString = '<div class="transTrain bold">Tåg</div><div class="transDest bold">Destinationer</div><div class="transDep bold marginCenter">Avgång</div><br>';

		foreach ($transfers as $tran) 
		{
			if ($tran->getDeparture() != '0000-00-00 00:00:00')
			{
				$retString .= '<div class="transTrain">' . $tran->getTrain() . '</div><div class="transDest">';

				if (strlen($tran->getDestination()) > 0)
				{
					$retString .= $tran->getDestination();
				}
				else {
					$retString .= $tran->getOrigin();
				}

				$retString .= '</div><div class="transDep">' . substr($tran->getDeparture(), 11, 5) . '</div><br>';
			}
		}

		return $retString;
	}

	// Aktiverar Google-maps med en markör som har samma kordinater som stationen.
	private function googleMaps($statObj)
	{
		$markers = "";

		if ($this->getQSId() != null && is_numeric($this->getQSId()))
		{
			$stat = $statObj->getStationById($this->getQSId());

			$markers .= 'var markerPos = new google.maps.Marker({
				      position: {lat: ' . $stat->getLat() . ', lng: ' . $stat->getLng() . '},
				      map: map,
				      animation: google.maps.Animation.DROP,
				      icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
				    });
					';
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

	// Hämtar Querystring :: "id".
	private function getQSId()
	{
		if (isset($_GET["id"]))
		{
			return $_GET["id"];
		}
		return null;
	}

	// Hämtar Querystring för "search".
	private function getQSSearch()
	{
		if (isset($_GET["search"]) && is_string($_GET["search"]))
		{
			return $_GET["search"];
		}
		return null;
	}
}
