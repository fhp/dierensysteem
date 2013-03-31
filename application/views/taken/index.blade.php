@layout('master')

@section('content')
<h1>Taken</h1>

<ul class="nav nav-tabs">
<li <? if($lijst == "dag") { ?> class="active" <? } ?>><a href="{{ URL::to_route("taken", array("dag")) }}">Dagtaken</a></li>
<li <? if($lijst == "week") { ?> class="active" <? } ?>><a href="{{ URL::to_route("taken", array("week")) }}">Weektaken</a></li>
</ul>


{{ Form::horizontal_open() }}
<h2>Taken voor vandaag</h2>
@foreach($takenVandaag as $taak)
	<div style="margin: 10px; font-size: normal;">
		<div class="btn-group">
			<a href="{{ URL::to_route("taakGedaan", array($taak->id)) }}" class="btn"><i class="icon-ok"></i> Heb ik gedaan</a>
			<a href="#" class="btn popup" data-content="{{ $taak->beschrijving }}" data-html="true"><i class="icon-info-sign"></i> Info</a>
		</div>
		<b>{{ $taak->naam }}</b>@if(count($taak->uitvoerders()) > 0):
			@foreach($taak->uitvoerders() as $uitvoerder)
				{{ $uitvoerder->naam }}
			@endforeach
		@endif
	</div>
@endforeach

@unless(count($overigeTaken) == 0)
	<h2>Overige taken</h2>
	@foreach($overigeTaken as $taak)
		<div style="margin: 10px; font-size: normal;">
			<div class="btn-group">
				<a href="{{ URL::to_route("taakGedaan", array($taak->id)) }}" class="btn"><i class="icon-ok"></i> Heb ik gedaan</a>
				<a href="#" class="btn popup" data-content="{{ $taak->beschrijving }}" data-html="true"><i class="icon-info-sign"></i> Info</a>
			</div>
			<b>{{ $taak->naam }}</b>
		</div>
	@endforeach
@endunless

{{ Form::close() }}

@if($geschiedenisStartDatum == new DateTime("today"))
<h2>Afgelopen week</h2>
@else
<h2>Geschiedenis</h2>
@endif
<table class="weekcalendar table">
<tr>
@foreach($dagen as $dag)
	<th>{{ $dag }}</th>
@endforeach
</tr>
<tr>
@foreach($geschiedenis as $dag)
	<td>
	@foreach($dag as $taak)
		<span class="popup" title="{{ $taak["taak"]->naam }}" data-content="@foreach($taak["uitvoerders"] as $uitvoerder) {{ $uitvoerder->naam . "<br>" }}@endforeach" data-html="true">{{ $taak["taak"]->naam }}</span><br>
	@endforeach
	</td>
@endforeach
</tr>
</table>

<?php
$week = new DateInterval("P7D");

$nextWeek = new DateTime($geschiedenisStartDatum->format("Y-m-d"));
$nextWeek->add($week);
$prevWeek = new DateTime($geschiedenisStartDatum->format("Y-m-d"));
$prevWeek->sub($week);

echo "<span class=\"pull-left\">" . HTML::link_to_route("taken", "<<< Vorige week", array($lijst, $prevWeek->format("Y"), $prevWeek->format("m"), $prevWeek->format("d"))) . "</span>";
if($nextWeek < new DateTime("today")) {
	echo "<span class=\"pull-right\">" . HTML::link_to_route("taken", "Volgende week >>>", array($lijst, $nextWeek->format("Y"), $nextWeek->format("m"), $nextWeek->format("d"))) . "</span>";
} else if($geschiedenisStartDatum < new DateTime("today")) {
	$nextWeek = new DateTime("today");
	echo "<span class=\"pull-right\">" . HTML::link_to_route("taken", "Volgende week >>>", array($lijst, $nextWeek->format("Y"), $nextWeek->format("m"), $nextWeek->format("d"))) . "</span>";
}
?>
<br style="clear: both"><br>

@if(Auth::user()->admin)
<p><a href="#nieuweTaakModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-plus"></i> Nieuwe taak</a></p>

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
		{{ Form::control_group(Form::label('frequentie', 'Frequentie'), Form::text('frequentie')) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>
@endif

@endsection
