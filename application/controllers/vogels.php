<?php

class Vogels_Controller extends Base_Controller {
	public $restful = true;
	
	public $rulesNieuw = array(
		"naam"=>"required",
		"soort"=>"required|integer",
		"geboortedatum"=>"match:/^[0-9][0-9]?-[0-9][0-9]?-[0-9][0-9]([0-9][0-9])?$/",
		"foto"=>"image",
	);
	
	public $rulesFoto = array(
		"foto"=>"required|image",
	);
	
	public $rulesVerslag = array(
		"tekst"=>"required",
	);
	
	public $rulesEten = array(
	);
	
	public $rulesVerslagBewerk = array(
		"tekst"=>"required",
	);
	
	public $rulesAlert = array(
	);
	
	public $rulesInformatie = array(
		"naam"=>"required",
		"geboortedatum"=>"match:/^[0-9][0-9]?-[0-9][0-9]?-[0-9][0-9]([0-9][0-9])?$/",
	);
	
	public $rulesCategorie = array(
		"categorie"=>"required|integer",
		"overleidensdatum"=>"match:/^[0-9][0-9]?-[0-9][0-9]?-[0-9][0-9]([0-9][0-9])?$/",
	);
	
	public function get_index($categorie_id = 1)
	{
		$categorie = Categorie::find($categorie_id);
		return View::make("vogels.index")
			->with("categorie", $categorie)
			->with("rulesNieuw", $this->rulesNieuw);
	}
	
	public function post_index()
	{
		if(Input::has("action")) {
			if(Input::get("action") == "nieuw") {
				if(!isAdmin()) {
					return Redirect::back();
				}
				if(Validator::make(Input::all(), $this->rulesNieuw)->passes()) {
					$vogel = new Vogel();
					$vogel->categorie_id = Input::get("categorie");
					$vogel->naam = Input::get("naam");
					$vogel->geslacht = Input::get("geslacht");
					$vogel->wegen = Input::has("wegen");
					if(Input::has("geboortedatum")) {
						$vogel->geboortedatum = new DateTime(Input::get("geboortedatum"));
					}
					if(Input::has_file("foto")) {
						$vogel->foto = Input::file("foto");
					}
					Soort::find(Input::get("soort"))->vogels()->insert($vogel);
				}
			}
		}
		
		return Redirect::back();
	}
	
	public function get_grafiek($id)
	{
		return View::make("vogels.grafiek-gewicht")->with("vogel", Vogel::find($id));
	}
	
	public function get_detail($id, $naam)
	{
		$vogel = Vogel::find($id);
		if(Auth::check() && !$vogel->isGelezen(Auth::user()->id)) {
			$vogel->gelezendoor()->attach(Auth::user()->id);
		}
		
		$verslagen = $vogel->verslagen()->order_by('datum', 'desc')->order_by("id", "asc")->paginate(5);
		
		return View::make("vogels.detail")
			->with("vogel", $vogel)
			->with("verslagen", $verslagen)
			->with("rulesFoto", $this->rulesFoto)
			->with("rulesVerslag", $this->rulesVerslag)
			->with("rulesInformatie", $this->rulesInformatie)
			->with("rulesAlert", $this->rulesAlert)
			->with("rulesCategorie", $this->rulesCategorie)
			->with("rulesEten", $this->rulesEten);
	}
	
