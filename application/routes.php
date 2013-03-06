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
	
	Route::any('taken/(:num)/(:any)', array("as"=>"taakDetail", 'uses'=>'taken@detail'));
	Route::any('taken', array("as"=>"taken", 'uses'=>'taken@index'));
});

Route::any('login', array("as"=>"login", 'uses'=>'login@login'));
Route::any('logout', array("as"=>"logout", 'uses'=>'login@logout'));

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

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
