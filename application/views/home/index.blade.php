@layout('master')

@section('content')
<h1>Welkom</h1>
<p>Je bent nu ingelogged als {{ Auth::user()->naam }}.</p>
<h2>Mededelingen</h2>
<ul class="media-list">
<?php $vorigeDatum = ""; ?>
@foreach ($mededelingen->results as $mededeling)
	<?php if($mededeling->datum != $vorigeDatum) { ?>
		<h4 class="media-heading">{{$mededeling->datum}}</h4>
	<?php $vorigeDatum = $mededeling->datum; } ?>
	<li class="media">
		<a class="pull-left" href="{{ URL::to_route("gebruikerDetail", array($mededeling->gebruiker->id, $mededeling->gebruiker->gebruikersnaam)) }}">
			{{ $mededeling->gebruiker->thumbnail_image(null, null, null, array("class"=>"media-object")) }}
		</a>
		<div class="media-body">
			<strong>{{$mededeling->gebruiker->naam}}</strong>: {{nl2br($mededeling->tekst)}}
		</div>
	</li>
@endforeach
</ul>

@if(Auth::user()->admin)
<p><a href="#mededelingModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-plus"></i> Nieuwe mededeling</a></p>

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
@endif

@endsection
