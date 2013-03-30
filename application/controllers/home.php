<?php

class Home_Controller extends Base_Controller {
	public $restful = true;

	public $rulesMededeling = array(
		"tekst"=>"required",
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
				if(!Auth::user()->admin) {
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
}
