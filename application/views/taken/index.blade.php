@layout('master')

@section('content')

<?
$geschiedenis = array();
$dagNaam = array("Zondag", "Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vrijdag", "Zaterdag");
for($i = 6; $i >= 0; $i--) {
	$dagDatum = new DateTime($datum->format("d-m-Y"));
	$dagDatum->sub(new DateInterval("P{$i}D"));
	$dagen[$i] = $dagNaam[$dagDatum->format("w")] . " " . $dagDatum->format('d-m-Y');
	$datums[$i] = $dagDatum;
	$taakIDs = DB::query("SELECT DISTINCT taak.id FROM taken AS taak INNER JOIN taakuitvoeringen AS uitvoering ON taak.id = uitvoering.taak_id WHERE uitvoering.datum = ? ORDER BY taak.naam", array($dagDatum));
	$geschiedenis[$i] = array();
	foreach($taakIDs as $taakID) {
		$taak = Taak::find($taakID->id);
		$geschiedenis[$i][] = array("taak"=>$taak, "uitvoerders"=>$taak->uitvoerders($dagDatum), "datum"=>$dagDatum);
	}
}
?>

<h1>Taken</h1>

<ul class="nav nav-tabs">
<li <? if($lijst == "dag") { ?> class="active" <? } ?>><a href="{{ URL::to_route("taken", array("dag")) }}">Dagtaken</a></li>
<li <? if($lijst == "week") { ?> class="active" <? } ?>><a href="{{ URL::to_route("taken", array("week")) }}">Weektaken</a></li>
</ul>

{{ Form::horizontal_open() }}
{{ Form::hidden("action", "uitvoeringen") }}
<table class="weekcalendar table">
<tr>
<th>{{ HTML::popup("Taak <i class=\"icon-info-sign\"></i>", "Klik op een taaknaam voor meer informatie over de taak.", "Taak beschrijving") }}</th>
@foreach($dagen as $dag)
	<th>{{ $dag }}</th>
@endforeach
</tr>
<?php
if($lijst == "dag") {
	$frequentie = 1;
} else if($lijst == "week") {
	$frequentie = 7;
}
?>
{{ Form::hidden("frequentie", $frequentie) }}
@foreach(Taak::where_actief(1)->where_frequentie($frequentie)->order_by("naam")->get() as $taak)
<tr>
	<td>{{ HTML::popup($taak->naam, $taak->beschrijving . (isAdmin() ? "<a href=\"" . URL::to_route("taakBewerk", array($taak->id)) . "\" class=\"btn btn-link\"><i class=\"icon-pencil\"></i></a>" : ""), $taak->naam) }}</td>
@foreach($geschiedenis as $index=>$dag)
	<td class="hover-table">
	@if($index == 0 && $datum == new DateTime("today") && !fcGast())
		{{ Form::checkbox('taak_' . $taak->id, 'done', $taak->gedaan(Auth::user()->id)) }}
	@endif
	@foreach($dag as $taakuitvoering)
		@if($taakuitvoering["taak"]->id == $taak->id)
			<?php
			$content = "";
			foreach($taakuitvoering["uitvoerders"] as $uitvoerder) {
				if(isAdmin()) {
					$content .= "<a href=\"" . URL::to_route("taakVerwijderUitvoering", array(Taakuitvoering::where_gebruiker_id_and_taak_id_and_datum($uitvoerder->id, $taak->id, $taakuitvoering["datum"])->only("id"))) . "\"><i class=\"icon icon-trash\"></i></a> ";
				}
				$content .= $uitvoerder->naam . "<br>";
			}
			?>
			{{ HTML::popup("<i class=\"icon icon-ok\"></i> " . (count($taakuitvoering["uitvoerders"]) == 1 ? $taakuitvoering["uitvoerders"][0]->naam : count($taakuitvoering["uitvoerders"]) . " personen"), $content, $taakuitvoering["taak"]->naam) }}
		@endif
	@endforeach
	@if(isAdmin())
	<div class="hover-table-item">
		<?php
		$content = "";
		foreach(Gebruiker::where_nonactief(0)->order_by("naam", "asc")->get() as $gebruiker) {
			$content .= "<a href=\"" . URL::to_route("taakNieuweUitvoering", array($datums[$index]->format("d"), $datums[$index]->format("m"), $datums[$index]->format("Y"), $taak->id, $gebruiker->id)) . "\"><i class=\"icon icon-plus\"></i> " . $gebruiker->naam . "</a><br>";
		}
		?>
		{{ HTML::popup("<i class=\"icon icon-plus\"></i>", $content, "Persoon toevoegen") }}
		
	</div>
	@endif
	</td>
