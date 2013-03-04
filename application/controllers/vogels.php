<?php

class Vogels_Controller extends Base_Controller {
	public $restful = true;
	
	public function get_index()
	{
		$vogels = Vogel::all();
		return View::make("vogels.index")->with("vogels", $vogels);
	}
	
	public function get_detail($id, $naam)
	{
		$vogel = Vogel::find($id);
		
		$summary = array();
		$summary["Soort"] = $vogel->soort->naam;
		$summary["Geslacht"] = Str::title($vogel->geslacht);
		$summary["Leeftijd"] = $vogel->leeftijd;
		
		$notities = $vogel->info()->order_by("created_at", "desc")->get();
		$verslagen = $vogel->verslagen()->order_by('datum', 'desc')->order_by("id", "desc")->paginate(5);
		
		return View::make("vogels.detail")
			->with("vogel", $vogel)
			->with("summary", $summary)
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
		}
		
		return Redirect::to_route("vogelDetail", array($id, $naam));
	}
	
	public function get_nieuw()
	{
		$soorten = array();
		foreach(Soort::all() as $soort) {
			$soorten[$soort->id] = $soort->naam;
		}
		return View::make("vogels.nieuw")->with("soorten", $soorten);
	}
	
	public function post_nieuw()
	{
		$vogel = new Vogel();
		$vogel->naam = Input::get("naam");
		$vogel->geslacht = Input::get("geslacht");
		if(Input::has_file("foto")) {
			$vogel->foto = Input::file("foto");
		}
		Soort::find(Input::get("soort"))->vogels()->insert($vogel);
		
		return Redirect::to_route("vogels");
	}
}
