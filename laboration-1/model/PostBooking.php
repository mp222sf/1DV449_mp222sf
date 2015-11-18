<?php 

class PostBooking {

	private $bookCode;
	private $data;
	private $links;

	public function __construct($code, $links)
	{
		$this->links = $links;
		$this->bookCode = $code;
	}

	public function doFormAction()
	{
		$this->curlPostRequest($this->links->getBase() . $this->links->getFormAction());
	}

	// Gör en Curl-request (POST).
	function curlPostRequest($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "mattias.pavic@student.lnu.se");

		$postStr = 'group1=' . $this->bookCode . '&username=zeke&password=coys&submit=login';

		curl_setopt($ch, CURLOPT_POSTFIELDS, $postStr);
		$data = curl_exec($ch);
		curl_close($ch);
		
		$this->data = $data;
	}

	

	// Returnerar alla personer.
	public function getData()
	{
		return $this->data;
	}
}