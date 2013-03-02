<?php

class Gebruiker extends Eloquent {
	public static $table = "gebruikers";
	public static $timestamps = true;

	public function gewichten()
	{
		return $this->has_many('Gewicht');
	}

	public function aanwezigheden()
	{
		return $this->has_many('Aanwezigheid');
	}

	public function taakuitvoeringen()
	{
		return $this->has_many('Taakuitvoering');
	}

	public function dagverslagen()
	{
		return $this->has_many('Dagverslag');
	}

	public function vliegevaluaties()
	{
		return $this->has_many('Vliegevaluatie');
	}

	public function vogelverslagen()
	{
		return $this->has_many('Vogelverslag');
	}

}
