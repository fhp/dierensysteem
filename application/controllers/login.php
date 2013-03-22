<?php

class Login_Controller extends Base_Controller {
	public $restful = true;
	
	public function get_login()
	{
		return View::make('login');
	}
	
	public function post_login()
	{
		if (Auth::attempt($_POST)) {
			return Redirect::to_route('home');
		} else {
			return View::make('login')->with('error', true);
		}
	}
	
	public function get_logout()
	{
		Auth::logout();
		return Redirect::to_route('home');
	}
}