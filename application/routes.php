<?php

Route::group(array('before' => 'auth'), function() {
	Route::any('/', array("as"=>"home", 'uses'=>'home@index'));

	Route::any('dagboek', array("as"=>"dagboek", 'uses'=>'dagboek@index'));
	
	Route::any('soorten', array("as"=>"soorten", 'uses'=>'soorten@index'));
	Route::any('soorten/(:num)/(:any)', array("as"=>"soortDetail", 'uses'=>'soorten@detail'));
	
	Route::any('gebruikers/(:num)/(:any)', array("as"=>"gebruikerDetail", 'uses'=>'gebruikers@detail'));
	Route::any('gebruikers', array("as"=>"gebruikers", 'uses'=>'gebruikers@index'));

	Route::any('vogels/(:num)/(:any)', array("as"=>"vogelDetail", 'uses'=>'vogels@detail'));
	Route::any('vogels', array("as"=>"vogels", 'uses'=>'vogels@index'));

	Route::get('taken', array("as"=>"taken", function()
	{
		return View::make('taken');
	}));
});

Route::get('login', array("as"=>"login", function()
{
	return View::make('login');
}));

Route::post("login", function()
{
	if (Auth::attempt($_POST)) {
		return Redirect::to_route('home');
	} else {
		return View::make('login')->with('error', true);
	}
});

Route::get('logout', array("as"=>"logout", function()
{
	Auth::logout();
	return Redirect::to_route('home');
}));

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Route::get('/', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});