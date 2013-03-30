@layout('master')

@section('content')
<div class="row">
<div class="span6">
<h1>Mededelingen</h1>
<ul class="media-list">
<?php $vorigeDatum = ""; ?>
@foreach (Mededeling::order_by("datum", "desc")->order_by("id", "asc")->paginate(5)->results as $mededeling)
	<?php if($mededeling->datum != $vorigeDatum) { ?>
		<h4 class="media-heading">{{$mededeling->datum}}</h4>
	<?php $vorigeDatum = $mededeling->datum; } ?>
	<li class="media">
		<a class="pull-left" href="{{ URL::to_route("gebruikerDetail", array($mededeling->gebruiker->id, $mededeling->gebruiker->gebruikersnaam)) }}">
			{{ $mededeling->gebruiker->thumbnail_image(null, null, null, array("class"=>"media-object")) }}
		</a>
		<div class="media-body">
			<strong>{{$mededeling->gebruiker->naam}}</strong>: {{nl2br($mededeling->tekst)}}
		</div>
	</li>
@endforeach
</ul>

@if(Auth::user()->admin)
<p><a href="#mededelingModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-plus"></i> Nieuwe mededeling</a></p>
@endif

</div>
<div class="span4">
<h1>Vandaag</h1>
<?php $today = new DateTime("today"); ?>
<h3>{{ HTML::link_to_route("taken", "Taken") }}</h3>
<?php $taken = Taak::takenVandaag(); ?>
@if(count($taken) > 0)
<ul>
@foreach($taken as $taak)
	<?php
	$gedaan = count($taak->uitvoerders($today)) > 0;
	?>
	<li>
	@if($gedaan)
		<del>
	@endif
	{{ HTML::popup($taak->naam, $taak->beschrijving, $taak->naam) }}
	@if($gedaan)
		</del>
	@endif
	</li>
@endforeach
</ul>
@else
	<p>Er zijn geen taken geplanned voor vandaag.</p>
@endif

<h3>{{ HTML::link_to_route("agenda", "Aanwezigen") }}</h3>
@forelse(Aanwezigheid::where_datum($today)->get() as $aanwezigheid)
	{{ HTML::agendaAanwezigheid($aanwezigheid) }}
@empty
	<p>Er is niemand aanwezig vandaag.</p>
@endforelse
@if(Auth::user()->admin)
	{{ HTML::agendaAanmeldenAdmin($today) }}
@endif

<h3>{{ HTML::link_to_route("agenda", "Activiteiten") }}</h3>
<?php $evenementen = Evenement::where_datum($today)->get(); ?>
@if(count($evenementen) > 0)
<ul>
@foreach($evenementen as $evenement)
	<li>{{ HTML::agendaEvenement($evenement) }}</li>
@endforeach
</ul>
@else
<p>Er zijn geen activiteiten geplanned voor vandaag.</p>
@endif
</div>

@if(Auth::user()->admin)
<div id="mededelingModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesMededeling) }}
	{{ Form::hidden("action", "mededeling") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Nieuwe mededeling</h3>
	</div>
	<div class="modal-body">
		<textarea cols="100" rows="10" id="tekst" name="tekst" style="width: 516px;"></textarea>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>
@endif

@endsection