	public function post_detail($id, $naam)
	{
		$vogel = Vogel::find($id);
		if(Input::has("action")) {
			if(Input::get("action") == "foto") {
				if(!isAdmin()) {
					return Redirect::back();
				}
				if(Validator::make(Input::all(), $this->rulesFoto)->passes()) {
					$vogel->foto = Input::file("foto");
					$vogel->save();
				}
			}
			if(Input::get("action") == "verslag") {
				if(Validator::make(Input::all(), $this->rulesVerslag)->passes()) {
					$verslag = new Vogelverslag();
					$verslag->tekst = Input::get("tekst");
					$verslag->belangrijk = Input::get("belangrijk", 0);
					if(isAdmin()) {
						$verslag->gebruiker_id = Input::get("gebruiker");
						$verslag->datum = new DateTime(Input::get("verslagdatum"));
					} else {
						$verslag->gebruiker_id = Auth::user()->id;
						$verslag->datum = new DateTime("today");
					}
					$vogel->verslagen()->insert($verslag);
					if($verslag->belangrijk) {
						$vogel->gelezendoor()->delete();
					}
				}
			}
			if(Input::get("action") == "alert") {
				if(!isAdmin()) {
					return Redirect::back();
				}
				if(Validator::make(Input::all(), $this->rulesAlert)->passes()) {
					$vogel->alert = Input::get("alert");
					$vogel->save();
					if($vogel->alert != "") {
						$vogel->gelezendoor()->delete();
					}
				}
			}
			if(Input::get("action") == "informatie") {
				if(!isAdmin()) {
					return Redirect::back();
				}
				if(Validator::make(Input::all(), $this->rulesInformatie)->passes()) {
					$vogel->naam = Input::get("naam");
					$vogel->geslacht = Input::get("geslacht");
					if(Input::has("geboortedatum")) {
						$vogel->geboortedatum = new DateTime(Input::get("geboortedatum"));
					} else {
						$vogel->geboortedatum = null;
					}
					if(Input::get("eigenaar") == 0) {
						$vogel->eigenaar_id = null;
					} else {
						$eigenaar = Gebruiker::find(Input::get("eigenaar"));
						$vogel->eigenaar_id = $eigenaar->id;
					}
					$vogel->wegen = Input::has("wegen");
					$vogel->informatie = Input::get("informatie");
					$vogel->kuikens = Input::get("kuikens");
					$vogel->hamsters = Input::get("hamsters");
					$vogel->duif = Input::get("duif", 0);
					$vogel->eten_opmerking = Input::get("eten_opmerking");
					$vogel->save();
					//$vogel->gelezendoor()->delete();
				}
			}
			if(Input::get("action") == "eten") {
				if(!Auth::check()) {
					return Redirect::back();
				}
				if(Validator::make(Input::all(), $this->rulesEten)->passes()) {
					if(isAdmin()) {
						$datum = new DateTime(Input::get("datum"));
					} else {
						$datum = new DateTime("today");
					}
					if($vogel->etenIngevuld($datum)) {
						$eten = $vogel->eten($datum);
					} else {
						$eten = new Eten();
					}
					$eten->gebruiker_id = Auth::user()->id;
					$eten->vogel_id = $id;
					$eten->datum = $datum;
					$eten->kuikens = str_replace(",", ".", Input::get("kuikens"));
					$eten->hamsters = str_replace(",", ".", Input::get("hamsters"));
					$eten->duif = Input::get("duif", 0);
					$eten->opmerking = Input::get("opmerking");
					$eten->save();
				}
			}
			if(Input::get("action") == "categorie") {
				if(!isAdmin()) {
					return Redirect::back();
				}
				if(Validator::make(Input::all(), $this->rulesCategorie)->passes()) {
					$categorie = Categorie::find(Input::get("categorie"));
					$vogel->categorie_id = $categorie->id;
					if(Input::has("overleidensdatum")) {
						$vogel->overleidensdatum = new DateTime(Input::get("overleidensdatum"));
					} else {
						$vogel->overleidensdatum = null;
					}
					$vogel->save();
				}
			}
			if(Input::get("action") == "vliegpermissies") {
				if(!isAdmin()) {
					return Redirect::back();
				}
				$vogel->vliegpermissies()->delete();
				foreach(Gebruiker::all() as $gebruiker) {
					if(Input::has("gebruiker-" . $gebruiker->id)) {
						$vogel->vliegpermissies()->attach($gebruiker->id, array("opmerkingen"=>(Input::get("opmerkingen-" . $gebruiker->id) == "" ? null : Input::get("opmerkingen-" . $gebruiker->id))));
					}
				}
			}
		}
		
		return Redirect::back();
	}
	
