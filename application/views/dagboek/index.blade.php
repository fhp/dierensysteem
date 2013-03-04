@layout('master')

@section('content')
<h1>Dagboek</h1>

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
			<strong>{{$verslag->gebruiker->naam}}</strong>: {{nl2br($verslag->tekst)}}
		</div>
	</li>
@endforeach
</ul>

<p><a href="#verslagModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-plus"></i> Nieuwe dagverslag</a></p>

<div id="verslagModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::hidden("action", "dagverslag") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Nieuwe dagverslag</h3>
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


@endsection
