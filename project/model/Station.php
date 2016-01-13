<?php

class Station {

	private $id;
	private $name;
	private $code;
	private $slug;
	private $lat;
	private $lng;
	
	public function __construct($obj)
	{
		$this->id = $obj["id"];
		$this->name = $obj["name"];
		$this->code = $obj["code"];
		$this->slug = $obj["slug"];
		$this->lat = $obj["lat"];
		$this->lng = $obj["lng"];
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getCode()
	{
		return $this->code;
	}

	public function getSlug()
	{
		return $this->slug;
	}

	public function getLat()
	{
		return $this->lat;
	}

	public function getLng()
	{
		return $this->lng;
	}
}