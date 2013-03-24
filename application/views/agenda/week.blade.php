@layout('master')

@section('content')
<h1>Agenda
<?php
$dagen = array('Zondag', 'Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag');
$maanden = array("", "januari", "februari", "maart", "april", "mei", "juni", "juli", "augustus", "november", "december");
?>
</h1>

<a href="{{ URL::to_route("agendaMaand") }}" class="pull-right">Maand weergave</a>

<table class="weekcalendar table">
<tr>
@foreach($dagenData as $dagData)
	<?php
	$aantalAanwezigen = Aanwezigheid::where_datum($dagData["datum"])->count();

	if($aantalAanwezigen == 0) {
		$class = "agenda-aanwezigen-geen";
	} else if($aantalAanwezigen <= 2) {
		$class = "agenda-aanwezigen-weinig";
	} else if($aantalAanwezigen <= 5) {
		$class = "agenda-aanwezigen-genoeg";
	} else {
		$class = "agenda-aanwezigen-veel";
	}
	?>
	<th class="{{$class}}">{{ $dagen[$dagData["datum"]->format("w")] }} {{ $dagData["datum"]->format("d") }} {{ $maanden[(int)$dagData["datum"]->format("m")] }} {{ $dagData["datum"]->format("Y") }}</th>
@endforeach
</tr>
<tr>
@foreach($dagenData as $dagData)
	<td>
	<b>Evenementen:</b><br>
	@forelse($dagData["evenementen"] as $evenement)
		{{ HTML::agendaEvenement($evenement) }}
	@empty
		<i>Geen evenementen</i><br>
	@endforelse
	</td>
@endforeach
</tr>
<tr>
@foreach($dagenData as $dagData)
	<td>
	<b>Aanwezigen:</b><br>
	@forelse($dagData["aanwezigen"] as $aanwezigheid)
		{{ HTML::agendaAanwezigheid($aanwezigheid) }}
	@empty
		<i>Niemand aanwezig</i>
	@endforelse
	</td>
@endforeach
</tr>
<tr>
@foreach($dagenData as $dagData)
	<td>
	@if(Auth::user()->admin && $dagData["datum"] >= new DateTime("today"))
		<p><a href="#evenementModal" role="button" data-toggle="modal" onClick="$('#evenementModal input#datum').val('{{ $dagData["datum"]->format("Y-m-d") }}')">Evenement toevoegen</a></p>
	@endif
	@if(Auth::user()->admin)
		<p>{{ HTML::agendaAanmeldenAdmin($dagData["datum"]) }}</p>
	@endif
	@if(Auth::user()->isAanwezig($dagData["datum"]))
		@if($dagData["datum"] >= new DateTime("today +4 days"))
			{{ HTML::postLink("afmelden", URL::to_route('agendaAfmelden', agendaDatumNaarArray($dagData["datum"]))) }}
		@endif
	@else
		@if($dagData["datum"] >= new DateTime("today"))
			{{ HTML::postLink("aanmelden", URL::to_route('agendaAanmelden', agendaDatumNaarArray($dagData["datum"]))) }}
		@endif
	@endif
	</td>
@endforeach
</tr>

</table>

<?php
$week = new DateInterval("P7D");

$huidigeWeek = new DateTime("$jaar-$maand-$dag");
$nextWeek = new DateTime("$jaar-$maand-$dag");
$nextWeek->add($week);
$prevWeek = new DateTime("$jaar-$maand-$dag");
$prevWeek->sub($week);

echo "<span class=\"pull-left\">" . HTML::link_to_route("agendaWeek", "<<< Vorige week", array($prevWeek->format("Y"), $prevWeek->format("m"), $prevWeek->format("d"))) . "</span>";
echo "<span class=\"pull-right\">" . HTML::link_to_route("agendaWeek", "Volgende week >>>", array($nextWeek->format("Y"), $nextWeek->format("m"), $nextWeek->format("d"))) . "</span>";

?>
<br style="clear: both"><br>


<div id="evenementModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::horizontal_open(URL::to_route("agendaEvenement")) }}
	{{ Form::rules($rulesEvenement) }}
	{{ Form::hidden("action", "nieuwEvenement") }}
	<input type="hidden" name="datum" id="datum">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Nieuwe evenement</h3>
	</div>
	<div class="modal-body">
		{{ Form::control_group(Form::label('naam', 'Naam'), Form::text('naam')) }}
		{{ Form::control_group(Form::label('beschrijving', 'Informatie:'), Form::textarea('beschrijving')) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>

@endsection
