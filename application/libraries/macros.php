<?php

HTML::macro("postLink", function($tekst, $url) {
	
	$output = "";
	$output .= Form::open($url);
	$output .= "<button class=\"btn btn-link\" type=\"submit\">$tekst</button>";
	$output .= Form::close();
	return $output;
});

HTML::macro("popup", function($tekst, $content, $title = null, $class = "") {
	if($title !== null) {
		$titleHtml = "title=\"" . htmlentities($title) . "\"";
	} else {
		$titleHtml = "";
	}
	return "<span class=\"popup $class\" $titleHtml data-content=\"" . htmlentities($content) . "\" data-html=\"true\">" . $tekst . "</span>";
});

HTML::macro("agendaEvenement", function($evenement) {
	if($evenement->beschrijving == "") {
		$beschrijving = "<i>Geen informatie opgegeven.</i>";
		$infoSymbool = "";
	} else {
		$beschrijving = nl2br($evenement->beschrijving);
		$infoSymbool = " <i class=\"icon-info-sign\"></i>";
	}
	
	$content = $beschrijving;
	
	if(isAdmin()) {
		$content .= "<p>" . HTML::postLink("Evenement verwijderen", URL::to_route("agendaDeleteEvenement", $evenement->id));
	}
	
	return HTML::popup($evenement->naam . $infoSymbool, $content, $evenement->naam) . "<br>";
});

HTML::macro("agendaAanwezigheid", function($aanwezigheid) {
	$naam = $aanwezigheid->gebruiker->thumbnail_image(null, "xsmall") . " <span class=\"" . ($aanwezigheid->actief ? "actief" : "nonactief") . "\">" . $aanwezigheid->gebruiker->naam . "</span>";
	$opmerkingen = $aanwezigheid->opmerkingen == "" ? "" : " (" . $aanwezigheid->opmerkingen . ")";
	if(isAdmin()) {
		$content  = HTML::postLink("afmelden", URL::to_route("agendaAfmeldenAdmin", agendaDatumNaarArray($aanwezigheid->datum, $aanwezigheid->gebruiker->id)));
		$content .= HTML::link_to_route("agendaAanwezigheid", "aanpassen", array($aanwezigheid->id));
		return HTML::popup($naam, $content, $aanwezigheid->gebruiker->naam) . $opmerkingen . "<br>";
	} else {
		return $naam . $opmerkingen . "<br>";
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