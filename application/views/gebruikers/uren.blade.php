@layout('master')

@section('content')

<h1>Urenoverzicht van {{ $gebruiker->naam }}</h1>

<table class="table">
<thead><tr><th>Datum</th><th>Start</th><th>Einde</th><th>Uren</th></tr></thead>
<tbody>
<?php

$dagnamen = array('Zondag', 'Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag');

$startDatum = new DateTime("01-$maand-$jaar");
$eindDatum = new DateTime("01-" . ($maand + 1) . "-$jaar");

$dagen = Aanwezigheid::where_gebruiker_id($gebruiker->id)->where("datum", ">=", $startDatum)->where("datum", "<", $eindDatum)->order_by("datum", "asc")->get();

$totaalStart = new DateTime("today");
$totaalEinde = new DateTime("today");
$aantalDagen = 0;

foreach($dagen as $dag) {
	$aantalDagen++;
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
	echo "<tr><td>" . $dagnamen[$datum->format("w")] . " " . $datum->format("d-m-Y") . "</td><td>" . $startHtml . "</td><td>" . $eindeHtml . "</td><td>" . $diffHtml . "</td></tr>";
}

$totaal = $totaalEinde->diff($totaalStart);

?>
</tbody>
<tfooter>
<tr><th colspan="2" style="text-align: right;">Totaal:</th><td>{{ $aantalDagen }} dagen<td>{{ $totaal->format("%h:%I") }} uren</td></tr>
</tfooter>
</table>

<?
$nextMonth = array($gebruiker->id, $gebruiker->gebruikersnaam, $jaar, $maand + 1);
$prevMonth = array($gebruiker->id, $gebruiker->gebruikersnaam, $jaar, $maand - 1);
if($maand == 12) {
	$nextMonth = array($gebruiker->id, $gebruiker->gebruikersnaam, $jaar + 1, 1);
} else if($maand == 1) {
	$prevMonth = array($gebruiker->id, $gebruiker->gebruikersnaam, $jaar - 1, 12);
}

echo "<span class=\"pull-left\">" . HTML::link_to_route("gebruikerUren", "<<< Vorige maand", $prevMonth) . "</span>";
echo "<span class=\"pull-right\">" . HTML::link_to_route("gebruikerUren", "Volgende maand >>>", $nextMonth) . "</span>";
?>

@endsection
