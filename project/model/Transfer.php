<?php

class Transfer {

	private $id;
	private $departure;
	private $newDeparture;
	private $destination;
	private $track;
	private $train;
	private $type;
	
	public function __construct($obj)
	{
		$this->id = $obj["id"];
		$this->departure = $obj["departure"];
		$this->newDeparture = $obj["newDeparture"];
		$this->destination = $obj["destination"];
		$this->track = $obj["track"];
		$this->train = $obj["train"];
		$this->type = $obj["type"];
	}

	public function getId()
	{
		return $this->id;
	}

	public function getDeparture()
	{
		return $this->departure;
	}

	public function getNewDeparture()
	{
		return $this->newDeparture;
	}
	
	public function getDestination()
	{
		return $this->destination;
	}

	public function getTrack()
	{
		return $this->track;
	}

	public function getTrain()
	{
		return $this->train;
	}

	public function getType()
	{
		return $this->type;
	}
}