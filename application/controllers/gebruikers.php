<?php

class Gebruikers_Controller extends Base_Controller {
	public $restful = true;
	
	public function get_index()
	{
		$gebruikers = Gebruiker::all();
		return View::make("gebruikers.index")->with("gebruikers", $gebruikers);
	}
	
	public function post_index()
	{
		if(Input::has("action")) {
			if(Input::get("action") == "nieuw") {
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
		
		return Redirect::to_route("gebruikers");
	}
	
	public function get_detail($id, $naam)
	{
		$gebruiker = Gebruiker::find($id);
		
		return View::make("gebruikers.detail")
			->with("gebruiker", $gebruiker);
	}
	
	public function post_detail($id, $naam)
	{
		$gebruiker = Gebruiker::find($id);
		if(Input::has("action")) {
			if(Input::get("action") == "foto") {
				$gebruiker->foto = Input::file("foto");
				$gebruiker->save();
			}
			if(Input::get("action") == "informatie") {
				$gebruiker->informatie = Input::get("informatie");
				$gebruiker->save();
			}
		}
		
		return Redirect::to_route("gebruikerDetail", array($id, $naam));
	}
}
