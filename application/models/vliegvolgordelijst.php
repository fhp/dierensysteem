<?php

class Vliegvolgordelijst extends Eloquent {
	public static $table = "vliegvolgordelijsten";
	public static $timestamps = true;

	public function vogels()
	{
		return $this->has_many_and_belongs_to("Vogel", "vliegvolgorde")->with('opmerkingen', 'volgorde');
	}
}

?>