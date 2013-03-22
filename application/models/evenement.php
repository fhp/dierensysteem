<?php

class Evenement extends Eloquent {
	public static $table = "evenementen";
	public static $timestamps = true;

	public function get_datum()
	{
		$dag = array("Zondag", "Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vrijdag", "Zaterdag");
		$time = strtotime($this->get_attribute('datum'));
		
		return $dag[date("w", $time)] . " " . date('d-m-Y', $time);
	}
}
