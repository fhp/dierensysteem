<?php

class Home_Controller extends Base_Controller {
	public $restful = true;

	public function get_index()
	{
		$mededelingen = Mededeling::order_by("datum", "desc")->order_by("id", "asc")->paginate(10);
		
		return View::make('home.index')
			->with("mededelingen", $mededelingen);
	}
	
	public function post_index()
	{
		if(Input::has("action")) {
			if(Input::get("action") == "mededeling") {
				$mededeling = new Mededeling();
				$mededeling->tekst = Input::get("tekst");
				$mededeling->gebruiker_id = Auth::user()->id;
				$mededeling->datum = new DateTime("today");
				$mededeling->save();
			}
		}
		
		return Redirect::to_route("home");
	}
}
