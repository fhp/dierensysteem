<?php

class Dagboek_Controller extends Base_Controller {
	public $restful = true;
	
	public $rulesVerslag = array(
		"tekst"=>"required",
	);
	public $rulesVerslagBewerk = array(
		"tekst"=>"required",
	);
	
	public function get_index()
	{
		$verslagen = Dagverslag::order_by("datum", "desc")->paginate(10);
		return View::make("dagboek.index")
			->with("rulesVerslag", $this->rulesVerslag)
			->with("verslagen", $verslagen);
	}
	
	public function post_index()
	{
		if(Input::has("action")) {
			if(Input::get("action") == "dagverslag") {
				if(Validator::make(Input::all(), $this->rulesVerslag)->passes()) {
					$dagverslag = new Dagverslag();
					$dagverslag->tekst = Input::get("tekst");
					if(isAdmin()) {
						$dagverslag->gebruiker_id = Input::get("gebruiker");
						$dagverslag->datum = new DateTime(Input::get("datum"));
					} else {
						$dagverslag->gebruiker_id = Auth::user()->id;
						$dagverslag->datum = new DateTime("today");
					}
					$dagverslag->save();
				}
			}
		}
		
		return Redirect::to_route("dagboek");
	}
	
	public function get_verslag($id)
	{
		$verslag = Dagverslag::find($id);
		return View::make("dagboek.verslag")
			->with("rulesVerslagBewerk", $this->rulesVerslagBewerk)
			->with("verslag", $verslag);
	}
	
	public function post_verslag($id)
	{
		$verslag = Dagverslag::find($id);
		if(!(isAdmin() || (Auth::user()->id == $verslag->gebruiker_id && (new DateTime($verslag->datum_edit) == new DateTime("today"))))) {
			return Redirect::back();
		}
		if(Input::has("action")) {
			if(Input::get("action") == "bewerk") {
				if(Validator::make(Input::all(), $this->rulesVerslagBewerk)->passes()) {
					$verslag->tekst = Input::get("tekst");
					if(isAdmin()) {
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
		
		return Redirect::to_route("dagboek");
	}
}
