<?php

class Soortinfo extends Eloquent {
	public static $table = "soortinfo";
	public static $timestamps = true;

	public function soort()
	{
		return $this->belongs_to('Soort');
	}

}
