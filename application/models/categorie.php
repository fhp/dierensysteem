<?php

class Categorie extends Eloquent {
	public static $table = "categorieen";
	public static $timestamps = true;

	public function vogels()
	{
		return $this->has_many('Vogel');
	}
	
	public function ongelezenVerslagen($gebruiker_id = null)
	{
		if($gebruiker_id == null) {
			if(Auth::check()) {
				$gebruiker_id = Auth::user()->id;
			} else {
				return array();
			}
		}
		
		return DB::table('vogelgelezen')->join("vogels", "vogels.id", "=", "vogelgelezen.vogel_id")->where_gebruiker_id($gebruiker_id)->where_categorie_id($this->id)->count() != $this->vogels()->count();
	}
}
