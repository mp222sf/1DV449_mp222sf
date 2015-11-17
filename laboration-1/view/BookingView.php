<?php
class BookingView {
	private static $url = 'startURL';
	private static $submit = 'startSubmit';
	private static $qsBookID = 'bookID';
	private static $qsFormAct = "formAct";
	private static $qsMovie = "movID";
	private static $qsDay = "dayID";
	private static $qsMovTime = "movTime";

	public function responseStart() {

		return $this->generateStartHTML();
	}

	public function responseNoInput() {

		return $this->generateStartHTML() . '<p>Ange en korrekt url.</p>';
	}

	public function responsePressedStart($movies, $links) {

		return $this->generateOutput($movies, $links);
	}

	public function responseReservations($bookings, $links)
	{
		$day = $this->getQSDay();
		$movID = $this->getQSMovie();
		$movTime = substr($this->getQSMovieTime(), 0, 2);
		$retStr = '';

		foreach ($bookings->getBookings() as $bkn)
		{
			if ($bkn->getDay() == $day && $bkn->getTimeStart() >= (int)($movTime + 2)) 
			{
				$retStr .= '<li>Det finns ett bord ledigt mellan klockan ' . $bkn->getTimeStart() . ' och ' . $bkn->getTimeEnd() .
				' efter att ha sett filmen ' . $this->getMovieName($movID) . ' klockan ' . $movTime . 
				'. <a href="?' . self::$qsBookID . '=' . $bkn->getCode() . '">Boka detta bord</a></li>';
			}
		}

		if (strlen($retStr) > 0)
		{
			return '<ul>' . $retStr . '</ul>';
		}
		return 'Det finns tyvärr inga lediga bord efter att ha sett filmen.';
	}

	public function responseBooking($data)
	{
		return $data;
	}


	private function generateStartHTML() {
		return '
			<form  method="post" >
				<p>Ange url: </p>
				<input type="text" id="' . self::$url . '" name="' . self::$url . '" />
				<input type="submit" name="' . self::$submit . '" value="Starta"/>
			</form>
		';
	}

	private function generateOutput($movies, $links)
	{
		if ($movies != null && count($movies->getAvailableMovies()) > 0)
		{
			$retStr = '<br>';
			foreach ($movies->getAvailableMovies() as $mov)
			{
				$retStr .= 'Filmen ' . $this->getMovieName($mov->getMovieID()) . ' klockan ' . $mov->getTime() . ' på ' 
				. $this->getDay($mov->getDate()) . ' <a href="?' . self::$qsDay . '=' . $mov->getDate() . '&' . self::$qsMovie . '=' 
				. $mov->getMovieID() . '&' . self::$qsMovTime . '=' . $mov->getTime() . '">Välj denna och boka bord</a><br>';
			}
			return $retStr;
		}
		if ($movies != null && count($movies->getAvailableMovies()) == 0)
		{
			return '<p>Inga filmer tillgängliga denna helg.</p>';
		}
		return '
			<p>Ange en korrekt url.</p>
		';
	}

	
	
	public function pressedStart()
	{
		return isset($_POST[self::$submit]);
	}

	public function getQSMovie()
	{
		if (isset($_GET[self::$qsMovie]))
		{
			return $_GET[self::$qsMovie];
		}
		return null;
	}

	public function getQSDay()
	{
		if (isset($_GET[self::$qsDay]))
		{
			return $_GET[self::$qsDay];
		}
		return null;
	}

	public function getQSMovieTime()
	{
		if (isset($_GET[self::$qsMovTime]))
		{
			return $_GET[self::$qsMovTime];
		}
		return null;
	}

	public function getQSBookID()
	{
		if (isset($_GET[self::$qsBookID]))
		{
			return $_GET[self::$qsBookID];
		}
		return null;
	}

	public function getQSFormAct()
	{
		if (isset($_GET[self::$qsFormAct]))
		{
			return $_GET[self::$qsFormAct];
		}
		return null;
	}


	public function getURL()
	{
		if (substr($_POST[self::$url], strlen($_POST[self::$url]) - 1) == '/')
		{
			return substr($_POST[self::$url], 0, strlen($_POST[self::$url]) - 1);
		}
		return $_POST[self::$url];
	}

	private function getDay($date)
	{
		if ($date == 1)
		{
			return 'fredag';
		}
		if ($date == 2)
		{
			return 'lördag';
		}
		return 'söndag';
	}

	private function getMovieName($movID)
	{
		if ($movID == 1)
		{
			return 'Söderkåkar';
		}
		if ($movID == 2)
		{
			return 'Fabian Bom';
		}
		return 'Pensionat Paradiset';
	}

	private function getAnchorLink($movie, $bookings)
	{
		foreach ($bookings->getBookings() as $booking)
		{
			if ($movie->getDate() == $booking->getDay() && ((int)substr($movie->getTime(), 0, 2) + 2) == $booking->getTimeStart())
			{
				return $booking->getCode();
			}
		}
		return "notAvailable";
	}

}