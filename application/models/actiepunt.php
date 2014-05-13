<?php

class Actiepunt extends Eloquent {
	public static $table = "actiepunten";
	public static $timestamps = true;

	public function agendapunt()
	{
		return $this->belongs_to('Agendapunt');
	}
	
	public function gebruiker()
	{
		return $this->belongs_to('Gebruiker');
	}
	
	public function get_deadline()
	{
		if($this->get_attribute('deadline') === null) {
			return null;
		}
		$dag = array("Zondag", "Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vrijdag", "Zaterdag");
		$time = strtotime($this->get_attribute('deadline'));
		
		return $dag[date("w", $time)] . " " . date('d-m-Y', $time);
	}

	public function get_datum()
	{
		$dag = array("Zondag", "Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vrijdag", "Zaterdag");
		$time = strtotime($this->get_attribute('created_at'));
		
		return $dag[date("w", $time)] . " " . date('d-m-Y', $time);
	}
}
