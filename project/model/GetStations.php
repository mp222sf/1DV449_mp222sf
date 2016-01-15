<?php

require_once("model/Station.php");
require_once("model/Transfer.php");

class GetStations {

	private $stations = array();
	private $foundStations = array();
	private $validSearch = false;
	private $jsonStations;
	
	public function __construct()
	{
		$this->getAllStations();
	}

	// Hämtar alla stationer som finns på Tågtiders API.
	// Cachar stationerna.
	// Returnerar void.
	public function getAllStations()
	{
		// Uppgifter om API:et.
		$username = 'tagtider';
		$password = 'codemocracy';
		$URL='http://api.tagtider.net/v1/stations.json';

		// Om stationerna redan finns cachade. 
		// Annars hämtas stationerna på nytt.
		if (time() - (int)file_get_contents('cacheStationTime.txt') < 86400)
		{
			$this->stations = unserialize(file_get_contents('cacheStation.txt'));
		}
		else {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $URL);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
			$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$result=curl_exec ($ch);
			curl_close ($ch);

			$data = json_decode($result, true)['stations']['station'];

			$this->jsonStations = $result;

			if (count($data) > 0)
			{
				foreach ($data as $da) {
					array_push($this->stations, new Station($da));
				}
			}
			else {
				throw new stationsException("Det gick inte att hämta tågstationer från API:et.");
			}
			
			if (count($this->stations) > 0)
			{
				$seriData = serialize($this->stations);
				file_put_contents('cacheStation.txt', $seriData);
				file_put_contents('cacheStationTime.txt', time());
			}
		}
	}

	// Hämtar en lista med stationer, filtrerat via ett sökord som använderen skickar med.
	// Returnerar en array med Station-objekt.
	public function findStation($searchWord)
	{
		if (count($this->stations) > 0 && $this->validation($searchWord))
		{
			foreach ($this->stations as $stat) {
				if (preg_match("/" . $searchWord . "/i", $stat->getName())) {
				    array_push($this->foundStations, $stat);
				}
			}

			$this->validSearch = true;
		}
	}

	// Hämtar ett specifikt Station-objekt.
	// Parameter "id" : Ett ID för en tågstation.
	// Returnerar ett Station-objekt, eller null om ingen station hittas.
	public function getStationById($id)
	{
		if (count($this->stations) > 0)
		{
			foreach ($this->stations as $stat) {
				if ($id == $stat->getId()) {
				    return $stat;
				}
			}
		}
		return null;
	}

	// Hämtar avgångar för en specifik station. 
	// Parameter "id" : Ett ID för en tågstation.
	// Returnerar en array med Transfer-objekt.
	public function getTransfersByStationID($id)
	{
		$URL = "http://api.tagtider.net/v1/stations/" . $id . "/transfers/departures.json";
		$username = "tagtider";
		$password = "codemocracy";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
		$result=curl_exec ($ch);
		curl_close ($ch);

		$data = json_decode($result, true)['station']['transfers']['transfer'];

		$dataArray = array();

		if (count($data) > 0)
		{
			foreach ($data as $da) {
				array_push($dataArray, new Transfer($da));
			}
		}
		else {
			throw new transferException("Det gick inte att hämta avgångar från denna station.");
		}

		return $dataArray;
	}

	// Validerar sökordet som användaren angett.
	private function validation($searchWord)
	{
		if(preg_match("/^[a-zA-Z0-9éÉüÜåÅäÄöÖ\.\,\-\s]+$/", $searchWord)) {
		    return true;
		} else {
		    return false;
		}
	}

	// Returnerar resultatet av valideringen.
	public function getValidSearch()
	{
		return $this->validSearch;
	}

	// Returnerar alla stationer.
	public function getStations()
	{
		return $this->stations;
	}

	// Returnerar en specifik tågstation.
	public function getFoundStations()
	{
		return $this->foundStations;
	}

	// Returnerar JSON.
	public function getJSON()
	{
		return $this->jsonStations;
	}
}

