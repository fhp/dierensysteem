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
	array('Vogels', URL::to_route('vogels', (Request::route()->is('vogelDetail') ? array(Vogel::find(URI::segment(2))->categorie_id) : array())), routeInModule('vogel')),
	array('Wegen', URL::to_route('wegen'), routeInModule('wegen')),
	array('Taken', URL::to_route('taken'), routeInModule('taken', 'taak')),
	array('Gebruikers', URL::to_route('gebruikers'), routeInModule('gebruiker')),
	array('Soorten', URL::to_route('soorten'), routeInModule('soort')),
	array(Navigation::DIVIDER),
	array('Uitloggen', URL::to_route('logout'), routeInModule('logout')),
);

if(Request::ip() == "88.159.83.200") {
	$menu[] = array(Navigation::DIVIDER);
	$menu[] = array(Navigation::HEADER, 'Inloggen als', false, false, null);
	$aanwezigen = Aanwezigheid::where_datum(new DateTime("today"))->join('gebruikers', 'aanwezigheid.gebruiker_id', '=', 'gebruikers.id')->order_by("gebruikers.naam", "asc")->get();
	foreach($aanwezigen as $aanwezige) {
		$menu[] = array($aanwezige->gebruiker->naam, URL::to_route('loginAs', array($aanwezige->gebruiker->gebruikersnaam)), false);
	}
}

echo Navigation::lists(Navigation::links($menu));
