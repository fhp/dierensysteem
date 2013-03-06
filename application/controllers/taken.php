<?php

class Taken_Controller extends Base_Controller {
	public $restful = true;
	
	public function get_index()
	{
		$taken = Taak::all();
		return View::make("taken.index")
			->with("taken", $taken);
	}
	
	public function get_detail($id, $naam)
	{
		$taak = Taak::find($id);
		return View::make("taken.detail")
			->with("taak", $taak);
	}
}
