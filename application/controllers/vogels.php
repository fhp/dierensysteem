<?php

class Vogels_Controller extends Base_Controller {
	public $restful = true;
	
	public $rulesNieuw = array(
		"naam"=>"required",
		"geslacht"=>"in:onbekend,tarsel,wijf",
		"soort"=>"required|integer",
		"geboortedatum"=>"match:/^[0-9][0-9]?-[0-9][0-9]?-[0-9][0-9]([0-9][0-9])?$/",
		"foto"=>"image",
	);
	
	public $rulesFoto = array(
		"foto"=>"required|image",
	);
	
	public $rulesVerslag = array(
		"tekst"=>"required",
	);
	
	public $rulesVerslagBewerk = array(
		"tekst"=>"required",
	);
	
	public $rulesAlert = array(
	);
	
	public $rulesInformatie = array(
		"naam"=>"required",
		"geslacht"=>"in:onbekend,tarsel,wijf",
		"geboortedatum"=>"match:/^[0-9][0-9]?-[0-9][0-9]?-[0-9][0-9]([0-9][0-9])?$/",
	);
	
	public $rulesCategorie = array(
		"categorie"=>"required|integer",
		"overleidensdatum"=>"match:/^[0-9][0-9]?-[0-9][0-9]?-[0-9][0-9]([0-9][0-9])?$/",
	);
	
	public function get_index($categorie_id = 1)
	{
		$categorie = Categorie::find($categorie_id);
		return View::make("vogels.index")
			->with("categorie", $categorie)
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
					$vogel = new Vogel();
					$vogel->categorie_id = Input::get("categorie");
					$vogel->naam = Input::get("naam");
					$vogel->geslacht = Input::get("geslacht");
					$vogel->wegen = Input::has("wegen");
					if(Input::has("geboortedatum")) {
						$vogel->geboortedatum = new DateTime(Input::get("geboortedatum"));
					}
					if(Input::has_file("foto")) {
						$vogel->foto = Input::file("foto");
					}
					Soort::find(Input::get("soort"))->vogels()->insert($vogel);
				}
			}
		}
		
		return Redirect::back();
	}
	
	public function get_grafiek($id)
	{
		return View::make("vogels.grafiek-gewicht")->with("vogel", Vogel::find($id));
	}
	
	public function get_detail($id, $naam)
	{
		$vogel = Vogel::find($id);
		
		$verslagen = $vogel->verslagen()->order_by('datum', 'desc')->order_by("id", "asc")->paginate(5);
		
		return View::make("vogels.detail")
			->with("vogel", $vogel)
			->with("verslagen", $verslagen)
			->with("rulesFoto", $this->rulesFoto)
			->with("rulesVerslag", $this->rulesVerslag)
			->with("rulesInformatie", $this->rulesInformatie)
			->with("rulesAlert", $this->rulesAlert)
			->with("rulesCategorie", $this->rulesCategorie);
	}
	
	public function post_detail($id, $naam)
	{
		$vogel = Vogel::find($id);
		if(Input::has("action")) {
			if(Input::get("action") == "foto") {
				if(!Auth::user()->admin) {
					return Redirect::back();
				}
				if(Validator::make(Input::all(), $this->rulesFoto)->passes()) {
					$vogel->foto = Input::file("foto");
					$vogel->save();
				}
			}
			if(Input::get("action") == "verslag") {
				if(Validator::make(Input::all(), $this->rulesVerslag)->passes()) {
					$verslag = new Vogelverslag();
					$verslag->tekst = Input::get("tekst");
					if(Auth::user()->admin) {
						$verslag->gebruiker_id = Input::get("gebruiker");
						$verslag->datum = new DateTime(Input::get("datum"));
					} else {
						$verslag->gebruiker_id = Auth::user()->id;
						$verslag->datum = new DateTime("today");
					}
					$vogel->verslagen()->insert($verslag);
				}
			}
			if(Input::get("action") == "alert") {
				if(!Auth::user()->admin) {
					return Redirect::back();
				}
				if(Validator::make(Input::all(), $this->rulesAlert)->passes()) {
					$vogel->alert = Input::get("alert");
					$vogel->save();
				}
			}
			if(Input::get("action") == "informatie") {
				if(!Auth::user()->admin) {
					return Redirect::back();
				}
				if(Validator::make(Input::all(), $this->rulesInformatie)->passes()) {
					$vogel->naam = Input::get("naam");
					$vogel->geslacht = Input::get("geslacht");
					if(Input::has("geboortedatum")) {
						$vogel->geboortedatum = new DateTime(Input::get("geboortedatum"));
					} else {
						$vogel->geboortedatum = null;
					}
					if(Input::get("eigenaar") == 0) {
						$vogel->eigenaar_id = null;
					} else {
						$eigenaar = Gebruiker::find(Input::get("eigenaar"));
						$vogel->eigenaar_id = $eigenaar->id;
					}
					$vogel->wegen = Input::has("wegen");
					$vogel->informatie = Input::get("informatie");
					$vogel->save();
				}
			}
			if(Input::get("action") == "categorie") {
				if(!Auth::user()->admin) {
					return Redirect::back();
				}
				if(Validator::make(Input::all(), $this->rulesCategorie)->passes()) {
					$categorie = Categorie::find(Input::get("categorie"));
					$vogel->categorie_id = $categorie->id;
					if(Input::has("overleidensdatum")) {
						$vogel->overleidensdatum = new DateTime(Input::get("overleidensdatum"));
					} else {
						$vogel->overleidensdatum = null;
					}
					$vogel->save();
				}
			}
			if(Input::get("action") == "vliegpermissies") {
				if(!Auth::user()->admin) {
					return Redirect::back();
				}
				$permissies = array();
				foreach(Gebruiker::all() as $gebruiker) {
					if(Input::has("gebruiker-" . $gebruiker->id)) {
						$permissies[] = $gebruiker->id;
					}
				}
				$vogel->vliegpermissies()->sync($permissies);
			}
		}
		
		return Redirect::back();
	}
	
	public function get_verslag($id)
	{
		$verslag = Vogelverslag::find($id);
		return View::make("vogels.verslag")
			->with("rulesVerslagBewerk", $this->rulesVerslagBewerk)
			->with("verslag", $verslag);
	}
	
	public function post_verslag($id)
	{
		$verslag = Vogelverslag::find($id);
		if(!(Auth::user()->admin || (Auth::user()->id == $verslag->gebruiker_id && (new DateTime($verslag->datum_edit) == new DateTime("today"))))) {
			return Redirect::back();
		}
		if(Input::has("action")) {
			if(Input::get("action") == "bewerk") {
				if(Validator::make(Input::all(), $this->rulesVerslagBewerk)->passes()) {
					$verslag->tekst = Input::get("tekst");
					if(Auth::user()->admin) {
						$verslag->gebruiker_id = Input::get("gebruiker");
						$verslag->datum = new DateTime(Input::get("datum"));
					}
					$verslag->save();
				}
			}
			if(Input::get("action") == "verwijder") {
				$verslag->delete();
			}
		}
		
		return Redirect::to_route("vogelDetail", array($verslag->vogel->id, $verslag->vogel->naam));
	}
}
