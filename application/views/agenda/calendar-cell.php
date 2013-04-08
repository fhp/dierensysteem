<?php

if($data === null) {
	return;
}

$aantalAanwezigen = Aanwezigheid::where_datum($data["datum"])->count();

if($aantalAanwezigen == 0) {
	$class = "agenda-aanwezigen-geen";
} else if($aantalAanwezigen <= 2) {
	$class = "agenda-aanwezigen-weinig";
} else if($aantalAanwezigen <= 5) {
	$class = "agenda-aanwezigen-genoeg";
} else {
	$class = "agenda-aanwezigen-veel";
}

if(count($data["evenementen"]) > 0) {
	echo "<b>Evenementen:</b><br>";
	foreach($data["evenementen"] as $evenement) {
		echo HTML::agendaEvenement($evenement);
	}
}

echo "<b class=\"$class\">Aanwezigen:</b><br>";
if(count($data["aanwezigen"]) == 0) {
	echo "<span class=\"agenda-disabled\">Niemand</span><br>";
} else {
	foreach($data["aanwezigen"] as $aanwezigheid) {
		echo HTML::agendaAanwezigheid($aanwezigheid);
	}
}
if(Auth::check()) {
	if(Auth::user()->isAanwezig($data["datum"])) {
		if($data["datum"] >= new DateTime("today +4 days")) {
			echo HTML::postLink("afmelden", URL::to_route('agendaAfmelden', agendaDatumNaarArray($data["datum"])));
		}
	} else {
		if($data["datum"] >= new DateTime("today")) {
			echo HTML::postLink("aanmelden", URL::to_route('agendaAanmelden', agendaDatumNaarArray($data["datum"])));
		}
	}
}
if($data["datum"] >= new DateTime("today") && isAdmin()) {
	echo "<a href=\"#evenementModal\" role=\"button\" data-toggle=\"modal\" onClick=\"$('#evenementModal input#datum').val('" . $data["datum"]->format("Y-m-d") . "')\">Evenement toevoegen</a><br>";
}
if(isAdmin()) {
	echo HTML::agendaAanmeldenAdmin($data["datum"]) . "<br>";
}

?>