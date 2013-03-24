<?php

class Gebruikers_Controller extends Base_Controller {
	public $restful = true;
	
	public $rulesNieuw = array(
		"gebruikersnaam"=>"required|alpha_dash|unique:gebruikers",
		"naam"=>"required",
		"email"=>"required|email",
		"telefoon"=>"numeric",
		"wachtwoord"=>"required|confirmed|min:6",
		"foto"=>"image",
	);
	
	public $rulesFoto = array(
		"foto"=>"required|image",
	);
	
	public $rulesInformatie = array(
	);
	
	public $rulesBiografie = array(
	);
	
	public function get_index()
	{
		$gebruikers = Gebruiker::order_by("naam")->get();
		return View::make("gebruikers.index")
			->with("rulesNieuw", $this->rulesNieuw)
			->with("gebruikers", $gebruikers);
	}
	
	public function post_index()
	{
		if(Input::has("action")) {
			if(Input::get("action") == "nieuw") {
				if(!Auth::user()->admin) {
					return Redirect::back();
				}
				if(Validator::make(Input::all(), $this->rulesNieuw)->passes()) {
					$gebruiker = new Gebruiker();
					$gebruiker->gebruikersnaam = Input::get("gebruikersnaam");
					$gebruiker->naam = Input::get("naam");
					$gebruiker->wachtwoord = Hash::make(Input::get("wachtwoord"));
					$gebruiker->email = Input::get("email");
					$gebruiker->telefoon = Input::get("telefoon");
					$gebruiker->admin = Input::get("admin", 0);
					
					if(Input::has_file("foto")) {
						$gebruiker->foto = Input::file("foto");
					}
					
					$gebruiker->save();
				}
			}
		}
		return Redirect::back();
	}
	
	public function get_detail($id, $naam)
	{
		$gebruiker = Gebruiker::find($id);
		
		return View::make("gebruikers.detail")
			->with("rulesFoto", $this->rulesFoto)
			->with("rulesInformatie", $this->rulesInformatie)
			->with("rulesBiografie", $this->rulesBiografie)
			->with("gebruiker", $gebruiker);
	}
	
	public function post_detail($id, $naam)
	{
		$gebruiker = Gebruiker::find($id);
		if(Input::has("action")) {
			if(Input::get("action") == "foto") {
				if(!(Auth::user()->admin || Auth::user()->id == $id)) {
					return Redirect::back();
				}
				if(Validator::make(Input::all(), $this->rulesFoto)->passes()) {
					$gebruiker->foto = Input::file("foto");
					$gebruiker->save();
				}
			}
			if(Input::get("action") == "informatie") {
				if(!Auth::user()->admin) {
					return Redirect::back();
				}
				if(Validator::make(Input::all(), $this->rulesInformatie)->passes()) {
					$gebruiker->informatie = Input::get("informatie");
					$gebruiker->save();
				}
			}
			if(Input::get("action") == "biografie") {
				if(!(Auth::user()->admin || Auth::user()->id == $id)) {
					return Redirect::back();
				}
				if(Validator::make(Input::all(), $this->rulesBiografie)->passes()) {
					$gebruiker->biografie = Input::get("biografie");
					$gebruiker->save();
				}
			}
		}
		
		return Redirect::back();
	}
}
