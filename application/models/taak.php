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
}
