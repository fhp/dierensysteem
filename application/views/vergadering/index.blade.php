@layout('master')

@section('content')
<h1>Vergadering</h1>

<p><a href="#agendapuntModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-plus"></i> Nieuw agendapunt</a></p>

<h2>Agendapunten</h2>
<ul class="media-list">
@forelse ($agendapunten->results as $agendapunt)
	<li class="media">
		<a class="pull-left" href="{{ URL::to_route("gebruikerDetail", array($agendapunt->gebruiker->id, $agendapunt->gebruiker->gebruikersnaam)) }}">
			{{ $agendapunt->gebruiker->thumbnail_image(null, null, null, array("class"=>"media-object")) }}
		</a>
		<div class="media-body">
			<strong>{{$agendapunt->gebruiker->naam}}: <a href="{{ URL::to_route("vergaderingAgendapunt", array($agendapunt->id)) }}">{{$agendapunt->titel}}</a></strong><br>{{nl2br($agendapunt->omschrijving)}}
		</div>
	</li>
@empty
	<li class="media">Geen agendapunten</li>
@endforelse
</ul>
{{ $agendapunten->links() }}

<h2>Actiepunten</h2>
<ul class="media-list">
@forelse ($actiepunten->results as $actiepunt)
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
{{ $actiepunten->links() }}

<h2>Vergaderingen</h2>
<?php
$vergaderingen = DB::query("SELECT DATE_FORMAT(created_at, '%Y%m%d') as date, DATE_FORMAT(created_at, '%d-%m-%Y') as dateformatted, count(id) as count FROM `notulen` WHERE 1 GROUP BY DATE_FORMAT(created_at, '%Y%m%d') ORDER BY `created_at` DESC");
?>
@forelse($vergaderingen as $vergadering)
	<?php
	$agendapunten = DB::query("SELECT DISTINCT `agendapunten`.`titel` FROM `agendapunten` LEFT JOIN `notulen` ON(`agendapunten`.`id` = `notulen`.`agendapunt_id`) WHERE DATE_FORMAT(`notulen`.`created_at`, '%Y%m%d') = ? ORDER BY `notulen`.`created_at` ASC", array($vergadering->date));
	?>
	
	<li class="media">
		<div class="media-body">
			<strong><a href="{{ URL::to_route("vergaderingVergadering", array($vergadering->date)) }}">{{$vergadering->dateformatted}}</a>:</strong>:<br>
			<ol>
			@foreach($agendapunten as $agendapunt)
				<li>{{ $agendapunt->titel }}</li>
			@endforeach
			</ol>
		</div>
	</li>
@empty
	<li class="media">Geen vergaderingen</li>
@endforelse

<p><a href="{{ URL::to_route("vergaderingArchief") }}" class="btn"><i class="icon icon-list"></i> Archief</a></p>

<div id="agendapuntModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesAgendapunt) }}
	{{ Form::hidden("action", "agendapunt") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Nieuw agendapunt</h3>
	</div>
	<div class="modal-body">
		{{ Form::control_group(Form::label('titel', 'Titel'), Form::text('titel')) }}
		<textarea cols="100" rows="10" id="omschrijving" name="omschrijving" style="width: 516px;"></textarea>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>

@endsection
