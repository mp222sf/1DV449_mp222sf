<?php

class GetBookings {
	private $links;
	private $bookingsArr = array();

	public function __construct($links)
	{
		$this->links = $links;
		$this->getRestaurantBookings();
	}

	private function getRestaurantBookings()
	{
		// Hämtar URL för alla personer i Kalendern.
		$dom = new DomDocument();
		$reqURL = $this->links->getBase() .  $this->links->getRestaurant() . '/';
		$restaurantSiteData = $this->curlGetRequest($reqURL);

		if ($dom->loadHTML($restaurantSiteData))
		{
			$xpath = new DOMXPath($dom);
			$bookings = $xpath->query('//form//div//p/input');

			foreach($bookings as $bkn)
			{
				array_push($this->bookingsArr, new Booking($bkn->getAttribute('value')));
			}
		}
	}

	// Gör en Curl-request.
	private function curlGetRequest($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

	

	// Returnerar alla personer.
	public function getBookings()
	{
		return $this->bookingsArr;
	}
}