	public function get_verslag($id)
	{
		$verslag = Vogelverslag::find($id);
		return View::make("vogels.verslag")
			->with("rulesVerslagBewerk", $this->rulesVerslagBewerk)
			->with("verslag", $verslag);
	}
	
	public function post_verslag($id)
	{
		$verslag = Vogelverslag::find($id);
		if(!(isAdmin() || (Auth::user()->id == $verslag->gebruiker_id && (new DateTime($verslag->datum_edit) == new DateTime("today"))))) {
			return Redirect::back();
		}
		if(Input::has("action")) {
			if(Input::get("action") == "bewerk") {
				if(Validator::make(Input::all(), $this->rulesVerslagBewerk)->passes()) {
					$verslag->tekst = Input::get("tekst");
					$verslag->belangrijk = Input::get("belangrijk", 0);
					if(isAdmin()) {
						$verslag->gebruiker_id = Input::get("gebruiker");
						$verslag->datum = new DateTime(Input::get("datum"));
					}
					$verslag->save();
				}
			}
			if(Input::get("action") == "verwijder") {
				$verslag->delete();
			}
		}
		
		return Redirect::to_route("vogelDetail", array($verslag->vogel->id, $verslag->vogel->naam));
	}
	
	public function get_volgorde()
	{
		return View::make("vogels.volgorde");
	}
	
	public function post_volgorde()
	{
		if(!isAdmin()) {
			return;
		}
		if(Input::has("action")) {
			if(Input::get("action") == "sorteer") {
				foreach(Vliegvolgordelijst::all() as $lijst) {
					$key = "lijst_" . $lijst->id;
					if(!Input::has($key)) {
						$data = array("vogel"=>array());
					} else {
						parse_str(Input::get($key), $data);
					}
					
					foreach($data["vogel"] as $volgorde=>$pivotID) {
						DB::table('vliegvolgorde')->where_id($pivotID)->update(array("vliegvolgordelijst_id"=>$lijst->id, "volgorde"=>$volgorde));
					}
				}
				parse_str(Input::get("lijsten"), $data);
				foreach($data["lijsten"] as $volgorde=>$lijstID) {
					DB::table('vliegvolgordelijsten')->where_id($lijstID)->update(array("volgorde"=>$volgorde));
				}
			}
			if(Input::get("action") == "delete") {
				parse_str(Input::get("elements"), $data);
				var_dump($data);
				if(isset($data["vogel"])) {
					foreach($data["vogel"] as $pivotID) {
						DB::table('vliegvolgorde')->where_id($pivotID)->delete();
					}
				}
				if(isset($data["lijsten"])) {
					foreach($data["lijsten"] as $lijstID) {
						$lijst = Vliegvolgordelijst::find($lijstID);
						$lijst->vogels()->delete();
						$lijst->delete();
					}
				}
			}
			if(Input::get("action") == "nieuweVogel") {
				$lijst = Vliegvolgordelijst::find(Input::get("lijst"));
				$lijst->vogels()->attach(Input::get("vogel"), array("opmerkingen"=>Input::get("opmerkingen") == "" ? null : Input::get("opmerkingen"), "volgorde"=>$lijst->vogels()->count()));
				return Redirect::to_route("vliegvolgorde");
			}
			if(Input::get("action") == "nieuweLijst") {
				$lijst = new Vliegvolgordelijst();
				$lijst->naam = Input::get("naam");
				$lijst->volgorde = Vliegvolgordelijst::count();
				$lijst->save();
				return Redirect::to_route("vliegvolgorde");
			}
		}
	}
	
	public function get_volgordepdf()
	{
		$pdf = new DOMPDF();
		$pdf->set_paper("a4");
		$pdf->load_html(View::make("vogels.volgorde-pdf"));
		$pdf->render();
		return Response::make($pdf->output(), 200, array("Content-Type"=>"application/pdf", "Content-Disposition"=>"attachment; filename=vliegvolgorde.pdf"));
	}
	
	public function get_voeren()
	{
		return View::make("vogels.voeren");
	}
}
