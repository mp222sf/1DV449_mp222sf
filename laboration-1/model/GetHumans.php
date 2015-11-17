<?php

class GetHumans {
	private $humans = array();
	private $links;

	public function __construct($links)
	{
		$this->links = $links;

		foreach ($this->links->getHumans() as $human)
		{
			$this->createHumans($human);
		}
	}

	// Skapar ett person-objekt utifrån hämtat data från en extern sida.
	private function createHumans($humanURL)
	{
		$dom = new DomDocument();
		$xpath = new DOMXPath($dom);

		$data = $this->curlGetRequest($this->links->getBase() . $this->links->getCalendar() . '/' . $humanURL);
		

		if (strlen($data) > 0 && $dom->loadHTML($data))
		{
			$xpath = new DOMXPath($dom);

			// Hämtar namn.
			$nameItem = $xpath->query('/html/body/h2');
			$name = "";
			foreach($nameItem as $n)
			{
				if (!strlen($name) > 0)
				{
					$name = $n->nodeValue;
				}
			}
			
			// Hämtar kalender.
			$items = $xpath->query('//table//tbody//tr/td');
			$calendarDays = array();
			foreach($items as $item)
			{
				array_push($calendarDays, $item->nodeValue);
			}

			// Skapar en ny Human.
			$newHuman = new Human($name, $calendarDays);
			$this->add($newHuman);
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

	// Lägger till en person i arrayen med personer.
	private function add($hum)
	{
		if ($hum instanceof Human)
		{
			array_push($this->humans, $hum);
		}
	}

	// Returnerar alla personer.
	public function getHumans()
	{
		return $this->humans;
	}
}