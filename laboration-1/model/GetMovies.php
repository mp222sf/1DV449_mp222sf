<?php 
require_once("model/Movie.php");

class GetMovies {
	private $movies = array();
	private $daySelector;
	private $links;

	public function __construct($dSelector, $links)
	{
		$this->daySelector = $dSelector;
		$this->links = $links;
		$this->createMovies();
	}

	// Skapar ett film-objekt utifrån hämtat data från en extern sida.
	private function createMovies()
	{
		// Går igenom tillgängliga dagar.
		foreach ($this->links->getDaysArray() as $day)
		{
			if ($day == '01')
			{
				$dayPre = 0;
			}
			else if ($day == '02')
			{
				$dayPre = 1;
			}
			else if ($day == '03')
			{
				$dayPre = 2;
			}
			else {
				throw new Exception("Inga lediga dagar.");
			}

			// Kontrollerar om vald dag är en lediga dag för alla personerna.
			if ($this->daySelector->getAvailableDays()[$dayPre])
			{
				$resArr = array();

				// Hämtar alla tillgängliga filmer för vald dag.
				foreach ($this->links->getMoviesArray() as $mov)
				{
					array_push($resArr, json_decode($this->curlGetRequest($this->links->getBase() . $this->links->getCinema() . '/' . $this->links->getQSCheck() . '?' . $this->links->getQSDay() . '=' . $day . '&' . $this->links->getQSMovie() . '=' . $mov)));
				}

				// Skapar ett film-objekt för varje film som inte är fullbokad.
				foreach ($resArr as $res)
				{
					foreach ($res as $m)
					{
						if ($m->status == 1)
						{
							array_push($this->movies, new Movie($m->movie, $m->time, true, ($dayPre + 1)));
						}
					}
				}
			}
		}
	}

	// Gör en Curl-request.
	private function curlGetRequest($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "mattias.pavic@student.lnu.se");

		$data = curl_exec($ch);
		curl_close($ch);

		return $data;
	}

	// Lägger till en film i arrayen med filmer.
	private function add($mov)
	{
		if ($mov instanceof Movie)
		{
			array_push($this->movies, $mov);
		}
	}

	// Returnerar alla filmer.
	public function getAvailableMovies()
	{
		return $this->movies;
	}
}