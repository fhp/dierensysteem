<?php

class Wegen_Controller extends Base_Controller {
	public $restful = true;
	
	public function get_index()
	{
		return View::make("wegen.index");
	}
	
	public function post_index()
	{
		foreach(Vogel::all() as $vogel) {
			$gewicht = Input::get("vogel_" . $vogel->id);
			if(is_numeric($gewicht)) {
				$vogel->set_gewicht($gewicht);
			}
		}
		return Redirect::back();
	}
}
