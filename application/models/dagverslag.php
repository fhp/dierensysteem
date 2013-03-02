<?php

class Dagverslag extends Eloquent {
	public static $table = "dagverslagen";
	public static $timestamps = true;

	public function gebruiker()
	{
		return $this->belongs_to('Gebruiker');
	}

}
