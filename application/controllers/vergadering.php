<?php

class Vergadering_Controller extends Base_Controller {
	public $restful = true;
	
	public $rulesAgendapunt = array(
		"titel"=>"required",
	);
	public $rulesNotule = array(
		"omschrijving"=>"required",
	);
	public $rulesActiepunt = array(
		"titel"=>"required",
		"deadline"=>"match:/^[0-9][0-9]?-[0-9][0-9]?-[0-9][0-9]([0-9][0-9])?$/",
	);
	
	public function get_index()
	{
		$agendapunten = Agendapunt::where("voltooid", "=", 0)->order_by("id", "desc")->paginate(10);
		$actiepunten = Actiepunt::where("voltooid", "=", 0)->order_by("deadline", "asc")->paginate(10);
		return View::make("vergadering.index")
			->with("rulesAgendapunt", $this->rulesAgendapunt)
			->with("agendapunten", $agendapunten)
			->with("actiepunten", $actiepunten);
	}
	
	public function post_index()
	{
		if(Input::has("action")) {
			if(Input::get("action") == "agendapunt") {
				if(Validator::make(Input::all(), $this->rulesAgendapunt)->passes()) {
					$agendapunt = new Agendapunt();
					$agendapunt->gebruiker_id = Auth::user()->id;
					$agendapunt->titel = Input::get("titel");
					$agendapunt->omschrijving = trim(Input::get("omschrijving")) == "" ? null : Input::get("omschrijving");
					$agendapunt->save();
				}
			}
		}
		
		return Redirect::to_route("vergadering");
	}
	
	public function get_archief()
	{
		$agendapunten = Agendapunt::order_by("id", "desc")->paginate(10);
		$actiepunten = Actiepunt::order_by("deadline", "asc")->paginate(10);
		return View::make("vergadering.archief")
			->with("agendapunten", $agendapunten)
			->with("actiepunten", $actiepunten);
	}
	
	public function get_agendapunt($id)
	{
		$agendapunt = Agendapunt::find($id);
		return View::make("vergadering.agendapunt")
			->with("rulesVerslagBewerk", $this->rulesVerslagBewerk)
			->with("rulesNotule", $this->rulesNotule)
			->with("rulesActiepunt", $this->rulesActiepunt)
			->with("agendapunt", $agendapunt);
	}
	
	public function post_agendapunt($id)
	{
		$agendapunt = Agendapunt::find($id);
		if(Input::has("action")) {
			if(Input::get("action") == "bewerk") {
				if(Validator::make(Input::all(), $this->rulesAgendapunt)->passes()) {
					$agendapunt->titel = Input::get("titel");
					$agendapunt->omschrijving = trim(Input::get("omschrijving")) == "" ? null : Input::get("omschrijving");
					$agendapunt->save();
				}
			}
			if(Input::get("action") == "verwijder") {
				$agendapunt->delete();
			}
			if(Input::get("action") == "notule") {
				if(Validator::make(Input::all(), $this->rulesNotule)->passes()) {
					$notule = new Notule();
					$notule->gebruiker_id = Auth::user()->id;
					$notule->omschrijving = Input::get("omschrijving");
					$agendapunt->notulen()->insert($notule);
				}
				$agendapunt->voltooid = Input::get("sluiten", 0);
				$agendapunt->save();
			}
			if(Input::get("action") == "notule_delete") {
				if(Validator::make(Input::all(), $this->rulesNotule)->passes()) {
					$notule = Nodule::get(Input::get("nodule_id"));
					$notule->delete();
				}
			}
			if(Input::get("action") == "actiepunt") {
				if(Validator::make(Input::all(), $this->rulesActiepunt)->passes()) {
					$actiepunt = new Actiepunt();
					$actiepunt->titel = Input::get("titel");
					$actiepunt->gebruiker_id = Gebruiker::find(Input::get("gebruiker"))->id;
					$actiepunt->omschrijving = Input::get("omschrijving");
					if(Input::has("deadline") && trim(Input::get("deadline")) != "") {
						$actiepunt->deadline = new DateTime(Input::get("deadline"));
					} else {
						$actiepunt->deadline = null;
					}
					$agendapunt->actiepunten()->insert($actiepunt);
				}
			}
		}
		
		return Redirect::to_route("vergaderingAgendapunt", array($id));
	}
	
	public function get_actiepunt($id)
	{
		$actiepunt = Actiepunt::find($id);
		return View::make("vergadering.actiepunt")
			->with("rulesActiepunt", $this->rulesActiepunt)
			->with("actiepunt", $actiepunt);
	}
	
	public function post_actiepunt($id)
	{
		$actiepunt = Actiepunt::find($id);
		if(Input::has("action")) {
			if(Input::get("action") == "actiepunt") {
				$actiepunt->opmerkingen = Input::get("opmerkingen");
				$actiepunt->voltooid = Input::get("sluiten", 0);
				$actiepunt->save();
			}
		}
		
		return Redirect::to_route("vergaderingActiepunt", array($id));
	}
	
	public function get_notule($id)
	{
		$notule = Notule::find($id);
		
		return View::make("vergadering.notule")
			->with("notule", $notule);
	}
	
	public function post_notule($id)
	{
		$notule = Notule::find($id);
		$notule->omschrijving = Input::get("omschrijving");
		$notule->save();
		return Redirect::to_route("vergaderingAgendapunt", array($notule->agendapunt->id));
	}
	
	public function get_notuleDelete($id)
	{
		$notule = Notule::find($id);
		$agendapuntID = $notule->agendapunt->id;
		$notule->delete();
		return Redirect::to_route("vergaderingAgendapunt", array($agendapuntID));
	}
}
