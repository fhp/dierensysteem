<?php

class Vliegvolgorde extends Eloquent {
	public static $table = "vliegvolgorde";
	public static $timestamps = true;

	public function vogels()
	{
		return $this->has_many('Vogel', 'lijst_id');
	}
}

?>