<?php

class Vogelinfo extends Eloquent {
	public static $table = "vogelinfo";
	public static $timestamps = true;

	public function vogel()
	{
		return $this->belongs_to('Vogel');
	}

}
