@layout('master')

@section('content')
<div class="row">
<div class="span6">
	<h1>{{$vogel->naam}}</h1>
	
	<dl class="dl-horizontal">
	<dt>Soort</dt><dd>{{ HTML::link_to_route("soortDetail", $vogel->soort->naam, array($vogel->soort->id, $vogel->soort->naam)) }}</dd>
	<dt>Geslacht</dt><dd>{{ Str::title($vogel->geslacht) }}</dd>
	<dt>Leeftijd</dt><dd>{{ $vogel->leeftijd }}</dd>
	</dl>
	
	
	<h2>Dagboek</h2>
	<p><a href="#verslagModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Nieuwe verslag</a></p>
	
	<ul class="media-list">
	<?php $vorigeDatum = ""; ?>
	@foreach ($verslagen->results as $verslag)
		<?php if($verslag->datum != $vorigeDatum) { ?>
			<h4 class="media-heading">{{$verslag->datum}}</h4>
		<?php $vorigeDatum = $verslag->datum; } ?>
		<li class="media">
			<a class="pull-left" href="{{ URL::to_route("gebruikerDetail", array($verslag->gebruiker->id, $verslag->gebruiker->gebruikersnaam)) }}">
				{{ $verslag->gebruiker->thumbnail_image(null, null, null, array("class"=>"media-object")) }}
			</a>
			<div class="media-body">
				<strong>{{$verslag->gebruiker->naam}}</strong>: {{$verslag->tekst}}
			</div>
		</li>
	@endforeach
	</ul>
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
	{{ $vogel->informatie }}
	<p><a href="#informatieModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Bewerk informatie</a></p>
</div>

<div id="informatieModal" class="modal hide fade modal-large" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::hidden("action", "informatie") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Bewerk algemene informatie</h3>
	</div>
	<div class="modal-body">
		{{ CKEditor::make('informatie', $vogel->informatie) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
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
