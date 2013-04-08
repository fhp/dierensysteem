<?php

$gebruiker = Auth::user();
if($gebruiker->isAanwezig()) {
	$aanwezigheid = $gebruiker->aanwezigheid();
	if($aanwezigheid->start === null) {
		echo Form::open(URL::to_route("inklokken"));
		echo Form::submit("Inklokken");
		echo Form::close();
	} else if($aanwezigheid->einde === null) {
		$start = new DateTime($aanwezigheid->start);
		echo "<p>Ingeklokked om " . $start->format("H:i") . ".</p>";
		echo Form::open(URL::to_route("uitklokken"));
		echo Form::submit("Uitklokken");
		echo Form::close();
	} else {
		$start = new DateTime($aanwezigheid->start);
		$einde = new DateTime($aanwezigheid->einde);
		$diff = $einde->diff($start);
		
		echo "<p>Ingeklokt om " . $start->format("H:i") . ", uitgeklokt om " . $einde->format("H:i") . ".</p>";
		echo "<p>Vandaag " . $diff->format("%h:%I") .  " uur gewerkt.</p>";
	}
}

?>