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
	
	@if($vogel->alert != "")
	{{ Alert::error("<strong>Let op!</strong> $vogel->alert")->open() }}
	@endif

@if($vogel->gewichten()->where("datum", ">", new DateTime("last month"))->count() > 0)
<script type="text/javascript">
grafiekImageSize = function()
{
	grafiek = $("#grafiek")
	baseUrl = "{{URL::to_route("vogelgrafiek", array($vogel->id))}}"
	src = baseUrl + "?width=" + grafiek.width() + "&height=" + grafiek.height()
	
	grafiek.attr("src", src);	
}

$(function() {
	grafiekImageSize();
	$(window).resize(grafiekImageSize);
});
</script>
<img src="{{URL::to_route("vogelgrafiek", array($vogel->id))}}" id="grafiek">
@endif

	<h2>Dagboek</h2>
	<p>
	<a href="#verslagModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Nieuwe verslag</a>
	@if(Auth::user()->admin)
	<a href="#alertModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Bewerk waarschuwing</a>
	@endif
	</p>
	
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
		@if(Auth::user()->admin)
		<div class="hover-text">
			<a href="#fotoModal" role="button" data-toggle="modal"><i class="icon icon-pencil icon-white"></i></a>
		</div>
		@endif
	</div>
	
	<h2>Notities</h2>
	{{ $vogel->informatie }}
	@if(Auth::user()->admin)
	<p><a href="#informatieModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Bewerk informatie</a></p>
	@endif
</div>

@if(Auth::user()->admin)
<div id="informatieModal" class="modal hide fade modal-large" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesInformatie) }}
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
@endif

<div id="verslagModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesVerslag) }}
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

@if(Auth::user()->admin)
<div id="alertModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesAlert) }}
	{{ Form::hidden("action", "alert") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Bewerk waarschuwing</h3>
	</div>
	<div class="modal-body">
		{{ Form::control_group(Form::label('alert', 'Waarschuwing:'), Form::text('alert', $vogel->alert)) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>
@endif

@if(Auth::user()->admin)
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

</div>
@endsection
