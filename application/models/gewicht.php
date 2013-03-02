<?php

class Gewicht extends Eloquent {
	public static $table = "gewichten";
	public static $timestamps = true;

	public function vogel()
	{
		return $this->belongs_to('Vogel');
	}

	public function gebruiker()
	{
		return $this->belongs_to('Gebruiker');
	}

}
