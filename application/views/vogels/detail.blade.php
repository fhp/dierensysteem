@layout('master')

@section('content')
<div class="row">
<div class="span6">
	<h1>{{$vogel->naam}}</h1>
	
	{{ Typography::horizontal_dl($summary) }}
	
	<h2>Dagboek</h2>
	<p><a href="#verslagModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Nieuwe verslag</a></p>
	
	{{ MediaObject::open_list() }}
	@foreach ($verslagen->results as $verslag)
		{{ MediaObject::create($verslag->tekst)->with_h4($verslag->datum) }}
	@endforeach
	{{ MediaObject::close_list() }}
	{{ $verslagen->links() }}

</div>
<div class="span4">
	<div class="hover-div">
		{{$vogel->thumbnail_image("foto", "large") }}
		<div class="hover-text">
			<a href="#fotoModal" role="button" data-toggle="modal"><i class="icon icon-pencil icon-white"></i></a>
		</div>
	</div>
	
	<h2>Notities</h2>
	{{ MediaObject::open_list() }}
	@foreach ($notities as $notitie)
		{{ MediaObject::create($notitie->tekst)->with_h4($notitie->titel) }}
	@endforeach
	{{ MediaObject::close_list() }}
	<a href="#notitieModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Nieuwe notitie</a>
</div>

<div id="verslagModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::hidden("action", "verslag") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Nieuwe verslag</h3>
	</div>
	<div class="modal-body">
		{{ Form::control_group(Form::label('tekst', 'Informatie:'), Form::textarea('tekst')) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>

<div id="notitieModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::hidden("action", "vogelInfo") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Nieuwe notitie</h3>
	</div>
	<div class="modal-body">
		{{ Form::control_group(Form::label('titel', 'Titel:'), Form::text('titel')) }}
		{{ Form::control_group(Form::label('tekst', 'Informatie:'), Form::textarea('tekst')) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
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

</div>
@endsection
