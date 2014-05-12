<?php

class Wegen_Controller extends Base_Controller {
	public $restful = true;
	
	public function get_index($jaar = null, $maand = null, $dag = null)
	{
		if(isAdmin() && $jaar !== null && $maand !== null && $dag !== null) {
			$datum = new DateTime("$dag-$maand-$jaar");
		} else {
			$datum = new DateTime("today");
		}
		
		return View::make("wegen.index")
			->with("datum", $datum);
	}
	
	public function post_index($jaar = null, $maand = null, $dag = null)
	{
		if(isAdmin() && $jaar !== null && $maand !== null && $dag !== null) {
			$datum = new DateTime("$dag-$maand-$jaar");
		} else {
			$datum = new DateTime("today");
		}
		
		foreach(Vogel::all() as $vogel) {
			$gewicht = Input::get("vogel_" . $vogel->id);
			$braakbal = Input::get("vogel_" . $vogel->id . "_braakbal", 0);
			if(is_numeric($gewicht)) {
				$vogel->set_gewicht($gewicht, $datum);
			}
			$vogel->set_braakbal($braakbal, $datum);
		}
		return Redirect::back();
	}
	
	public function get_pdf()
	{
		$pdf = new DOMPDF();
		$pdf->set_paper("a4", "landscape");
		$pdf->load_html(View::make("wegen.pdf"));
		$pdf->render();
		return Response::make($pdf->output(), 200, array("Content-type"=>"application/pdf", "Content-Disposition"=>"attachment; filename=lijst.pdf"));
	}
	
	public function get_pdfHtml()
	{
		return View::make("wegen.pdf");
	}
}
