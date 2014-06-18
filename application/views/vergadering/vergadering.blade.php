@layout('master')

@section('content')
<h1>Vergadering op {{ substr($date, 6, 2) }}-{{ substr($date, 4, 2) }}-{{ substr($date, 0, 4) }}</h1>

<?php
$agendapunten = DB::query("SELECT DISTINCT `agendapunten`.`id` FROM `agendapunten` LEFT JOIN `notulen` ON(`agendapunten`.`id` = `notulen`.`agendapunt_id`) WHERE DATE_FORMAT(`notulen`.`created_at`, '%Y%m%d') = ? ORDER BY `notulen`.`created_at` ASC", array($date));
$actiepunten = DB::query("SELECT DISTINCT `actiepunten`.`id` FROM `actiepunten` WHERE DATE_FORMAT(`actiepunten`.`created_at`, '%Y%m%d') = ? ORDER BY `created_at` ASC", array($date));
?>

<h2>Agendapunten</h2>
<ul class="media-list">
@forelse ($agendapunten as $agendapunt_id)
	<?php $agendapunt = Agendapunt::find($agendapunt_id->id); ?>
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

<h2>Actiepunten</h2>
<ul class="media-list">
@forelse ($actiepunten as $actiepunt_id)
	<?php $actiepunt = Actiepunt::find($actiepunt_id->id); ?>
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

@endsection
