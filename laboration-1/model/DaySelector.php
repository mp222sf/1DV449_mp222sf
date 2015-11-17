<?php

class DaySelector {
	private $humansArr;

	public function __construct($h)
	{
		$this->humansArr = $h;
	}

	public function getAvailableDays()
	{
		$friday = true;
		$saturday = true;
		$sunday = true;

		foreach ($this->humansArr as $human)
		{
			if (strcasecmp($human->getCalendar()->getDayFromCalendar(0), 'ok'))
			{
				$friday = false;
			}

			if (strcasecmp($human->getCalendar()->getDayFromCalendar(1), 'ok'))
			{
				$saturday = false;
			}

			if (strcasecmp($human->getCalendar()->getDayFromCalendar(2), 'ok'))
			{
				$sunday = false;
			}
		}

		return array($friday, $saturday, $sunday);
	}
}