<?php

class Movie {
	private $movieID;
	private $time;
	private $status;
	private $date;

	public function __construct($mID, $t, $s, $d)
	{
		$this->movieID = $mID;
		$this->time = $t;
		$this->status = $s;
		$this->date = $d;
	}

	public function getMovieID()
	{
		return $this->movieID;
	}

	public function getTime()
	{
		return $this->time;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function getDate()
	{
		return $this->date;
	}
}