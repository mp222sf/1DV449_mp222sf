<?php

class Weather {

	private $id;
	private $main; // eg. Snow
	private $description; // eg. Light snow
	private $temp;
	private $tempMin;
	private $tempMax;
	private $name;
	private $icon;
	
	public function __construct($obj)
	{
		$this->id = $obj["id"];
		$this->main = $obj["weather"][0]["main"];
		$this->description = $obj["weather"][0]["description"];
		$this->temp = $obj["main"]["temp"];
		$this->tempMin = $obj["main"]["temp_min"];
		$this->tempMax = $obj["main"]["temp_max"];
		$this->name = $obj["name"];
		$this->icon = $obj["weather"][0]["icon"];
	}

	public function getId()
	{
		return $this->id;
	}

	public function getMain()
	{
		return $this->main;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function getTemp()
	{
		return $this->temp;
	}

	public function getTempMin()
	{
		return $this->tempMin;
	}

	public function getTempMax()
	{
		return $this->tempMax;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getIcon()
	{
		return $this->icon;
	}
}