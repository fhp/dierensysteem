<?php

class Home_Controller extends Base_Controller {
	public $restful = true;

	public $rulesMededeling = array(
		"tekst"=>"required",
	);
	public $rulesMededelingBewerk = array(
		"tekst"=>"required",
		"datum"=>"required",
		"gebruiker"=>"required|exists:gebruikers,id"
	);
	
	public function get_index()
	{
		return View::make('home.index')
			->with("rulesMededeling", $this->rulesMededeling);
	}
	
	public function post_index()
	{
		if(Input::has("action")) {
			if(Input::get("action") == "mededeling") {
				if(!isAdmin()) {
					return Redirect::back();
				}
				if(Validator::make(Input::all(), $this->rulesMededeling)->passes()) {
					$mededeling = new Mededeling();
					$mededeling->tekst = Input::get("tekst");
					$mededeling->gebruiker_id = Auth::user()->id;
					$mededeling->datum = new DateTime("today");
					$mededeling->save();
				}
			}
		}
		
		return Redirect::back();
	}
	
	public function get_mededelingen($id)
	{
		$mededeling = Mededeling::find($id);
		return View::make("home.mededeling")
			->with("rulesMededelingBewerk", $this->rulesMededelingBewerk)
			->with("mededeling", $mededeling);
	}
	
	public function post_mededelingen($id)
	{
		if(!isAdmin()) {
			return Redirect::back();
		}
		$mededeling = Mededeling::find($id);
		if(Input::has("action")) {
			if(Input::get("action") == "bewerk") {
				if(Validator::make(Input::all(), $this->rulesMededelingBewerk)->passes()) {
					$mededeling->tekst = Input::get("tekst");
					$mededeling->gebruiker_id = Input::get("gebruiker");
					$mededeling->datum = new DateTime(Input::get("datum"));
					$mededeling->save();
				}
			}
			if(Input::get("action") == "verwijder") {
				$mededeling->delete();
			}
		}
		
		return Redirect::to_route("home");
	}
}
