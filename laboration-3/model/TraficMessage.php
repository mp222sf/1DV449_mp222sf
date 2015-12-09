<?php

class TraficMessage {

	private $id;
	private $priority;
	private $createddate;
	private $title;
	private $exactlocation;
	private $description;
	private $latitude;
	private $longitude;
	private $category;
	private $subcategory;
	
	public function __construct($obj)
	{
		$this->id = $obj["id"];
		$this->priority = $obj["priority"];
		$date = substr($obj["createddate"], 6, 10);
		$this->createddate = new DateTime("@$date"); 
		$this->title = $obj["title"];
		$this->exactlocation = $obj["exactlocation"];
		$this->description = $obj["description"];
		$this->latitude = $obj["latitude"];
		$this->longitude = $obj["longitude"];
		$this->category = $obj["category"];
		$this->subcategory = $obj["subcategory"];
	}

	public function getId()
	{
		return $this->id;
	}

	public function getPriority()
	{
		return $this->priority;
	}

	public function getCreatedDate()
	{
		return $this->createddate;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getExactlocation()
	{
		return $this->exactlocation;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function getLatitude()
	{
		return $this->latitude;
	}

	public function getLongitude()
	{
		return $this->longitude;
	}

	public function getCategory()
	{
		return $this->category;
	}

	public function getSubcategory()
	{
		return $this->subcategory;
	}
}