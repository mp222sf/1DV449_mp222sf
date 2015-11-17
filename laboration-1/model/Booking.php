<?php 

class Booking {
	private $day;
	private $timeStart;
	private $timeEnd;
	private $status;
	private $code;

	public function __construct($value)
	{
		$dayStr = substr($value, 0, 3);
		$timeStartStr = substr($value, 3, 2);
		$timeEndStr = substr($value, 5, 2);

		if ($dayStr == 'fre')
		{
			$this->day = 1;
		}
		else if ($dayStr == 'lor')
		{
			$this->day = 2;
		}
		else if ($dayStr == 'son')
		{
			$this->day = 3;
		}
		else
		{
			throw new Exception("Fel veckodagsformat.");
		}

		$this->timeStart = (int)$timeStartStr;
		$this->timeEnd = (int)$timeEndStr;
		$this->status = true;
		$this->code = $value;
	}

	public function getDay()
	{
		return $this->day;
	}

	public function getTimeStart()
	{
		return $this->timeStart;
	}

	public function getTimeEnd()
	{
		return $this->timeEnd;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function getCode()
	{
		return $this->code;
	}
}