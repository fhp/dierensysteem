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
	array('Vogels', URL::to_route('vogels'), routeInModule('vogel')),
	array('Soorten', URL::to_route('soorten'), routeInModule('soort')),
	array('Taken', URL::to_route('taken'), routeInModule('taken', 'taak')),
	array('Gebruikers', URL::to_route('gebruikers'), routeInModule('gebruiker')),
	array(Navigation::DIVIDER),
	array('Logout', URL::to_route('logout'), routeInModule('logout')),
	array(Navigation::DIVIDER),
	array(Navigation::HEADER, 'Inloggen als', false, false, null),
);

$gebruikers = Gebruiker::order_by("naam", "asc")->get();

foreach($gebruikers as $gebruiker) {
	$menu[] = array($gebruiker->naam, URL::to_route('loginAs', array($gebruiker->gebruikersnaam)), false);
}

echo Navigation::lists(Navigation::links($menu));
