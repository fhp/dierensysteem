<?php

class Dagboek_Controller extends Base_Controller {
	public $restful = true;
	
	public function get_index()
	{
		$verslagen = Dagverslag::order_by("datum", "desc")->paginate(10);
		return View::make("dagboek.index")->with("verslagen", $verslagen);
	}
	
	public function post_index()
	{
		if(Input::has("action")) {
			if(Input::get("action") == "dagverslag") {
				$dagverslag = new Dagverslag();
				$dagverslag->tekst = Input::get("tekst");
				$dagverslag->gebruiker_id = Auth::user()->id;
				$dagverslag->datum = new DateTime("today");
				$dagverslag->save();
			}
		}
		
		return Redirect::to_route("dagboek");
	}
}
