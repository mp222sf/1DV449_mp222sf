<?php 

class SessHandler {

	public function setSession($sessName, $objectToSet)
	{
		$_SESSION[$sessName] = $objectToSet;
	}

	public function getSession($sessName)
	{
		if (isset($_SESSION[$sessName]))
		{
			return $_SESSION[$sessName];
		}
		return null;
	} 
}