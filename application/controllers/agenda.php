<?php

class Agenda_Controller extends Base_Controller {
	public $restful = true;
	
	public $rulesEvenement = array(
		"naam"=>"required",
	);
	
	public function get_week($jaar = null, $maand = null, $dag = null)
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
		
		$dagenData = array();
		for($i = 0; $i < 7; $i++) {
			$datum = new DateTime("$jaar-$maand-$dag");
			$datum->add(new DateInterval("P{$i}D"));
			$data["datum"] = $datum;
			$data["aanwezigen"] = Aanwezigheid::where_datum($datum)->get();
			$data["evenementen"] = Evenement::where_datum($datum)->get();
			$dagenData[$i] = $data;
		}
		
		return View::make("agenda.week")
			->with("maand", $maand)
			->with("jaar", $jaar)
			->with("dag", $dag)
			->with("dagenData", $dagenData)
			->with("rulesEvenement", $this->rulesEvenement);
	}
	
	public function get_maand($jaar = null, $maand = null)
	{
		if($jaar === null) {
			$jaar = date("Y");
		}
		if($maand === null) {
			$maand = date("m");
		}
		
		$dagenData = array();
		for($i = 1; $i <= date('t',mktime(0,0,0,$maand,1,$jaar)); $i++) {
			$datum = new DateTime("$jaar-$maand-$i");
			$data["datum"] = $datum;
			$data["aanwezigen"] = Aanwezigheid::where_datum($datum)->get();
			$data["evenementen"] = Evenement::where_datum($datum)->get();
			$dagenData[$i] = $data;
		}
		
		return View::make("agenda.maand")
			->with("maand", $maand)
			->with("jaar", $jaar)
			->with("dagenData", $dagenData)
			->with("rulesEvenement", $this->rulesEvenement);
	}
	
	public function post_evenement()
	{
		if(!isAdmin()) {
			return Redirect::back();
		}
		if(Validator::make(Input::all(), $this->rulesEvenement)->passes()) {
			$evenement = new Evenement();
			$evenement->naam = Input::get("naam");
			$evenement->beschrijving = Input::get("beschrijving");
			$evenement->datum = new DateTime(Input::get("datum"));
			$evenement->save();
			return Redirect::back();
		} else {
			return Redirect::back();
		}
	}
	
	public function post_deleteEvenement($evenementID)
	{
		if(!isAdmin()) {
			return Redirect::back();
		}
		Evenement::find($evenementID)->delete();
		return Redirect::back();
	}
	
	public function post_aanwezig($jaar, $maand, $dag, $gebruiker_id = null)
	{
		$datum = new DateTime("$jaar-$maand-$dag");
		if($gebruiker_id === null) {
			$gebruiker = Auth::user();
		} else if(isAdmin()) {
			$gebruiker = Gebruiker::find($gebruiker_id);
		} else {
			Redirect::back();
		}
		if(!$gebruiker->isAanwezig($datum)) {
			$aanwezigheid = new Aanwezigheid();
			$aanwezigheid->datum = $datum;
			$gebruiker->aanwezigheden()->insert($aanwezigheid);
		}
		return Redirect::back();
	}
	
	public function post_afwezig($jaar, $maand, $dag, $gebruiker_id = null)
	{
		$datum = new DateTime("$jaar-$maand-$dag");
		if($gebruiker_id === null) {
			$gebruiker = Auth::user();
		} else if(isAdmin()) {
			$gebruiker = Gebruiker::find($gebruiker_id);
		} else {
			Redirect::back();
		}
		if($datum >= new DateTime("today +4 days") || isAdmin()) {
			$aanwezigheid = $gebruiker->aanwezigheid($datum);
			$aanwezigheid->delete();
		}
		
		return Redirect::back();
	}
	
	public function get_aanwezigheid($id)
	{
		if(!isAdmin()) {
			return Response::error('404');
		}
		$aanwezigheid = Aanwezigheid::find($id);
		return View::make("agenda.aanwezigheid")
			->with("aanwezigheid", $aanwezigheid);
	}
	
	public function post_aanwezigheid($id)
	{
		if(!isAdmin()) {
			return Response::error('404');
		}
		$aanwezigheid = Aanwezigheid::find($id);
		$aanwezigheid->opmerkingen = Input::get("opmerkingen");
		$aanwezigheid->actief = Input::get("actief", 0);
		$aanwezigheid->save();
		return Redirect::to_route("home");
	}
}
