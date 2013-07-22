@layout('master')

@section('content')
<div class="row">
<div class="span6">
<h1>Welkom</h1>
@if(Auth::check())
	@render("home.inklokken")
@else
	<div class="alert"><strong>Let op!</strong> Je bent momenteel niet ingelogd.</div>
@endif
<h3>Mededelingen</h3>
@if(isAdmin())
<p><a href="#mededelingModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-plus"></i> Nieuwe mededeling</a></p>
@endif

<?php
$vorigeDatum = "";
$mededelingen = Mededeling::order_by("datum", "desc")->order_by("id", "asc")->paginate(5);
?>
<ul class="media-list">
@foreach ($mededelingen->results as $mededeling)
	<?php if($mededeling->datum != $vorigeDatum) { ?>
		<h4 class="media-heading">{{$mededeling->datum}}</h4>
	<?php $vorigeDatum = $mededeling->datum; } ?>
	<li class="media {{ isAdmin() ? "hover-edit" : "" }}">
		<a class="pull-left" href="{{ URL::to_route("gebruikerDetail", array($mededeling->gebruiker->id, $mededeling->gebruiker->gebruikersnaam)) }}">
			{{ $mededeling->gebruiker->thumbnail_image(null, null, null, array("class"=>"media-object")) }}
		</a>
		<div class="media-body">
			<strong>{{$mededeling->gebruiker->naam}}</strong>: {{nl2br($mededeling->tekst)}}
		</div>
		@if(isAdmin())
		<div class="hover-edit-tools">
			<a href="{{ URL::to_route("mededelingenEdit", array($mededeling->id)) }}"><i class="icon icon-pencil"></i></a>
		</div>
		@endif
	</li>
@endforeach
</ul>
{{ $mededelingen->links() }}


</div>
<div class="span4">
<h1>Vandaag</h1>
<?php $today = new DateTime("today"); ?>

<h3>{{ HTML::link_to_route("agenda", "Aanwezigen") }}</h3>
@forelse(Aanwezigheid::where_datum($today)->get() as $aanwezigheid)
	{{ HTML::agendaAanwezigheid($aanwezigheid) }}
@empty
	<p>Er is niemand aanwezig vandaag.</p>
@endforelse
@if(isAdmin())
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

<h1>Notities</h1>
{{ Notitie::first()->tekst }}
@if(isAdmin())
<p><a href="#notitieModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Bewerk notitie</a></p>
@endif


</div>

@if(isAdmin())
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

<div id="notitieModal" class="modal hide fade modal-large" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::hidden("action", "notitie") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Bewerk notitie</h3>
	</div>
	<div class="modal-body">
		{{ CKEditor::make('notitie', Notitie::first()->tekst) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>

@endif

@endsection