@endforeach
</tr>
@endforeach
@if($datum == new DateTime("today") && !fcGast())
	@for($i = 7; $i >= 0; $i--)
		<td>
		@if($i == 0)
			{{ Form::submit("Opslaan") }}
		@else
			&nbsp;
		@endif
		</td>
	@endfor
@endif
</table>
{{ Form::close() }}

<?php
$week = new DateInterval("P7D");

$nextWeek = new DateTime($datum->format("Y-m-d"));
$nextWeek->add($week);
$prevWeek = new DateTime($datum->format("Y-m-d"));
$prevWeek->sub($week);

echo "<span class=\"pull-left\">" . HTML::link_to_route("taken", "<<< Vorige week", array($lijst, $prevWeek->format("Y"), $prevWeek->format("m"), $prevWeek->format("d"))) . "</span>";
if($nextWeek < new DateTime("today")) {
	echo "<span class=\"pull-right\">" . HTML::link_to_route("taken", "Volgende week >>>", array($lijst, $nextWeek->format("Y"), $nextWeek->format("m"), $nextWeek->format("d"))) . "</span>";
} else if($datum < new DateTime("today")) {
	$nextWeek = new DateTime("today");
	echo "<span class=\"pull-right\">" . HTML::link_to_route("taken", "Volgende week >>>", array($lijst, $nextWeek->format("Y"), $nextWeek->format("m"), $nextWeek->format("d"))) . "</span>";
}
?>
<br style="clear: both"><br>

@if(isAdmin())
<p><a href="#nieuweTaakModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-plus"></i> Nieuwe taak</a></p>
<p><a href="#adminTaakUitvoering" role="button" data-toggle="modal" class="btn"><i class="icon icon-plus"></i> Taakuitvoering toevoegen</a></p>

<div id="nieuweTaakModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesNieuweTaak) }}
	{{ Form::hidden("action", "nieuweTaak") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Nieuwe taak</h3>
	</div>
	<div class="modal-body">
		{{ Form::control_group(Form::label('naam', 'Naam'), Form::text('naam')) }}
		{{ Form::control_group(Form::label('beschrijving', 'Informatie:'), Form::textarea('beschrijving')) }}
		{{ Form::control_group(Form::label('frequentie', 'Frequentie'), Form::select('frequentie', array("1"=>"Dagtaak", 7=>"Weektaak"))) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>

<div id="adminTaakUitvoering" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesAdminTaakUitvoering) }}
	{{ Form::hidden("action", "taakuitvoering") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Taakuitvoering toevoegen</h3>
	</div>
	<div class="modal-body">
		{{ Form::control_group(Form::label('taak', 'Taak'), Form::select('taak', Taak::where_actief(1)->order_by("naam", "asc")->lists("naam", "id"))) }}
		{{ Form::control_group(Form::label('gebruiker', 'Gebruiker'), Form::select('gebruiker', Gebruiker::where_nonactief(0)->order_by("naam", "asc")->lists("naam", "id"))) }}
		{{ Form::control_group(Form::label('datum', 'Datum:'), Form::text('datum', date("d-m-Y"), array("class"=>"datepicker"))) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>

@endif

@endsection
