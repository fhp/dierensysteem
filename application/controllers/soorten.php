<?php

class Soorten_Controller extends Base_Controller {
	public $restful = true;
	
	public $rulesNieuw = array(
		"naam"=>"required",
		"geslacht"=>"in:onbekend,tarsel,wijf",
		"soort"=>"integer",
		"geboortedatum"=>"match:/^[0-9][0-9]?-[0-9][0-9]?-[0-9][0-9]([0-9][0-9])?$/",
		"foto"=>"image",
	);
	
	public $rulesInformatie = array(
	);
	
	public function get_index()
	{
		$soorten = Soort::all();
		return View::make("soorten.index")
			->with("rulesNieuw", $this->rulesNieuw)
			->with("soorten", $soorten);
	}
	
	public function post_index()
	{
		if(Input::has("action")) {
			if(Input::get("action") == "nieuw") {
				if(Validator::make(Input::all(), $this->rulesNieuw)->passes()) {
					$soort = new Soort();
					$soort->naam = Input::get("naam");
					$soort->latijnsenaam = Input::get("latijnsenaam");
					$soort->save();
				}
			}
		}
		
		return Redirect::to_route("soorten");
	}
	
	public function get_detail($id, $naam)
	{
		$soort = Soort::find($id);
		return View::make("soorten.detail")
			->with("rulesInformatie", $this->rulesInformatie)
			->with("soort", $soort);
	}
	
	public function post_detail($id, $naam)
	{
		$soort = Soort::find($id);
		if(Input::has("action")) {
			if(Input::get("action") == "informatie") {
				if(Validator::make(Input::all(), $this->rulesInformatie)->passes()) {
					$soort->informatie = Input::get("informatie");
					$soort->save();
				}
			}
		}
		
		return Redirect::to_route("soortDetail", array($id, $naam));
	}
}
