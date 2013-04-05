<?php

class Gebruikers_Controller extends Base_Controller {
	public $restful = true;
	
	public $rulesNieuw = array(
		"gebruikersnaam"=>"required|alpha_dash|unique:gebruikers",
		"naam"=>"required",
		"email"=>"email",
		"telefoon"=>"numeric",
		"wachtwoord"=>"required|confirmed|min:6",
		"foto"=>"image",
	);
	
	public $rulesFoto = array(
		"foto"=>"required|image",
	);
	
	public $rulesInformatie = array(
		"email"=>"email",
		"telefoon"=>"numeric",
	);
	
	public $rulesBiografie = array(
	);
	
	public $rulesWachtwoord = array(
		"wachtwoord"=>"required|confirmed|min:6",
	);
	
	public function get_index()
	{
		return View::make("gebruikers.index")
			->with("rulesNieuw", $this->rulesNieuw);
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
					$gebruiker->nonactief = 0;
					
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
			->with("rulesWachtwoord", $this->rulesWachtwoord)
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
					$gebruiker->email = Input::get("email");
					$gebruiker->telefoon = Input::get("telefoon");
					$gebruiker->informatie = Input::get("informatie");
					$gebruiker->nonactief = Input::get("nonactief", 0);
					$gebruiker->admin = Input::get("admin", 0);
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
			if(Input::get("action") == "wachtwoord") {
				if(!(Auth::user()->admin || Auth::user()->id == $id)) {
					return Redirect::back();
				}
				if(Validator::make(Input::all(), $this->rulesWachtwoord)->passes()) {
					$gebruiker->wachtwoord = Hash::make(Input::get("wachtwoord"));
					$gebruiker->save();
				}
			}
			if(Input::get("action") == "vliegpermissies") {
				if(!Auth::user()->admin) {
					return Redirect::back();
				}
				$gebruiker->vliegpermissies()->delete();
				foreach(Vogel::all() as $vogel) {
					if(Input::has("vogel-" . $vogel->id)) {
						$gebruiker->vliegpermissies()->attach($vogel->id, array("opmerkingen"=>(Input::get("opmerkingen-" . $vogel->id) == "" ? null : Input::get("opmerkingen-" . $vogel->id))));
					}
				}
			}
		}
		
		return Redirect::back();
	}
	
	public function post_inklokken()
	{
		$gebruiker = Auth::user();
		if(!$gebruiker->isAanwezig()) {
			return Redirect::back();
		}
		$aanwezigheid = $gebruiker->aanwezigheid();
		$aanwezigheid->start = new DateTime();
		$aanwezigheid->save();
		return Redirect::back();
	}
	
	public function post_uitklokken()
	{
		$gebruiker = Auth::user();
		if(!$gebruiker->isAanwezig()) {
			return Redirect::back();
		}
		$aanwezigheid = $gebruiker->aanwezigheid();
		if($aanwezigheid->start === null) {
			return Redirect::back();
		}
		$aanwezigheid->einde = new DateTime();
		$aanwezigheid->save();
		return Redirect::back();
	}
	
	public function get_uren($id, $naam, $jaar = null, $maand = null)
	{
		if($jaar === null) {
			$jaar = date("Y");
		}
		if($maand === null) {
			$maand = date("m");
		}
		if(!(Auth::user()->admin || Auth::user()->id == $id)) {
			return Response::error('404');
		}
		$gebruiker = Gebruiker::find($id);
		return View::make("gebruikers.uren")
			->with("jaar", $jaar)
			->with("maand", $maand)
			->with("gebruiker", $gebruiker);
	}
	
	public function get_veranderWachtwoord()
	{
		if (Auth::guest()) return Redirect::to('login');
		
		return View::make("gebruikers.veranderwachtwoord")
			->with("rulesWachtwoord", $this->rulesWachtwoord);
	}
	
	public function post_veranderWachtwoord()
	{
		if (Auth::guest()) return Redirect::to('login');
		
		if(Validator::make(Input::all(), $this->rulesWachtwoord)->passes()) {
			$gebruiker = Auth::user();
			$gebruiker->wachtwoord = Hash::make(Input::get("wachtwoord"));
			$gebruiker->save();
		}
		return Redirect::to_route('home');
	}
}
