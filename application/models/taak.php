<?php

class Taak extends Eloquent {
	public static $table = "taken";
	public static $timestamps = true;

	public function uitvoeringen()
	{
		return $this->has_many('Taakuitvoering');
	}

}
