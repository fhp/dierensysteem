<?php


class Taken_Controller extends Base_Controller {
	public $restful = true;
	
	public $rulesNieuweTaak = array(
	);
	
	public $rulesBewerkTaak = array(
	);
	
	public $rulesAdminTaakUitvoering = array(
		"datum"=>"match:/^[0-9][0-9]?-[0-9][0-9]?-[0-9][0-9]([0-9][0-9])?$/",
		"gebruiker"=>"exists:gebruikers,id",
		"taak"=>"exists:taken,id",
	);
	
	public function get_index($lijst = "dag", $jaar = null, $maand = null, $dag = null)
	{
		if($jaar === null) {
			$jaar = date("Y");
		}
		if($maand === null) {
			$maand = date("m");
		}
		if($dag === null) {
			$dag = date("d");
		}
		$datum = new DateTime("$jaar-$maand-$dag");
		
		return View::make("taken.index")
			->with("lijst", $lijst)
			->with("datum", $datum)
			->with("rulesNieuweTaak", $this->rulesNieuweTaak)
			->with("rulesAdminTaakUitvoering", $this->rulesAdminTaakUitvoering);
	}
	
	public function post_index($jaar = null, $maand = null, $dag = null)
	{
		if($jaar === null) {
			$jaar = date("Y");
		}
		if($maand === null) {
			$maand = date("m");
		}
		if($dag === null) {
			$dag = date("d");
		}
		
		if(Input::has("action")) {
			if(Input::get("action") == "uitvoeringen") {
				if(fcGast()) {
					return Redirect::back();
				}
				foreach(DB::table("taakuitvoeringen")->join("taken", "taakuitvoeringen.taak_id", "=", "taken.id")->where_datum(new DateTime("today"))->where_frequentie(Input::get("frequentie"))->where_gebruiker_id(Auth::user()->id)->get("taakuitvoeringen.id") as $uitvoering) {
					$uitvoering = Taakuitvoering::find($uitvoering->id);
					$uitvoering->delete();
				}
				foreach(Input::get() as $naam=>$waarde) {
					if(substr($naam, 0, 5) == "taak_") {
						$uitvoering = new Taakuitvoering();
						$uitvoering->taak_id = substr($naam, 5);
						$uitvoering->datum = new DateTime("today");
						Auth::user()->taakuitvoeringen()->insert($uitvoering);
					}
				}
			}
			if(Input::get("action") == "nieuweTaak") {
				if(!isAdmin()) {
					return Redirect::back();
				}
				if(Validator::make(Input::all(), $this->rulesNieuweTaak)->passes()) {
					$taak = new Taak();
					$taak->naam = Input::get("naam");
					$taak->beschrijving = Input::get("beschrijving");
					$taak->frequentie = Input::get("frequentie");
					$taak->actief = 1;
					$taak->save();
				}
			}
			if(Input::get("action") == "taakuitvoering") {
				if(!isAdmin()) {
					return Redirect::back();
				}
				if(Validator::make(Input::all(), $this->rulesAdminTaakUitvoering)->passes()) {
					$uitvoering = new Taakuitvoering();
					$uitvoering->gebruiker_id = Input::get("gebruiker");
					$uitvoering->taak_id = Input::get("taak");
					$uitvoering->datum = new DateTime(Input::get("datum"));
					$uitvoering->save();
				}
			}
		}
		
		return Redirect::back();
	}
	
	public function get_bewerk($id)
	{
		$taak = Taak::find($id);
		return View::make("taken.bewerk")
			->with("taak", $taak)
			->with("rulesBewerkTaak", $this->rulesBewerkTaak);
	}
	
	public function post_bewerk($id)
	{
		if(!isAdmin()) {
			return Redirect::back();
		}
		$taak = Taak::find($id);
		if(Input::has("action")) {
			if(Input::get("action") == "bewerk") {
				if(Validator::make(Input::all(), $this->rulesBewerkTaak)->passes()) {
					$taak->naam = Input::get("naam");
					$taak->beschrijving = Input::get("beschrijving");
					$taak->frequentie = Input::get("frequentie");
					$taak->save();
				}
			}
			if(Input::get("action") == "verwijder") {
				$taak->actief = 0;
				$taak->save();
			}
		}
		return Redirect::to_route('taken', array($taak->frequentie == 1 ? "dag" : "week"));
	}
	
	public function get_gedaan($id)
	{
		$taak = Taak::find($id);
		if(!$taak->gedaan(Auth::user()->id)) {
			$taak->isGedaan(Auth::user()->id);
		} else {
			$taak->isNietGedaan(Auth::user()->id);
		}
		return Redirect::back();
	}
	
	public function get_verwijderUitvoering($id)
	{
		if(!isAdmin()) {
			return Redirect::back();
		}
		$uitvoering = Taakuitvoering::find($id);
		$uitvoering->delete();
		return Redirect::back();
	}
}
