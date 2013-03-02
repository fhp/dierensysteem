<?php

class Aanwezigheid extends Eloquent {
	public static $table = "aanwezigheid";
	public static $timestamps = true;

	public function gebruiker()
	{
		return $this->belongs_to('Gebruiker');
	}

}
