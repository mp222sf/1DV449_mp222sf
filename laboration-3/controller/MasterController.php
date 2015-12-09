<?php

require_once("model/GetTraficMessages.php");

class MasterController {

	private $traficMessages;
	private $traficHTML;

	public function Run()
	{
		$this->traficMessages = new GetTraficMessages();
		$this->traficHTML = $this->traficMessages->getMessages();

	}

	public function getHTML()
	{
		return $this->traficHTML;
	}
}

