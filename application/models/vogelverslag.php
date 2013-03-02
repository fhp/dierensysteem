<?php

class Vogelverslag extends Eloquent {
	public static $table = "vogelverslagen";
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
