@layout('master')

@section('content')
<h1>Vergadering archief</h1>

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

@endsection
