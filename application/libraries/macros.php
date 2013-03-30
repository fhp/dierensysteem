<?php

HTML::macro("postLink", function($tekst, $url) {
	
	$output = "";
	$output .= Form::open($url);
	$output .= "<button class=\"btn btn-link\" type=\"submit\">$tekst</button>";
	$output .= Form::close();
	return $output;
});

HTML::macro("popup", function($tekst, $content, $title = null) {
	if($title !== null) {
		$titleHtml = "title=\"" . htmlentities($title) . "\"";
	} else {
		$titleHtml = "";
	}
	return "<span class=\"popup\" $titleHtml data-content=\"" . htmlentities($content) . "\" data-html=\"true\">" . $tekst . "</span><br>";
});

HTML::macro("agendaEvenement", function($evenement) {
	if($evenement->beschrijving == "") {
		$beschrijving = "<i>Geen informatie opgegeven.</i>";
	} else {
		$beschrijving = nl2br($evenement->beschrijving);
	}
	
	$content = $beschrijving;
	
	if(Auth::user()->admin) {
		$content .= "<p>" . HTML::postLink("Evenement verwijderen", URL::to_route("agendaDeleteEvenement", $evenement->id));
	}
	
	return HTML::popup($evenement->naam, $content, $evenement->naam);
});

HTML::macro("agendaAanwezigheid", function($aanwezigheid) {
	if(Auth::user()->admin) {
		$content = HTML::postLink("afmelden", URL::to_route("agendaAfmeldenAdmin", agendaDatumNaarArray($aanwezigheid->datum, $aanwezigheid->gebruiker->id)));
		return HTML::popup($aanwezigheid->gebruiker->thumbnail_image(null, "xsmall") . " " . $aanwezigheid->gebruiker->naam, $content, $aanwezigheid->gebruiker->naam);
	} else {
		return $aanwezigheid->gebruiker->naam . "<br>";
	}
});

HTML::macro("agendaAanmeldenAdmin", function($datum) {
	$output = "";
	$output .= "<div class=\"dropdown\">";
	$output .= "<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">Iemand anders aanmelden</a>";
	$output .= "<ul class=\"dropdown-menu\">";
	foreach(Gebruiker::order_by("naam")->get() as $gebruiker) {
		if($gebruiker->isAanwezig($datum)) {
			continue;
		}
		$output .= "<li>";
		$output .= HTML::postLink($gebruiker->naam, URL::to_route("agendaAanmeldenAdmin", agendaDatumNaarArray($datum, $gebruiker->id)));
		$output .= "</li>";
	}
	$output .= "</ul>";
	$output .= "</div>";
	return $output;
});

function agendaDatumNaarArray($datum, $id = null) {
	if(is_string($datum)) {
		$datum = new DateTime($datum);
	}
	$array = array($datum->format("Y"), $datum->format("m"), $datum->format("d"));
	if($id !== null) {
		$array[] = $id;
	}
	return $array;
}

?>