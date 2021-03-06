<?php

Route::group(array('before' => 'auth'), function() {
	Route::any('/', array("as"=>"home", 'uses'=>'home@index'));
	Route::any('mededelingen/(:num)', array("as"=>"mededelingenEdit", 'uses'=>'home@mededelingen'));
	
	Route::any('dagboek', array("as"=>"dagboek", 'uses'=>'dagboek@index'));
	Route::any('dagboek/(:num)', array("as"=>"dagboekBewerk", 'uses'=>'dagboek@verslag'));
	
	Route::any('soorten', array("as"=>"soorten", 'uses'=>'soorten@index'));
	Route::any('soorten/(:num)/(:any)', array("as"=>"soortDetail", 'uses'=>'soorten@detail'));
	
	Route::any('gebruikers/(:num)/(:any)/uren/(:num)/(:num)', array("as"=>"gebruikerUren", 'uses'=>'gebruikers@uren'));
	Route::any('gebruikers/(:num)/(:any)/uren', array("as"=>"gebruikerUren", 'uses'=>'gebruikers@uren'));
	Route::any('gebruikers/(:num)/(:any)/urenlijst/(:num)/(:num)', array("as"=>"gebruikerUrenPdf", 'uses'=>'gebruikers@urenpdf'));
	Route::any('gebruikers/(:num)/(:any)/urenlijst', array("as"=>"gebruikerUrenPdf", 'uses'=>'gebruikers@urenpdf'));
	Route::any('gebruikers/(:num)/(:any)', array("as"=>"gebruikerDetail", 'uses'=>'gebruikers@detail'));
	Route::any('gebruikers/bewerkAanwezigheid/(:num)', array("as"=>"gebruikerUrenEdit", 'uses'=>'gebruikers@urenedit'));
	Route::any('gebruikers', array("as"=>"gebruikers", 'uses'=>'gebruikers@index'));
	
	Route::any('vliegvolgorde/vliegvolgorde.pdf', array("as"=>"vliegvolgordepdf", 'uses'=>'vogels@volgordepdf'));
	Route::any('vliegvolgorde', array("as"=>"vliegvolgorde", 'uses'=>'vogels@volgorde'));
	
	Route::get('vogels/grafiek/(:num)', array("as"=>"vogelgrafiek", 'uses'=>'vogels@grafiek'));
	Route::any('vogels/verslag/(:num)', array("as"=>"vogelVerslagEdit", 'uses'=>'vogels@verslag'));
	Route::any('vogels/(:num)/(:all)', array("as"=>"vogelDetail", 'uses'=>'vogels@detail'));
	Route::any('vogels/(:num)', array("as"=>"vogels", 'uses'=>'vogels@index'));
	Route::any('vogels', array("as"=>"vogels", 'uses'=>'vogels@index'));
	
	Route::any('wegen/(:num)/(:num)/(:num)', array("as"=>"wegen", 'uses'=>'wegen@index'));
	Route::any('wegen', array("as"=>"wegen", 'uses'=>'wegen@index'));
	Route::any('wegen/lijst.pdf', array("as"=>"wegenPdf", 'uses'=>'wegen@pdf'));
	
	Route::any('voeren', array("as"=>"voeren", 'uses'=>'vogels@voeren'));
	
	Route::post('agenda/(:num)/(:num)/(:num)/aanwezig/(:num)', array("as"=>"agendaAanmeldenAdmin", 'uses'=>'agenda@aanwezig'));
	Route::post('agenda/(:num)/(:num)/(:num)/afwezig/(:num)', array("as"=>"agendaAfmeldenAdmin", 'uses'=>'agenda@afwezig'));
	Route::post('agenda/(:num)/(:num)/(:num)/aanwezig', array("as"=>"agendaAanmelden", 'uses'=>'agenda@aanwezig'));
	Route::post('agenda/(:num)/(:num)/(:num)/afwezig', array("as"=>"agendaAfmelden", 'uses'=>'agenda@afwezig'));
	Route::post('agenda/evenement/delete/(:num)', array("as"=>"agendaDeleteEvenement", 'uses'=>'agenda@deleteEvenement'));
	Route::post('agenda/evenement', array("as"=>"agendaEvenement", 'uses'=>'agenda@evenement'));
	Route::get('agenda/maand/(:num)/(:num)', array("as"=>"agendaMaand", 'uses'=>'agenda@maand'));
	Route::get('agenda/maand', array("as"=>"agendaMaand", 'uses'=>'agenda@maand'));
	Route::get('agenda/week/(:num)/(:num)/(:num)', array("as"=>"agendaWeek", 'uses'=>'agenda@week'));
	Route::get('agenda/week', array("as"=>"agendaWeek", 'uses'=>'agenda@week'));
	Route::any('agenda/aanwezigheid/(:num)', array("as"=>"agendaAanwezigheid", 'uses'=>'agenda@aanwezigheid'));
	Route::get('agenda', array("as"=>"agenda", 'uses'=>'agenda@week'));
	
	Route::get('taken/verwijder/(:num)', array("as"=>"taakVerwijderUitvoering", 'uses'=>'taken@verwijderUitvoering'));
	Route::get('taken/gedaan/(:num)', array("as"=>"taakGedaan", 'uses'=>'taken@gedaan'));
	Route::any('taken/bewerk/(:num)', array("as"=>"taakBewerk", 'uses'=>'taken@bewerk'));
	Route::any('taken/nieuw/(:num)/(:num)/(:num)/(:num)/(:num)', array("as"=>"taakNieuweUitvoering", 'uses'=>'taken@nieuweUitvoering'));
	Route::any('taken/(:any)/(:num)/(:num)/(:num)', array("as"=>"taken", 'uses'=>'taken@index'));
	Route::any('taken/(:any)', array("as"=>"taken", 'uses'=>'taken@index'));
	Route::any('taken', array("as"=>"taken", 'uses'=>'taken@index'));
	
	Route::post('inklokken', array("as"=>"inklokken", 'uses'=>'gebruikers@inklokken'));
	Route::post('uitklokken', array("as"=>"uitklokken", 'uses'=>'gebruikers@uitklokken'));
	
	if(isAdmin()) {
		Route::any('vergadering', array("as"=>"vergadering", 'uses'=>'vergadering@index'));
		Route::any('vergadering/archief', array("as"=>"vergaderingArchief", 'uses'=>'vergadering@archief'));
		Route::any('vergadering/agendapunt/(:num)', array("as"=>"vergaderingAgendapunt", 'uses'=>'vergadering@agendapunt'));
		Route::any('vergadering/actiepunt/(:num)', array("as"=>"vergaderingActiepunt", 'uses'=>'vergadering@actiepunt'));
		Route::any('vergadering/notule/(:num)', array("as"=>"vergaderingNotule", 'uses'=>'vergadering@notule'));
		Route::any('vergadering/notule/(:num)/delete', array("as"=>"vergaderingNotuleDelete", 'uses'=>'vergadering@notuleDelete'));
		Route::any('vergadering/vergadering/(:any)', array("as"=>"vergaderingVergadering", "uses"=>"vergadering@vergadering"));
	}
});

Route::any('veranderWachtwoord', array("as"=>"veranderWachtwoord", 'uses'=>'gebruikers@veranderWachtwoord'));
Route::any('login/(:any)', array("as"=>"loginAs", 'uses'=>'login@login'));
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
	if(fcGast() && Request::method() == "GET") {
		return;
	}
	if (Auth::guest()) return Redirect::to('login');
	if (Hash::check('', Auth::user()->wachtwoord)) return Redirect::to('veranderWachtwoord');
});
