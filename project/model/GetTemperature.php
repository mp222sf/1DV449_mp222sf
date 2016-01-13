<?php 

require_once("model/Weather.php");

class GetTemperature {

	private $weatherStation;
	
	public function __construct($lat, $lng)
	{
		if (is_numeric($lat) && is_numeric($lng))
		{
			$this->fetchWeatherByLatLng($lat, $lng);
		}
		else {
			throw new weatherException("Det gick inte hämta vädret för denna station.");
		}
	}

	// Hämtar vädret på en viss position.
	// Parametrar "lat" & "lng" : Kordinater till den plats man vill hämta väderrapport från.
	// Returnerar void.
	private function fetchWeatherByLatLng($lat, $lng)
	{
		$URL='http://api.openweathermap.org/data/2.5/weather?lat=' . $lat . '&lon=' . $lng . '&units=metric&appid=fbf156d6e265b11cff72beb573f3b3a3';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
		$result=curl_exec ($ch);
		curl_close ($ch);

		$data = json_decode($result, true);

		$this->weatherStation = new Weather($data);
	}

	// Returnerar ett Weather-objekt.
	public function getWeather()
	{
		return $this->weatherStation;
	}
}