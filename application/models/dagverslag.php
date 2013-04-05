<?php

class Dagverslag extends Eloquent {
	public static $table = "dagverslagen";
	public static $timestamps = true;

	public function gebruiker()
	{
		return $this->belongs_to('Gebruiker');
	}

	public function get_datum()
	{
		$dag = array("Zondag", "Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vrijdag", "Zaterdag");
		$time = strtotime($this->get_attribute('datum'));
		
		return $dag[date("w", $time)] . " " . date('d-m-Y', $time);
	}
	
	public function get_datum_edit()
	{
		$time = strtotime($this->get_attribute('datum'));
		
		return date('d-m-Y', $time);
	}
}
