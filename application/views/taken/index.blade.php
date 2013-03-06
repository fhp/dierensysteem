@layout('master')

@section('content')
<h1>Taken</h1>

{{ Form::horizontal_open() }}
@foreach($taken as $taak)
<div style="margin: 10px; font-size: normal;">
	<div class="btn-group">
		<a href="#" class="btn"><i class="icon-ok"></i> Heb ik gedaan</a>
		<a href="#" class="btn popup" data-content="{{ $taak->beschrijving }}" data-html="true"><i class="icon-info-sign"></i> Info</a>
	</div>
	<b>{{ $taak->naam }}</b>@if(count($taak->uitvoerders()) > 0):
		@foreach($taak->uitvoerders() as $uitvoerder)
			{{ $uitvoerder->naam }}
		@endforeach
	@endif
</div>
@endforeach

{{ Form::close() }}

@endsection
