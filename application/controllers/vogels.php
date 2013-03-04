<?php

class Vogels_Controller extends Base_Controller {
	public $restful = true;
	
	public function get_index()
	{
		$vogels = Vogel::all();
		$soorten = array();
		foreach(Soort::all() as $soort) {
			$soorten[$soort->id] = $soort->naam;
		}
		return View::make("vogels.index")
			->with("vogels", $vogels)
			->with("soorten", $soorten);
	}
	
	public function post_index()
	{
		if(Input::has("action")) {
			if(Input::get("action") == "nieuw") {
				$vogel = new Vogel();
				$vogel->naam = Input::get("naam");
				$vogel->geslacht = Input::get("geslacht");
				if(Input::has_file("foto")) {
					$vogel->foto = Input::file("foto");
				}
				Soort::find(Input::get("soort"))->vogels()->insert($vogel);
			}
		}
		
		return Redirect::to_route("vogels");
	}
	
	public function get_detail($id, $naam)
	{
		$vogel = Vogel::find($id);
		
		$notities = $vogel->info()->order_by("created_at", "desc")->get();
		$verslagen = $vogel->verslagen()->order_by('datum', 'desc')->order_by("id", "asc")->paginate(5);
		
		return View::make("vogels.detail")
			->with("vogel", $vogel)
			->with("notities", $notities)
			->with("verslagen", $verslagen);
	}
	
	public function post_detail($id, $naam)
	{
		$vogel = Vogel::find($id);
		if(Input::has("action")) {
			if(Input::get("action") == "foto") {
				$vogel->foto = Input::file("foto");
				$vogel->save();
			}
			if(Input::get("action") == "vogelInfo") {
				$info = new Vogelinfo();
				$info->titel = Input::get("titel");
				$info->tekst = Input::get("tekst");
				$vogel->info()->insert($info);
			}
			if(Input::get("action") == "verslag") {
				$verslag = new Vogelverslag();
				$verslag->tekst = Input::get("tekst");
				$verslag->datum = new DateTime("today");
				$verslag->gebruiker_id = Auth::user()->id;
				$vogel->info()->insert($verslag);
			}
			if(Input::get("action") == "informatie") {
				$vogel->informatie = Input::get("informatie");
				$vogel->save();
			}
		}
		
		return Redirect::to_route("vogelDetail", array($id, $naam));
	}
}
