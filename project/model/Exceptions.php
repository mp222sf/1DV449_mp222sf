<?php

// Felhantering av stationer.
class stationsException extends Exception {
	public function errorMessage() {
		//error message
		$errorMsg = 'Det gick inte att hämta alla tågstationer från API:et.';
		return $errorMsg;
	}
}

// Felhantering av avgångar.
class transferException extends Exception {
	public function errorMessage() {
		//error message
		$errorMsg = 'Det gick inte att hämta avgångar från denna station.';
		return $errorMsg;
	}
}

// Felhantering av väder.
class weatherException extends Exception {
	public function errorMessage() {
		//error message
		$errorMsg = 'Det gick inte hämta vädret för denna station.';
		return $errorMsg;
	}
}



