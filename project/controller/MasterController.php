<?php

require_once("model/GetStations.php");
require_once("model/GetTemperature.php");
require_once("model/Exceptions.php");

class MasterController {

	private $stations;
	private $stationSearchHTML;
	private $weatherStation;
	private $lv;

	public function __construct($layoutView)
	{
		$this->lv = $layoutView;
	}

	public function Run()
	{
		try {
			// Hämtar alla stationer.
			$this->stations = new GetStations();

			// Om användaren klickat på sök,
			// så ges en lista med olika stationer.
			// Sökordet valideras.
			if ($this->lv->getQSSearch() != null)
			{
				$this->stations->findStation($this->lv->getQSSearch());
			}

			// Om en id-Querystring finns så visas station och väder för detta tågstationsID:t.
			if ($this->lv->getQSId() != null)
			{
				$stat = $this->stations->getStationById($this->lv->getQSId());
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
}

