<?php

function routeInModule($naam1, $naam2 = null) {
	$routeNaam = array_get(Request::route()->action, "as");
	if(substr($routeNaam, 0, strlen($naam1)) == $naam1) {
		return true;
	}
	if($naam2 === null) {
		return false;
	}
	if(substr($routeNaam, 0, strlen($naam2)) == $naam2) {
		return true;
	}
	return false;
}

$menu = array(
	array(Navigation::HEADER, 'Algemeen', false, false, null),
	array('Home', URL::to_route('home'), routeInModule('home')),
	array('Agenda', URL::to_route('agenda'), routeInModule('agenda')),
	array('Dagboek', URL::to_route('dagboek'), routeInModule('dagboek')),
	array('Dieren', URL::to_route('vogels', (Request::route()->is('vogelDetail') ? array(Vogel::find(URI::segment(2))->categorie_id) : array())), routeInModule('vogel')),
	array('Wegen', URL::to_route('wegen'), routeInModule('wegen')),
	array('Voeren', URL::to_route('voeren'), routeInModule('voeren')),
	array('Taken', URL::to_route('taken'), routeInModule('taken', 'taak')),
	array('Gebruikers', URL::to_route('gebruikers'), routeInModule('gebruiker')),
	array('Soorten', URL::to_route('soorten'), routeInModule('soort')),
	array(Navigation::DIVIDER),
);

if(Auth::check()) {
	$menu[] = array('Uitloggen', URL::to_route('logout'), routeInModule('logout'));
} else {
	$menu[] = array('Inloggen', URL::to_route('login'), routeInModule('login'));
}

if(Request::ip() == IP_FALCONCREST) {
	$menu[] = array(Navigation::DIVIDER);
	$menu[] = array(Navigation::HEADER, 'Inloggen als', false, false, null);
	$aanwezigen = Aanwezigheid::where_datum(new DateTime("today"))->join('gebruikers', 'aanwezigheid.gebruiker_id', '=', 'gebruikers.id')->order_by("gebruikers.naam", "asc")->get();
	foreach($aanwezigen as $aanwezige) {
		$menu[] = array($aanwezige->gebruiker->naam, URL::to_route('loginAs', array($aanwezige->gebruiker->gebruikersnaam)), false, false, null, null, ((Auth::check() && $aanwezige->gebruiker->id == Auth::user()->id) ? array("class"=>"user-logged-in") : null));
	}
}

echo Navigation::lists(Navigation::links($menu));
