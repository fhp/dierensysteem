<?php

class Vliegevaluatie extends Eloquent {
	public static $table = "vliegevaluaties";
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
