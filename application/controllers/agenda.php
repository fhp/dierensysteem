<?php

class Agenda_Controller extends Base_Controller {
	public $restful = true;
	
	public $rulesEvenement = array(
		"naam"=>"required",
	);
	
	public function get_index($jaar = null, $maand = null)
	{
		if($jaar === null) {
			$jaar = date("Y");
		}
		if($maand === null) {
			$maand = date("m");
		}
		
		$aanwezigen = array();
		for($i = 1; $i <= date('t',mktime(0,0,0,$maand,1,$jaar)); $i++) {
			$datum = new DateTime("$jaar-$maand-$i");
			$data["datum"] = $datum;
			$data["aanwezigen"] = Aanwezigheid::where_datum($datum)->get();
			$data["evenementen"] = Evenement::where_datum($datum)->get();
			$aanwezigen[$i] = $data;
		}
		
		return View::make("agenda.index")
			->with("maand", $maand)
			->with("jaar", $jaar)
			->with("aanwezigen", $aanwezigen)
			->with("rulesEvenement", $this->rulesEvenement);
	}
	
	public function post_index($jaar = null, $maand = null)
	{
		if($jaar === null) {
			$jaar = date("Y");
		}
		if($maand === null) {
			$maand = date("m");
		}
		
		if(Input::has("action")) {
			if(Input::get("action") == "nieuwEvenement") {
				if(Validator::make(Input::all(), $this->rulesEvenement)->passes()) {
					$evenement = new Evenement();
					$evenement->naam = Input::get("naam");
					$evenement->beschrijving = Input::get("beschrijving");
					$evenement->datum = new DateTime(Input::get("datum"));
					$evenement->save();
				}
			}
		}
		
		return Redirect::to_route("agenda", array($jaar, $maand));
	}
	
	public function get_aanwezig($jaar, $maand, $dag)
	{
		$datum = new DateTime("$jaar-$maand-$dag");
		if(!Auth::user()->isAanwezig($datum)) {
			$aanwezigheid = new Aanwezigheid();
			$aanwezigheid->datum = $datum;
			Auth::user()->aanwezigheden()->insert($aanwezigheid);
		}
		return Redirect::to_route('agenda', array($jaar, $maand));
	}
	
	public function get_afwezig($jaar, $maand, $dag)
	{
		$datum = new DateTime("$jaar-$maand-$dag");
		
		$aanwezigheid = Auth::user()->aanwezigheid($datum);
		$aanwezigheid->delete();
		
		return Redirect::to_route('agenda', array($jaar, $maand));
	}
}
