<?php


class Taken_Controller extends Base_Controller {
	public $restful = true;
	
	public function get_index()
	{
		$datum = new DateTime("today");
		$taakIDs = DB::query("SELECT DISTINCT taak.id FROM `taken` AS taak LEFT JOIN taakuitvoeringen AS uitvoering ON taak.id = uitvoering.taak_id WHERE DATEDIFF(?, (SELECT MAX(datum) FROM taakuitvoeringen WHERE taak_id = taak.id AND DATEDIFF(datum, ?) < 0)) > frequentie OR (SELECT count(taak_id) FROM taakuitvoeringen WHERE taak_id = taak.id AND DATEDIFF(datum, ?) < 0) = 0 OR uitvoering.datum = ?", array($datum, $datum, $datum, $datum));
		$takenVandaag = array();
		$taakIDArray = array();
		foreach($taakIDs as $taakID) {
			$takenVandaag[] = Taak::find($taakID->id);
			$taakIDArray[] = $taakID->id;
		}
		if(count($taakIDArray) == 0) {
			$alleTaken = Taak::all();
		} else {
			$alleTaken = Taak::where_not_in('id', $taakIDArray)->get();
		}
		
		$geschiedenis = array();
		$dag = array("Zondag", "Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vrijdag", "Zaterdag");
		for($i = 7; $i >= 1; $i--) {
			$datum = new DateTime("today $i days ago");
			$dagen[$i] = $dag[$datum->format("w")] . " " . $datum->format('d-m-Y');
			$taakIDs = DB::query("SELECT DISTINCT taak.id FROM taken AS taak INNER JOIN taakuitvoeringen AS uitvoering ON taak.id = uitvoering.taak_id WHERE uitvoering.datum = ?", array($datum));
			$geschiedenis[$i] = array();
			foreach($taakIDs as $taakID) {
				$taak = Taak::find($taakID->id);
				$geschiedenis[$i][] = array("taak"=>$taak, "uitvoerders"=>$taak->uitvoerders($datum));
			}
		}
		return View::make("taken.index")
			->with("takenVandaag", $takenVandaag)
			->with("overigeTaken", $alleTaken)
			->with("geschiedenis", $geschiedenis)
			->with("dagen", $dagen);
	}
	
	public function get_detail($id, $naam)
	{
		$taak = Taak::find($id);
		return View::make("taken.detail")
			->with("taak", $taak);
	}
	
	public function get_gedaan($id) // TODO: post actie maken
	{
		$taak = Taak::find($id);
		$taak->isGedaan(Auth::user()->id);
		return Redirect::to_route('taken');
	}
}
