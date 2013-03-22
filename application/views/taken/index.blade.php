@layout('master')

@section('content')
<h1>Taken</h1>

{{ Form::horizontal_open() }}
<h2>Taken voor vandaag</h2>
@foreach($takenVandaag as $taak)
	<div style="margin: 10px; font-size: normal;">
		<div class="btn-group">
			<a href="{{ URL::to_route("taakGedaan", array($taak->id)) }}" class="btn"><i class="icon-ok"></i> Heb ik gedaan</a>
			<a href="#" class="btn popup" data-content="{{ $taak->beschrijving }}" data-html="true"><i class="icon-info-sign"></i> Info</a>
		</div>
		<b>{{ $taak->naam }}</b>@if(count($taak->uitvoerders()) > 0):
			@foreach($taak->uitvoerders() as $uitvoerder)
				{{ $uitvoerder->naam }}
			@endforeach
		@endif
	</div>
@endforeach

@unless(count($overigeTaken) == 0)
	<h2>Overige taken</h2>
	@foreach($overigeTaken as $taak)
		<div style="margin: 10px; font-size: normal;">
			<div class="btn-group">
				<a href="{{ URL::to_route("taakGedaan", array($taak->id)) }}" class="btn"><i class="icon-ok"></i> Heb ik gedaan</a>
				<a href="#" class="btn popup" data-content="{{ $taak->beschrijving }}" data-html="true"><i class="icon-info-sign"></i> Info</a>
			</div>
			<b>{{ $taak->naam }}</b>
		</div>
	@endforeach
@endunless

{{ Form::close() }}


<h2>Afgelopen week</h2>
<table class="weekcalendar table">
<tr>
@foreach($dagen as $dag)
	<th>{{ $dag }}</th>
@endforeach
</tr>
<tr>
@foreach($geschiedenis as $dag)
	<td>
	@foreach($dag as $taak)
		<span class="popup" title="{{ $taak["taak"]->naam }}" data-content="@foreach($taak["uitvoerders"] as $uitvoerder) {{ $uitvoerder->naam . "<br>" }}@endforeach" data-html="true">{{ $taak["taak"]->naam }}</span><br>
	@endforeach
	</td>
@endforeach
</tr>
</table>

@endsection
