<?php
class Calendar {
	private $daysOpen = array();

	public function addToCalendar($day)
	{
		array_push($this->daysOpen, $day);
	}

	public function getDayFromCalendar($day)
	{
		return $this->daysOpen[$day];
	}
}