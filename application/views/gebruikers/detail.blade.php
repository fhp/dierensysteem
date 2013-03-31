@layout('master')

@section('content')
<div class="row">
<div class="span6">
	<h1>{{$gebruiker->naam}}</h1>
	
	<dl class="dl-horizontal">
	<dt>Gebruikersnaam</dt><dd>{{ $gebruiker->gebruikersnaam }}</dd>
	<dt>Beheerder</dt><dd>{{ $gebruiker->admin ? "Ja" : "Nee" }}</dd>
@if(Auth::user()->admin || Auth::user()->id == $gebruiker->id)
	<dt>Email</dt><dd>{{ HTML::mailto($gebruiker->email, $gebruiker->email) }}&nbsp;</dd>
	<dt>Telefoonnummer</dt><dd>{{ $gebruiker->telefoon }}&nbsp;</dd>
@endif
<?php
$vogels = $gebruiker->vliegpermissies;
$vogelCount = count($vogels);
$magVliegenMetHtml = "";
foreach($vogels as $vogel) {
	$magVliegenMetHtml .= "<a href=\"" . URL::to_route("vogelDetail", array($vogel->id, $vogel->naam)) . "\">" . $vogel->thumbnail_image(null, "xsmall") . " " . $vogel->naam . "</a><br>";
}
?>
	<dt>Mag vliegen met</dt><dd>{{ HTML::popup($vogelCount . ($vogelCount == 1 ? " vogel" : " vogels"), $magVliegenMetHtml, "$gebruiker->naam mag vliegen met:") }}</dd>
	</dl>
	{{ $gebruiker->informatie }}
@if(Auth::user()->admin)
	<p><a href="#informatieModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Bewerk persoonlijke informatie</a></p>
	<p><a href="#vliegpermissiesModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Bewerk vliegpermissies</a></p>
@endif
</div>
<div class="span4">
	<div class="hover-div">
		{{$gebruiker->thumbnail_image("foto", "large") }}
		@if(Auth::user()->admin || Auth::user()->id == $gebruiker->id)
		<div class="hover-text">
			<a href="#fotoModal" role="button" data-toggle="modal"><i class="icon icon-pencil icon-white"></i></a>
		</div>
		@endif
	</div>
	{{ $gebruiker->biografie }}
	@if(Auth::user()->admin || Auth::user()->id == $gebruiker->id)
	<p><a href="#biografieModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Bewerk biografie</a></p>
	<p><a href="{{ URL::to_route("gebruikerUren", array($gebruiker->id, $gebruiker->gebruikersnaam)) }}" role="button" class="btn"><i class="icon icon-time"></i> Uren overzicht</a></p>
	<p><a href="#wachtwoordModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-lock"></i> Verander wachtwoord</a></p>
	@endif
</div>
</div>


@if(Auth::user()->admin || Auth::user()->id == $gebruiker->id)
<div id="fotoModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::vertical_open_for_files() }}
	{{ Form::rules($rulesFoto) }}
	{{ Form::hidden("action", "foto") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Nieuwe foto uploaden</h3>
	</div>
	<div class="modal-body">
		{{ Form::file('foto') }}<br>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>
@endif

@if(Auth::user()->admin)
<div id="informatieModal" class="modal hide fade modal-large" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesInformatie) }}
	{{ Form::hidden("action", "informatie") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Bewerk persoonlijke informatie</h3>
	</div>
	<div class="modal-body">
		{{ Form::control_group(Form::label('email', 'Email adres'), Form::text('email', $gebruiker->email)) }}
		{{ Form::control_group(Form::label('telefoon', 'Telefoon nummer'), Form::text('telefoon', $gebruiker->telefoon)) }}
		{{ Form::control_group(Form::label('nonactief', 'Non-actief'), Form::labelled_checkbox('nonactief', "ja", 1, $gebruiker->nonactief)) }}
		{{ Form::control_group(Form::label('admin', 'Beheerder'), Form::labelled_checkbox('admin', "ja", 1, $gebruiker->admin)) }}
		{{ CKEditor::make('informatie', $gebruiker->informatie) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>
@endif

@if(Auth::user()->admin || Auth::user()->id == $gebruiker->id)
<div id="biografieModal" class="modal hide fade modal-large" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesBiografie) }}
	{{ Form::hidden("action", "biografie") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Bewerk biografie</h3>
	</div>
	<div class="modal-body">
		{{ CKEditor::make('biografie', $gebruiker->biografie) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>
@endif

@if(Auth::user()->id == $gebruiker->id)
<div id="wachtwoordModal" class="modal hide fade modal-large" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesWachtwoord) }}
	{{ Form::hidden("action", "wachtwoord") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Verander wachtwoord</h3>
	</div>
	<div class="modal-body">
		{{ Form::control_group(Form::label('wachtwoord', 'Wachtwoord'), Form::password('wachtwoord')) }}
		{{ Form::control_group(Form::label('wachtwoord_confirmation', 'Bevestig wachtwoord'), Form::password('wachtwoord_confirmation')) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>
@endif

@if(Auth::user()->admin)
<div id="vliegpermissiesModal" class="modal hide fade modal-large" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::hidden("action", "vliegpermissies") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Bewerk vliegpermissies</h3>
	</div>
	<div class="modal-body">
		<p>{{ $gebruiker->naam }} mag vliegen met:</p>
		@foreach(Vogel::all() as $vogel)
			{{ Form::labelled_checkbox('vogel-' . $vogel->id, $vogel->naam, '1', count($vogel->vliegpermissies()->where_gebruiker_id($gebruiker->id)->get()) == 1) }}
		@endforeach
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>
@endif

@endsection
