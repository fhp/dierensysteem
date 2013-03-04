<?php
echo Navigation::lists(
	Navigation::links(
		array(
			array(Navigation::HEADER, 'Algemeen', false, false, null),
			array('Home', URL::to_route('home'), Request::route()->is('home')),
			array('Dagboek', URL::to_route('dagboek'), Request::route()->is('dagboek')),
			array('Vogels', URL::to_route('vogels'), Request::route()->is('vogels') || Request::route()->is('vogelDetail')),
			array('Soorten', URL::to_route('soorten'), Request::route()->is('soorten') || Request::route()->is('soortDetail')),
// 			array('Taken', URL::to_route('taken'), Request::route()->is('taken')),
			array('Gebruikers', URL::to_route('gebruikers'), Request::route()->is('gebruikers') || Request::route()->is('gebruikerDetail')),
			array(Navigation::DIVIDER),
			array('Logout', URL::to_route('logout'), Request::route()->is('logout')),
		)
	)
);
