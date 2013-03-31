<?php

class Soorten_Controller extends Base_Controller {
	public $restful = true;
	
	public $rulesNieuw = array(
		"naam"=>"required",
	);
	
	public $rulesInformatie = array(
		"naam"=>"required",
	);
	
	public function get_index()
	{
		$soorten = Soort::order_by("naam")->get();
		return View::make("soorten.index")
			->with("rulesNieuw", $this->rulesNieuw)
			->with("soorten", $soorten);
	}
	
	public function post_index()
	{
		if(Input::has("action")) {
			if(Input::get("action") == "nieuw") {
				if(!Auth::user()->admin) {
					return Redirect::back();
				}
				if(Validator::make(Input::all(), $this->rulesNieuw)->passes()) {
					$soort = new Soort();
					$soort->naam = Input::get("naam");
					$soort->engelsenaam = Input::get("engelsenaam");
					$soort->latijnsenaam = Input::get("latijnsenaam");
					$soort->save();
				}
			}
		}
		
		return Redirect::back();
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
				if(!Auth::user()->admin) {
					return Redirect::back();
				}
				if(Validator::make(Input::all(), $this->rulesInformatie)->passes()) {
					$soort->naam = Input::get("naam");
					$soort->engelsenaam = Input::get("engelsenaam");
					$soort->latijnsenaam = Input::get("latijnsenaam");
					$soort->informatie = Input::get("informatie");
					$soort->save();
				}
			}
		}
		
		return Redirect::back();
	}
}
