@layout('master')

@section('content')
<h1>Agendapunt: {{$agendapunt->titel}}</h1>
@if($agendapunt->voltooid)
<div class="alert">Agendapunt is gesloten.</div>
@endif
{{nl2br($agendapunt->omschrijving)}}

<h2>Notulen</h2>
<ul class="media-list">
<?php $vorigeDatum = ""; ?>
@forelse ($agendapunt->notulen as $notule)
	<?php if($notule->datum != $vorigeDatum) { ?>
		<h4 class="media-heading">{{$notule->datum}}</h4>
	<?php $vorigeDatum = $notule->datum; } ?>
	<li class="media hover-edit">
		<a class="pull-left" href="{{ URL::to_route("gebruikerDetail", array($notule->gebruiker->id, $notule->gebruiker->gebruikersnaam)) }}">
			{{ $notule->gebruiker->thumbnail_image(null, null, null, array("class"=>"media-object")) }}
		</a>
		<div class="media-body">
			<strong>{{$notule->gebruiker->naam}}</strong><br>{{nl2br($notule->omschrijving)}}
		</div>
		<div class="hover-edit-tools">
			<a href="{{ URL::to_route("vergaderingNotule", array($notule->id)) }}"><i class="icon icon-pencil"></i></a>
			<a href="{{ URL::to_route("vergaderingNotuleDelete", array($notule->id)) }}"><i class="icon icon-trash"></i></a>
		</div>
	</li>
@empty
	<li class="media">Geen notulen</li>
@endforelse
</ul>

{{ Form::horizontal_open() }}
{{ Form::rules($rulesNotule) }}
{{ Form::hidden("action", "notule") }}
	<h3>Notuleren</h3>
	<textarea cols="100" rows="10" id="omschrijving" name="omschrijving" style="width: 516px;"></textarea>
	{{ Form::control_group(Form::label('sluiten', 'Agendapunt sluiten'), Form::labelled_checkbox('sluiten', "Ja", '1', $agendapunt->voltooid)) }}
{{ Form::actions(array(Button::primary_submit('Opslaan'))) }}
{{ Form::close() }}

<h2>Actiepunten</h2>
<ul class="media-list">
@forelse ($agendapunt->actiepunten as $actiepunt)
	<li class="media">
		<a class="pull-left" href="{{ URL::to_route("gebruikerDetail", array($actiepunt->gebruiker->id, $actiepunt->gebruiker->gebruikersnaam)) }}">
			{{ $actiepunt->gebruiker->thumbnail_image(null, null, null, array("class"=>"media-object")) }}
		</a>
		<div class="media-body">
			<strong>{{$actiepunt->gebruiker->naam}}: <a  href="{{ URL::to_route("vergaderingActiepunt", array($actiepunt->id)) }}">{{$actiepunt->titel}}</a></strong><br>
			@if($actiepunt->deadline !== null)
				<i>Deadline: {{ $actiepunt->deadline }}</i><br>
			@endif
			{{nl2br($actiepunt->omschrijving)}}
		</div>
	</li>
@empty
	<li class="media">Geen actiepunten</li>
@endforelse
</ul>

<?php
foreach(Gebruiker::where_nonactief(0)->order_by("naam", "asc")->lists("naam", "id") as $key=>$value) {
	$gebruikers[$key] = $value;
}
?>
{{ Form::horizontal_open() }}
{{ Form::rules($rulesActiepunt) }}
{{ Form::hidden("action", "actiepunt") }}
	<h3>Nieuw actiepunt</h3>
	{{ Form::control_group(Form::label('titel', 'Titel'), Form::text('titel')) }}
	{{ Form::control_group(Form::label('gebruiker', 'Verantwoordelijke'), Form::select('gebruiker', $gebruikers, Auth::user()->id)) }}
	{{ Form::control_group(Form::label('deadline', 'Deadline'), Form::text('deadline', null, array("class"=>"datepicker"))) }}
	<textarea cols="100" rows="10" id="omschrijving" name="omschrijving" style="width: 516px;"></textarea>
{{ Form::actions(array(Button::primary_submit('Opslaan'))) }}
{{ Form::close() }}


@endsection
