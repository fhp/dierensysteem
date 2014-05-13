<?php

class Agendapunt extends Eloquent {
	public static $table = "agendapunten";
	public static $timestamps = true;

	public function gebruiker()
	{
		return $this->belongs_to('Gebruiker');
	}
	
	public function notulen()
	{
		return $this->has_many('Notule');
	}
	
	public function actiepunten()
	{
		return $this->has_many('Actiepunt');
	}
	
	public function get_datum()
	{
		$dag = array("Zondag", "Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vrijdag", "Zaterdag");
		$time = strtotime($this->get_attribute('created_at'));
		
		return $dag[date("w", $time)] . " " . date('d-m-Y', $time);
	}
}
