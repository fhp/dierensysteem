<?php

class Vogels_Controller extends Base_Controller {
	public $restful = true;
	
	public $rulesNieuw = array(
		"naam"=>"required",
		"geslacht"=>"in:onbekend,tarsel,wijf",
		"soort"=>"integer",
		"geboortedatum"=>"match:/^[0-9][0-9]?-[0-9][0-9]?-[0-9][0-9]([0-9][0-9])?$/",
		"foto"=>"image",
	);
	
	public $rulesFoto = array(
		"foto"=>"required|image",
	);
	
	public $rulesVerslag = array(
		"tekst"=>"required",
	);
	
	public $rulesAlert = array(
	);
	
	public $rulesInformatie = array(
	);
	
	public function get_index()
	{
		$vogels = Vogel::all();
		$soorten = array();
		foreach(Soort::all() as $soort) {
			$soorten[$soort->id] = $soort->naam;
		}
		return View::make("vogels.index")
			->with("vogels", $vogels)
			->with("soorten", $soorten)
			->with("rulesNieuw", $this->rulesNieuw);
	}
	
	public function post_index()
	{
		if(Input::has("action")) {
			if(Input::get("action") == "nieuw") {
				if(Validator::make(Input::all(), $this->rulesNieuw)->passes()) {
					$vogel = new Vogel();
					$vogel->naam = Input::get("naam");
					$vogel->geslacht = Input::get("geslacht");
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
		
		return Redirect::to_route("vogels");
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
			->with("rulesAlert", $this->rulesAlert);

	}
	
	public function post_detail($id, $naam)
	{
		$vogel = Vogel::find($id);
		if(Input::has("action")) {
			if(Input::get("action") == "foto") {
				if(Validator::make(Input::all(), $this->rulesFoto)->passes()) {
					$vogel->foto = Input::file("foto");
					$vogel->save();
				}
			}
			if(Input::get("action") == "verslag") {
				if(Validator::make(Input::all(), $this->rulesVerslag)->passes()) {
					$verslag = new Vogelverslag();
					$verslag->tekst = Input::get("tekst");
					$verslag->datum = new DateTime("today");
					$verslag->gebruiker_id = Auth::user()->id;
					$vogel->verslagen()->insert($verslag);
				}
			}
			if(Input::get("action") == "alert") {
				if(Validator::make(Input::all(), $this->rulesAlert)->passes()) {
					$vogel->alert = Input::get("alert");
					$vogel->save();
				}
			}
			if(Input::get("action") == "informatie") {
				if(Validator::make(Input::all(), $this->rulesInformatie)->passes()) {
					$vogel->informatie = Input::get("informatie");
					$vogel->save();
				}
			}
		}
		
		return Redirect::to_route("vogelDetail", array($id, $naam));
	}
}
