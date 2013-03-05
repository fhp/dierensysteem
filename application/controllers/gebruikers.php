<?php

class Gebruikers_Controller extends Base_Controller {
	public $restful = true;
	
	public $rulesNieuw = array(
		"gebruikersnaam"=>"required|alpha_dash|unique:gebruikers",
		"naam"=>"required",
		"email"=>"required|email",
		"telefoon"=>"integer",
		"wachtwoord"=>"required|confirmed|min:6",
		"foto"=>"image",
	);
	
	public $rulesFoto = array(
		"foto"=>"required|image",
	);
	
	public $rulesInformatie = array(
	);
	
	public function get_index()
	{
		$gebruikers = Gebruiker::all();
		return View::make("gebruikers.index")
			->with("rulesNieuw", $this->rulesNieuw)
			->with("gebruikers", $gebruikers);
	}
	
	public function post_index()
	{
		if(Input::has("action")) {
			if(Input::get("action") == "nieuw") {
				if(Validator::make(Input::all(), $this->rulesNieuw)->passes()) {
					$gebruiker = new Gebruiker();
					$gebruiker->gebruikersnaam = Input::get("gebruikersnaam");
					$gebruiker->naam = Input::get("naam");
					$gebruiker->wachtwoord = Hash::make(Input::get("wachtwoord"));
					$gebruiker->email = Input::get("email");
					$gebruiker->telefoon = Input::get("telefoon");
					
					if(Input::has_file("foto")) {
						$gebruiker->foto = Input::file("foto");
					}
					$gebruiker->save();
				}
			}
		}
		
		return Redirect::to_route("gebruikers");
	}
	
	public function get_detail($id, $naam)
	{
		$gebruiker = Gebruiker::find($id);
		
		return View::make("gebruikers.detail")
			->with("rulesFoto", $this->rulesFoto)
			->with("rulesInformatie", $this->rulesInformatie)
			->with("gebruiker", $gebruiker);
	}
	
	public function post_detail($id, $naam)
	{
		$gebruiker = Gebruiker::find($id);
		if(Input::has("action")) {
			if(Input::get("action") == "foto") {
				if(Validator::make(Input::all(), $this->rulesFoto)->passes()) {
					$gebruiker->foto = Input::file("foto");
					$gebruiker->save();
				}
			}
			if(Input::get("action") == "informatie") {
				if(Validator::make(Input::all(), $this->rulesInformatie)->passes()) {
					$gebruiker->informatie = Input::get("informatie");
					$gebruiker->save();
				}
			}
		}
		
		return Redirect::to_route("gebruikerDetail", array($id, $naam));
	}
}
