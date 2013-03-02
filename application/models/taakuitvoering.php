<?php

class Taakuitvoering extends Eloquent {
	public static $table = "taakuitvoeringen";
	public static $timestamps = true;

	public function gebruiker()
	{
		return $this->belongs_to('Gebruiker');
	}

	public function taak()
	{
		return $this->belongs_to('Taak');
	}

}
