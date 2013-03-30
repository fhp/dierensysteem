<?php

class Taak extends Eloquent {
	public static $table = "taken";
	public static $timestamps = true;

	public function uitvoeringen()
	{
		return $this->has_many('Taakuitvoering');
	}
	
	public function uitvoerders($datum = null)
	{
		if($datum === null) {
			$datum = new DateTime("today");
		}
		$uitvoeringen = $this->uitvoeringen()->where("datum", "=", $datum)->get();
		$gebruikers = array();
		foreach($uitvoeringen as $uitvoering) {
			$gebruikers[] = $uitvoering->gebruiker()->results();
		}
		return $gebruikers;
	}
	
	public function isGedaan($gebruikerID, $datum = null)
	{
		if($datum === null) {
			$datum = new DateTime("today");
		}
		
		if(Taakuitvoering::where_gebruiker_id_and_datum_and_taak_id($gebruikerID, $datum, $this->id)->count() > 0) {
			return;
		}
		
		$uitvoering = new Taakuitvoering();
		$uitvoering->datum = $datum;
		$uitvoering->gebruiker_id = $gebruikerID;
		return $this->uitvoeringen()->insert($uitvoering);
	}
	
	public static function takenVandaag()
	{
		$vandaag = new DateTime("today");
		$taakIDs = DB::query("SELECT DISTINCT taak.id FROM `taken` AS taak LEFT JOIN taakuitvoeringen AS uitvoering ON taak.id = uitvoering.taak_id WHERE DATEDIFF(?, (SELECT MAX(datum) FROM taakuitvoeringen WHERE taak_id = taak.id AND DATEDIFF(datum, ?) < 0)) > (frequentie - 1) OR (SELECT count(taak_id) FROM taakuitvoeringen WHERE taak_id = taak.id AND DATEDIFF(datum, ?) < 0) = 0 OR uitvoering.datum = ? ORDER BY taak.naam", array($vandaag, $vandaag, $vandaag, $vandaag));
		$takenVandaag = array();
		foreach($taakIDs as $taakID) {
			$takenVandaag[] = Taak::find($taakID->id);
		}
		return $takenVandaag;
	}
}
