<?php

$dagnamen = array('Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag');
$maandnamen = array('januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december');

$html = "<html><body>";
$html .= <<<STYLE
<style>
table, tr, td {
	border: 1pt solid black;
	border-collapse: collapse;
}
td {
	white-space: nowrap;
	vertical-align: middle;
}
body, table {
	width: 100%;
}
td.date {
	text-align: center;
	font-weight: bold;
}
</style>
STYLE;

$html .= "<h1>Urenlijst " . $maandnamen[intval($maand)] . " " . $jaar . "</h1>";


$html .= "<div style=\"width: 40%; float: left;\">Naam student: {$gebruiker->naam}</div>";
$html .= "<div style=\"width: 40%; float: right;\">Klas:</div>";

$html .= "<div style=\"clear: both;\"></div><br>";

$html .= "<div style=\"width: 40%; float: left;\">Naam stage bedrijf: Falconcrest</div>";
$html .= "<div style=\"width: 40%; float: right;\">Periode:</div>";

$html .= "<div style=\"clear: both;\"></div><br>";

$html .= "<table>";
$html .= "<tr>";
$html .= "<td>Datum</td>";
$html .= "<td>Tijdstip aanvang</td>";
$html .= "<td>Tijdstip vertrek</td>";
$html .= "<td>Uren aanwezig</td>";
$html .= "<td>Opmerkingen</td>";
$html .= "</tr>";

$startDatum = new DateTime("01-$maand-$jaar");
$eindDatum = new DateTime("01-" . ($maand + 1) . "-$jaar");

$dagen = Aanwezigheid::where_gebruiker_id($gebruiker->id)->where("datum", ">=", $startDatum)->where("datum", "<", $eindDatum)->order_by("datum", "asc")->get();

$totaalStart = new DateTime("today");
$totaalEinde = new DateTime("today");
$aantalDagen = 0;

foreach($dagen as $dag) {
	if($dag->actief) {
		$aantalDagen++;
	}
	$datum = new DateTime($dag->datum);
	if($dag->start === null) {
		$startHtml = "-";
	} else {
		$start = new DateTime($dag->start);
		$startHtml = $start->format("H:i");
	}
	if($dag->einde === null) {
		$eindeHtml = "-";
	} else {
		$einde = new DateTime($dag->einde);
		$eindeHtml = $einde->format("H:i");
	}
	if($dag->start === null || $dag->einde === null) {
		$diffHtml = "-";
	} else {
		$diff = $einde->diff($start);
		$diffHtml = $diff->format("%h:%I");
		$totaalEinde->add($diff);
	}
	$html .= "<tr><td><span class=\"" . ($dag->actief ? "actief" : "nonactief") . "\">" . $dagnamen[$datum->format("w")] . " " . $datum->format("d-m-Y") . "</span></td><td>" . $startHtml . "</td><td>" . $eindeHtml . "</td><td>" . $diffHtml . "</td><td>" . $dag->opmerkingen . "</td></tr>";
}

$totaal = $totaalEinde->diff($totaalStart);

$html .= "<tr><td>Totaal</td><td colspan=\"2\">$aantalDagen dagen</td><td colspan=\"2\">" . ( 24 * $totaal->format("%a") + $totaal->format("%h")) . ":" . $totaal->format("%I") . " uren</td></tr>";

$html .= "</table>";

$html .= "<br>";

$html .= "<p>Datum:</p>";

$html .= "<div style=\"width: 40%; float: left;\">Handtekening opleiding:</div>";
$html .= "<div style=\"width: 40%; float: right;\">Handtekening student:</div>";

$html .= "<div style=\"clear: both;\"></div>";

$html .= "</body></html>";

echo $html;

?>