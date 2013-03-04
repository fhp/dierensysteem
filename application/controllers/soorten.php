<?php

class Soorten_Controller extends Base_Controller {
	public $restful = true;
	
	public function get_index()
	{
		$soorten = Soort::all();
		return View::make("soorten.index")->with("soorten", $soorten);
	}
	
	public function post_index()
	{
		if(Input::has("action")) {
			if(Input::get("action") == "nieuw") {
				$soort = new Soort();
				$soort->naam = Input::get("naam");
				$soort->latijnsenaam = Input::get("latijnsenaam");
				$soort->save();
			}
		}
		
		return Redirect::to_route("soorten");
	}
	
	public function get_detail($id, $naam)
	{
		$soort = Soort::find($id);
		return View::make("soorten.detail")->with("soort", $soort);
	}
	
	public function post_detail($id, $naam)
	{
		$soort = Soort::find($id);
		if(Input::has("action")) {
			if(Input::get("action") == "informatie") {
				$soort->informatie = Input::get("informatie");
				$soort->save();
			}
		}
		
		return Redirect::to_route("soortDetail", array($id, $naam));
	}
}
