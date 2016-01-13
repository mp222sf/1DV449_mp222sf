<?php

require_once("model/GetStations.php");
require_once("model/GetTemperature.php");
require_once("model/Exceptions.php");

class MasterController {

	private $stations;
	private $stationSearchHTML;
	private $weatherStation;

	public function Run()
	{
		try {
			// Hämtar alla stationer.
			$this->stations = new GetStations();

			// Om användaren klickat på sök samtidigt som textfältet innehåller tecken,
			// så ges en lista med olika stationer.
			// Sökordet valideras.
			if ($this->getQSSearch() != null && $this->validation($this->getQSSearch()))
			{
				$this->stations->findStation($this->getQSSearch());
			}

			// Om en id-Querystring finns så visas station och väder för detta tågstationsID:t.
			if ($this->getQSId() != null)
			{
				$stat = $this->stations->getStationById($this->getQSId());
				$this->weatherStation = new GetTemperature($stat->getLat(), $stat->getLng()); 
			}
		}
		catch(stationsException $e) {
			echo $e->getMessage();
		}
		catch(transferException $e) {
			echo $e->getMessage();
		}
		catch(weatherException $e) {
			echo $e->getMessage();
		}
	}

	// Returnerar HTML för stationer.
	public function getStationsHTML()
	{
		return $this->stations;
	}

	// Returnerar HTML för väder.
	public function getWeatherHTML()
	{
		return $this->weatherStation;
	}

	// Kontrollerar om användaren tryckt på sök.
	private function didUserPressSearch()
	{
		return isset($_POST['searchButt']);
	}

	// // Hämtar input från sökfältet.
	// private function getSearchInput()
	// {
	// 	return $_POST['searchInp'];
	// }

	// Hämtar Querystring för "id".
	private function getQSId()
	{
		if (isset($_GET["id"]) && is_numeric($_GET["id"]))
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

	private function validation($searchWord)
	{
		if(preg_match("/^[a-zA-Z0-9éÉüÜåÅäÄöÖ.,-\s]+$/", $searchWord)) {
		    return true;
		} else {
		    return false;
		}
	}
}

