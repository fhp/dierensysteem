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
		if($evenement->beschrijving == "") {
			echo "<span class=\"popup\" data-content=\"Geen informatie opgegeven.\">" . $evenement->naam . "</span><br>";
		} else {
			echo "<span class=\"popup\" data-content=\"" . nl2br($evenement->beschrijving) . "\" data-html=\"true\">" . $evenement->naam . "</span><br>";
		}
	}
}

echo "<b class=\"$class\">Aanwezigen:</b><br>";
if(count($data["aanwezigen"]) == 0) {
	echo "<i>Niemand</i><br>";
} else {
	foreach($data["aanwezigen"] as $aanwezigheid) {
		echo $aanwezigheid->gebruiker->naam . "<br>";
	}
}

if(Auth::user()->isAanwezig($data["datum"])) {
	if($data["datum"] >= new DateTime("today +4 days")) {
		echo Form::open(URL::to_route('afmelden', array($data["datum"]->format("Y"), $data["datum"]->format("m"), $data["datum"]->format("d"))));
		echo "<button class=\"btn btn-link\" type=\"submit\">afmelden</button>";
		echo Form::close();
	}
} else {
	if($data["datum"] >= new DateTime("today")) {
		echo Form::open(URL::to_route('aanmelden', array($data["datum"]->format("Y"), $data["datum"]->format("m"), $data["datum"]->format("d"))));
		echo "<button class=\"btn btn-link\" type=\"submit\">aanmelden</button>";
		echo Form::close();
	}
}
if($data["datum"] >= new DateTime("today") && Auth::user()->admin) {
	echo "<p><a href=\"#evenementModal\" role=\"button\" data-toggle=\"modal\" onClick=\"$('#evenementModal input#datum').val('" . $data["datum"]->format("Y-m-d") . "')\">Evenement toevoegen</a></p>";
}

?>