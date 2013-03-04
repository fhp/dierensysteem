@layout('master')

@section('content')
<div class="row">
<div class="span6">
	<h1>{{$gebruiker->naam}}</h1>
	
	<dl class="dl-horizontal">
	<dt>Gebruikersnaam</dt><dd>{{ $gebruiker->gebruikersnaam }}</dd>
	<dt>Email</dt><dd>{{ HTML::mailto($gebruiker->email, $gebruiker->email) }}</dd>
	<dt>Telefoonnummer</dt><dd>{{ $gebruiker->telefoon }}</dd>
	</dl>
	
	{{ $gebruiker->informatie }}
	<p><a href="#informatieModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Bewerk persoonlijke informatie</a></p>
</div>
<div class="span4">
	<div class="hover-div">
		{{$gebruiker->thumbnail_image("foto", "large") }}
		<div class="hover-text">
			<a href="#fotoModal" role="button" data-toggle="modal"><i class="icon icon-pencil icon-white"></i></a>
		</div>
	</div>
</div>

<div id="fotoModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::vertical_open_for_files() }}
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

<div id="informatieModal" class="modal hide fade modal-large" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::hidden("action", "informatie") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Bewerk persoonlijke informatie</h3>
	</div>
	<div class="modal-body">
		{{ CKEditor::make('informatie', $gebruiker->informatie) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>


</div>
@endsection
