<?php

$gebruiker = Auth::user();
if($gebruiker->isAanwezig()) {
	$aanwezigheid = $gebruiker->aanwezigheid();
	if($aanwezigheid->start === null) {
		echo Form::open(URL::to_route("inclocken"));
		echo Form::submit("Inclocken");
		echo Form::close();
	} else if($aanwezigheid->einde === null) {
		$start = new DateTime($aanwezigheid->start);
		echo "<p>Ingeclocked om " . $start->format("H:i") . ".</p>";
		echo Form::open(URL::to_route("uitclocken"));
		echo Form::submit("Uitclocken");
		echo Form::close();
	} else {
		$start = new DateTime($aanwezigheid->start);
		$einde = new DateTime($aanwezigheid->einde);
		$diff = $einde->diff($start);
		
		echo "<p>Ingeclocked om " . $start->format("H:i") . ", uitgeclocked om " . $einde->format("H:i") . ".</p>";
		echo "<p>Vandaag " . $diff->format("%h:%I") .  " uur gewerkt.</p>";
	}
}

?>