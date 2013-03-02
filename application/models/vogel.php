<?php

class Vogel extends Eloquent {
	public static $table = "vogels";
	public static $timestamps = true;

	public function soort()
	{
		return $this->belongs_to('Soort');
	}

	public function info()
	{
		return $this->has_many('Vogelinfo');
	}

	public function gewichten()
	{
		return $this->has_many('Gewicht');
	}

	public function verslagen()
	{
		return $this->has_many('Vogelverslag');
	}

	public function vliegevaluaties()
	{
		return $this->has_many('Vliegevaluatie');
	}

}
