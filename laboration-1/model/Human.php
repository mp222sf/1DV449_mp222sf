<?php
require_once("model/Calendar.php");

class Human {
	private $name;
	private $calendar;

	public function __construct($myName, $do)
	{
		$this->name = $myName;
		$this->calendar = new Calendar();
		foreach ($do as $day) {
		    $this->calendar->addToCalendar($day);
		}
	}

	public function getName()
	{
		return $this->name;
	}

	public function getCalendar()
	{
		return $this->calendar;
	}
}