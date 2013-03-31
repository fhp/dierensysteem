<?php

class Categorie extends Eloquent {
	public static $table = "categorieen";
	public static $timestamps = true;

	public function vogels()
	{
		return $this->has_many('Vogel');
	}
}
