<?php


class Taken_Controller extends Base_Controller {
	public $restful = true;
	
	public $rulesNieuweTaak = array(
	);
	
	public $rulesBewerkTaak = array(
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
		
		if($lijst == "dag") {
			$frequentie = 1;
		} else if($lijst == "week") {
			$frequentie = 7;
		}
		
		$vandaag = new DateTime("today");
		$taakIDs = DB::query("SELECT DISTINCT taak.id FROM `taken` AS taak LEFT JOIN taakuitvoeringen AS uitvoering ON taak.id = uitvoering.taak_id WHERE frequentie = ? AND actief = 1 AND (DATEDIFF(?, (SELECT MAX(datum) FROM taakuitvoeringen WHERE taak_id = taak.id AND DATEDIFF(datum, ?) < 0)) > (frequentie - 1) OR (SELECT count(taak_id) FROM taakuitvoeringen WHERE taak_id = taak.id AND DATEDIFF(datum, ?) < 0) = 0 OR uitvoering.datum = ?) ORDER BY taak.naam", array($frequentie, $vandaag, $vandaag, $vandaag, $vandaag));
		$takenVandaag = array();
		$taakIDArray = array();
		foreach($taakIDs as $taakID) {
			$takenVandaag[] = Taak::find($taakID->id);
			$taakIDArray[] = $taakID->id;
		}
		if(count($taakIDArray) == 0) {
			$alleTaken = Taak::where_frequentie_and_actief($frequentie, 1)->get();
		} else {
			$alleTaken = Taak::where_frequentie_and_actief($frequentie, 1)->where_not_in('id', $taakIDArray)->get();
		}
		
		$geschiedenis = array();
		$dagNaam = array("Zondag", "Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vrijdag", "Zaterdag");
		for($i = 6; $i >= 0; $i--) {
			$datum = new DateTime("$jaar-$maand-$dag");
			$datum->sub(new DateInterval("P{$i}D"));
			$dagen[$i] = $dagNaam[$datum->format("w")] . " " . $datum->format('d-m-Y');
			$taakIDs = DB::query("SELECT DISTINCT taak.id FROM taken AS taak INNER JOIN taakuitvoeringen AS uitvoering ON taak.id = uitvoering.taak_id WHERE uitvoering.datum = ? ORDER BY taak.naam", array($datum));
			$geschiedenis[$i] = array();
			foreach($taakIDs as $taakID) {
				$taak = Taak::find($taakID->id);
				$geschiedenis[$i][] = array("taak"=>$taak, "uitvoerders"=>$taak->uitvoerders($datum));
			}
		}
		$datum = new DateTime("$jaar-$maand-$dag");
		return View::make("taken.index")
			->with("lijst", $lijst)
			->with("takenVandaag", $takenVandaag)
			->with("overigeTaken", $alleTaken)
			->with("geschiedenis", $geschiedenis)
			->with("dagen", $dagen)
			->with("geschiedenisStartDatum", $datum)
			->with("rulesNieuweTaak", $this->rulesNieuweTaak);
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
		return Redirect::to_route('taken');
	}
	
	public function get_verwijderUitvoering($id)
	{
		if(!isAdmin()) {
			return Redirect::back();
		}
		$uitvoering = Taakuitvoering::find($id);
		$uitvoering->delete();
		return Redirect::to_route('taken');
	}
}
