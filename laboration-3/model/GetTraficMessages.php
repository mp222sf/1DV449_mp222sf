<?php

require_once("model/TraficMessage.php");

class GetTraficMessages {

	private $messages = array();
	
	public function __construct()
	{
		if (time() - (int)file_get_contents('cacheTime.txt') < 300)
		{
			$jsonObj = unserialize(file_get_contents('cache.txt'));
		}
		else {
			$jsonObj = json_decode(file_get_contents('http://api.sr.se/api/v2/traffic/messages?format=json&pagination=false&sort=createddate&indent=true'), true);

			if (count($jsonObj["messages"]) > 0)
			{
				$data = serialize($jsonObj);
				file_put_contents('cache.txt', $data);
				file_put_contents('cacheTime.txt', time());
			}
		}

		if (count($jsonObj["messages"]) > 0)
		{
			foreach ($jsonObj["messages"] as $tm) {
				array_push($this->messages, new TraficMessage($tm));
			}
		}

		$this->filterCategory();
		$this->messageSort();
	}

	public function getMessages()
	{
		return $this->messages;
	}

	private function getQS()
	{
		if (isset($_GET["cat"]))
		{
			return $_GET["cat"];
		}
		return null;
	}

	private function filterCategory()
	{
		if ($this->getQS() != null && $this->getQS() >= 0 && $this->getQS() <= 3)
		{
			$cat = $this->getQS();
			$tempArray = array();

			foreach ($this->messages as $mess) {
				if ($mess->getCategory() == $cat)
				{
					array_push($tempArray, $mess);
				}
			}
			$this->messages = $tempArray;
		}
	}

	private function messageSort()
	{
		$tempArray = $this->messages;
		
		usort($tempArray, function($a, $b)
		{
		    return strcmp($b->getCreatedDate()->format("U"), $a->getCreatedDate()->format("U"));
		});

		$this->messages = $tempArray;
	}

}

