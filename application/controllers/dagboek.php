<?php

class Dagboek_Controller extends Base_Controller {
	public $restful = true;
	
	public $rulesVerslag = array(
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
					$dagverslag->gebruiker_id = Auth::user()->id;
					$dagverslag->datum = new DateTime("today");
					$dagverslag->save();
				}
			}
		}
		
		return Redirect::to_route("dagboek");
	}
}
