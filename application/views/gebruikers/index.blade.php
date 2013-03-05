@layout('master')

@section('content')
<h1>Gebruikers</h1>

{{ MediaObject::open_list() }}
@foreach ($gebruikers as $gebruiker)
	{{ MediaObject::create($gebruiker->gebruikersnaam, $gebruiker->thumbnail_url())->with_h4(HTML::link_to_route("gebruikerDetail", $gebruiker->naam, array($gebruiker->id, $gebruiker->gebruikersnaam))) }}
@endforeach
{{ MediaObject::close_list() }}

<p><a href="#nieuwegebruikerModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-plus"></i> Nieuwe gebruiker</a></p>

<div id="nieuwegebruikerModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesNieuw) }}
	{{ Form::hidden("action", "nieuw") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Nieuwe gebruiker</h3>
	</div>
	<div class="modal-body">
		{{ Form::control_group(Form::label('gebruikersnaam', 'Gebruikersnaam'), Form::text('gebruikersnaam')) }}
		{{ Form::control_group(Form::label('naam', 'Naam'), Form::text('naam')) }}
		{{ Form::control_group(Form::label('email', 'Email adres'), Form::text('email')) }}
		{{ Form::control_group(Form::label('telefoon', 'Telefoon nummer'), Form::text('telefoon')) }}
		{{ Form::control_group(Form::label('wachtwoord', 'Wachtwoord'), Form::password('wachtwoord')) }}
		{{ Form::control_group(Form::label('wachtwoord_confirmation', 'Bevestig wachtwoord'), Form::password('wachtwoord_confirmation')) }}
		{{ Form::control_group(Form::label('foto', 'Foto'), Form::file('foto')) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>


@endsection
