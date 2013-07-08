<?php

class Login_Controller extends Base_Controller {
	public $restful = true;
	
	public function get_login($naam = null)
	{
		Auth::logout();
		return View::make('login')
			->with("username", $naam);
	}
	
	public function post_login()
	{
		if (Auth::attempt(array_merge($_POST, array("nonactief"=>0)))) {
			return Redirect::to_route('home');
		} else {
			return View::make('login')
				->with("username", Input::get("username"))
				->with('error', true);
		}
	}
	
	public function get_logout()
	{
		Auth::logout();
		return Redirect::to_route('home');
	}
}
