@layout('master')

@section('content')
<h1>Dagboek</h1>

@if(Auth::check())
<p><a href="#verslagModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-plus"></i> Nieuw dagverslag</a></p>
@endif

<ul class="media-list">
<?php $vorigeDatum = ""; ?>
@foreach ($verslagen->results as $verslag)
	<?php if($verslag->datum != $vorigeDatum) { ?>
		<h4 class="media-heading">{{$verslag->datum}}</h4>
	<?php $vorigeDatum = $verslag->datum; } ?>
	<?php $magEditen = isAdmin() || (Auth::check() && Auth::user()->id == $verslag->gebruiker->id && (new DateTime($verslag->datum_edit) == new DateTime("today"))); ?>
	<li class="media {{ $magEditen ? "hover-edit" : "" }}">
		<a class="pull-left" href="{{ URL::to_route("gebruikerDetail", array($verslag->gebruiker->id, $verslag->gebruiker->gebruikersnaam)) }}">
			{{ $verslag->gebruiker->thumbnail_image(null, null, null, array("class"=>"media-object")) }}
		</a>
		<div class="media-body">
			<strong>{{$verslag->gebruiker->naam}}</strong>: {{nl2br($verslag->tekst)}}
		</div>
		@if($magEditen)
		<div class="hover-edit-tools">
			<a href="{{ URL::to_route("dagboekBewerk", array($verslag->id)) }}"><i class="icon icon-pencil"></i></a>
		</div>
		@endif
	</li>
@endforeach
</ul>
{{ $verslagen->links() }}

@if(Auth::check())
<div id="verslagModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesVerslag) }}
	{{ Form::hidden("action", "dagverslag") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Nieuw dagverslag</h3>
	</div>
	<div class="modal-body">
		@if(isAdmin())
		{{ Form::control_group(Form::label('gebruiker', 'Gebruiker'), Form::select('gebruiker', Gebruiker::where("nonactief", "=", 0)->order_by("naam", "asc")->lists("naam", "id"), Auth::user()->id)) }}
		{{ Form::control_group(Form::label('datum', 'Datum'), Form::text('datum', date("d-m-Y"), array("class"=>"datepicker"))) }}
		@endif
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
