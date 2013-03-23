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
		if(Validator::make(Input::all(), $this->rulesEvenement)->passes()) {
			$evenement = new Evenement();
			$evenement->naam = Input::get("naam");
			$evenement->beschrijving = Input::get("beschrijving");
			$evenement->datum = new DateTime(Input::get("datum"));
			$evenement->save();
			return Redirect::back();
		} else {
			return Redirect::back()->with('error', 'Het is niet gelukt om het evenement aan te maken.');
		}
	}
	
	public function post_aanwezig($jaar, $maand, $dag)
	{
		$datum = new DateTime("$jaar-$maand-$dag");
		if(!Auth::user()->isAanwezig($datum)) {
			$aanwezigheid = new Aanwezigheid();
			$aanwezigheid->datum = $datum;
			Auth::user()->aanwezigheden()->insert($aanwezigheid);
		}
		return Redirect::back();
	}
	
	public function post_afwezig($jaar, $maand, $dag)
	{
		$datum = new DateTime("$jaar-$maand-$dag");
		if($datum >= new DateTime("today +4 days")) {
			$aanwezigheid = Auth::user()->aanwezigheid($datum);
			$aanwezigheid->delete();
		}
		
		return Redirect::back();
	}
}
