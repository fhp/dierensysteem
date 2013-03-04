<?php

class Soort extends Eloquent {
	public static $table = "soorten";
	public static $timestamps = true;

	public function vogels()
	{
		return $this->has_many('Vogel');
	}

}
