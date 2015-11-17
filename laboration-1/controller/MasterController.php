<?php
require_once("view/BookingView.php");
require_once("model/GetHumans.php");
require_once("model/GetMovies.php");
require_once("model/Human.php");
require_once("model/DaySelector.php");
require_once("model/GetLinks.php");
require_once("model/GetBookings.php");
require_once("model/Booking.php");
require_once("model/PostBooking.php");
require_once("model/SessionHandler.php");

class MasterController {

	private $bookView;
	private $bookings;
	private $postBooking;
	private $humans;
	private $humansArray;
	private $movies;
	private $moviesArray;
	private $responseHTML;
	private $daySelector;
	private $links;
	private $sessHandler;

	private static $qsLinks = 'links';
	private static $qsMovies = 'movies';


	public function __construct() {
		$this->bookView = new BookingView();
		$this->sessHandler = new SessHandler();
	}

	public function start()
	{
		// Trycker användaren på "start" och URL-längden är större än 0.
		if ($this->bookView->pressedStart() && strlen($this->bookView->getURL()) > 0)
		{
			// Hämtar majoriteten av alla länkar som kommer behövas i applikationen.
			// Sparas i en session.
			// Param: "Bas-url" som användaren skrivit in.
			$this->links = new GetLinks($this->bookView->getURL());
			$this->sessHandler->setSession(self::$qsLinks, $this->links);

			// Skapar personer med deras kalendrar.
			// Param: "GetLinks"-objekt, med alla länkar.
			$this->humans = new GetHumans($this->links);

			// Om det finns personer.
			// Jämför personernas kalendrar och tar reda på vilka dagar de kan ses.
			if ($this->humans->getHumans() != null)
			{
				$this->daySelector = new DaySelector($this->humans->getHumans());
				$this->movies = new GetMovies($this->daySelector, $this->links);
				$this->sessHandler->setSession(self::$qsMovies, $this->movies);
			}

			$this->responseHTML = $this->bookView->responsePressedStart($this->movies, $this->links);
		}
		// Trycker användaren på "start", och angiven länk är 0 tecken.
		else if ($this->bookView->pressedStart() && strlen($this->bookView->getURL()) == 0)
		{
			$this->responseHTML = $this->bookView->responseNoInput();
		}
		// Om en film är vald, visas vilka bord som är lediga.
		else if ($this->bookView->getQSMovie() != null && $this->bookView->getQSDay() != null && 
			$this->bookView->getQSMovieTime() != null)
		{
			if ($this->sessHandler->getSession(self::$qsLinks) != null)
			{
				$this->links = $this->sessHandler->getSession(self::$qsLinks);
			}

			// Skapar lediga bookningar på resturangen.
			$this->bookings = new GetBookings($this->links);

			$this->responseHTML = $this->bookView->responseReservations($this->bookings, $this->links);
		}
		// Om en bokning är vald, så bokar man ett bord.
		else if ($this->bookView->getQSBookID() != null)
		{
			if ($this->sessHandler->getSession(self::$qsLinks) != null)
			{
				$this->links = $this->sessHandler->getSession(self::$qsLinks);
			}

			$this->postBooking = new PostBooking($this->bookView->getQSBookID(), $this->links);
			$this->postBooking->doFormAction();
			$this->responseHTML = $this->bookView->responseBooking($this->postBooking->getData());
		}
		else {
			$this->responseHTML = $this->bookView->responseStart();
		}
	}

	public function getHTML()
	{
		return $this->responseHTML;
	}

}