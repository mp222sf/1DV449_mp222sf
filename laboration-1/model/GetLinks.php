<?php 

class GetLinks {
	private $baseURL;
	private $calendarURL;
	private $cinemaURL;
	private $restaurantURL;
	private $humansURL = array();
	private $cinemaQSCheck;
	private $cinemaQSDay;
	private $cinemaQSMovie;
	private $formActionURL;
	private $daysArr = array();
	private $moviesArr = array();

	public function __construct($startURL)
	{
		$this->baseURL = $startURL;
		$this->getMenuLinks();
		$this->getHumansNames();
		$this->getQSDayAndMovie();
		$this->getPostAction();
	}

	// Hämtar länkarna till alla menyval på startsidan.
	private function getMenuLinks()
	{
		// Hämtar URL för Menyval.
		$dom = new DomDocument();
		$startSiteData = $this->curlGetRequest($this->baseURL);

		if (strlen($startSiteData) > 0 && $dom->loadHTML($startSiteData))
		{
			$xpath = new DOMXPath($dom);
			$anchorCalendar = $xpath->query('//ol//li/a');

			foreach($anchorCalendar as $ac)
			{
				if ($ac->nodeValue == 'Kalendrar')
				{
					$this->calendarURL = $ac->getAttribute('href');
				}
				if ($ac->nodeValue == 'Stadens biograf!')
				{
					$this->cinemaURL = $ac->getAttribute('href');
				}
				if ($ac->nodeValue == 'Zekes restaurang!')
				{
					$this->restaurantURL = $ac->getAttribute('href');
				}
			}
		}
	}

	// Hämtar länkarna till alla personerna i Kalendern.
	// Sparas i "humansURL".
	private function getHumansNames()
	{
		// Hämtar URL för alla personer i Kalendern.
		$dom = new DomDocument();
		$reqURL = $this->baseURL .  $this->calendarURL . '/';
		$calendarSiteData = $this->curlGetRequest($reqURL);

		if (strlen($calendarSiteData) > 0 && $dom->loadHTML($calendarSiteData))
		{
			$xpath = new DOMXPath($dom);
			$names = $xpath->query('//ul//li/a');

			foreach($names as $name)
			{
				if (strlen($name->nodeValue) > 0)
				{
					array_push($this->humansURL, $name->getAttribute('href'));
				}
			}
		}
	}

	// Hämtar ut strängar från "Cinema". 
	// "cinemaQSDay", "cinemaQSMovie" och "cinemaQSCheck" är Query Strings som behövs.
	// "daysArr" är en samling med alla dagar.
	// "moviesArr" är en samling med alla filmer.
	private function getQSDayAndMovie()
	{
		$dom = new DomDocument();
		$reqURL = $this->baseURL .  $this->cinemaURL . '/';
		
		$cinemaSiteData = $this->curlGetRequest($reqURL);
		if (strlen($cinemaSiteData) > 0 && $dom->loadHTML($cinemaSiteData))
		{
			$xpath = new DOMXPath($dom);
			$selectDayNames = $xpath->query('//form[@action = "cinema/day"]//div/select');

			foreach($selectDayNames as $sdn)
			{
				if ($this->cinemaQSDay == null)
				{
					$this->cinemaQSDay = $sdn->getAttribute('name');
				}
			}

			$selectMovieNames = $xpath->query('//form[@action = "cinema/movie"]/select');

			foreach($selectMovieNames as $smn)
			{
				if ($this->cinemaQSMovie == null)
				{
					$this->cinemaQSMovie = $smn->getAttribute('name');
				}
			}

			$selectDayOptions = $xpath->query('//form[@action = "cinema/day"]//div//select//option');

			foreach($selectDayOptions as $sdo)
			{
				if (strlen($sdo->getAttribute('value')) > 0) {
					array_push($this->daysArr, $sdo->getAttribute('value'));
				}
			}

			$selectMovieOptions = $xpath->query('//form[@action = "cinema/movie"]//select/option');

			foreach($selectMovieOptions as $smo)
			{
				if (strlen($smo->getAttribute('value')) > 0) {
					array_push($this->moviesArr, $smo->getAttribute('value'));
				}
			}

			$selectCheckName = $xpath->query('//div[@class = "center"]/button');

			foreach($selectCheckName as $scn)
			{
				if ($this->cinemaQSCheck == null)
				{
					$this->cinemaQSCheck = $scn->getAttribute('id');
				}
			}
		}
	}

	private function getPostAction()
	{
		// Hämtar URL för alla personer i Kalendern.
		$dom = new DomDocument();
		$reqURL = $this->baseURL .  $this->restaurantURL . '/';
		$restaurantSiteData = $this->curlGetRequest($reqURL);

		if (strlen($restaurantSiteData) > 0 && $dom->loadHTML($restaurantSiteData))
		{
			$xpath = new DOMXPath($dom);
			$forms = $xpath->query('//body/form');

			foreach($forms as $form)
			{
				$this->formActionURL = $form->getAttribute('action');
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

	private function curlGetRequestXML($url)
	{

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));

		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

	public function getBase()
	{
		return $this->baseURL;
	}

	public function getCalendar()
	{
		return $this->calendarURL;
	}

	public function getCinema()
	{
		return $this->cinemaURL;
	}

	public function getRestaurant()
	{
		return $this->restaurantURL;
	}

	public function getHumans()
	{
		return $this->humansURL;
	}

	public function getQSCheck()
	{
		return $this->cinemaQSCheck;
	}

	public function getQSDay()
	{
		return $this->cinemaQSDay;
	}

	public function getQSMovie()
	{
		return $this->cinemaQSMovie;
	}

	public function getDaysArray()
	{
		return $this->daysArr;
	}

	public function getMoviesArray()
	{
		return $this->moviesArr;
	}

	public function getFormAction()
	{
		return $this->formActionURL;
	}
}