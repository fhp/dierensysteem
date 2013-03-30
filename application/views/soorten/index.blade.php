@layout('master')

@section('content')
<h1>Soorten</h1>

<ul class="media-list media-table">
@foreach ($soorten as $soort)
	{{ MediaObject::create($soort->latijnsenaam)->with_h4(HTML::link_to_route("soortDetail", $soort->naam, array($soort->id, $soort->naam))) }}
@endforeach
</ul>

@if(Auth::user()->admin)
<p><a href="#verslagModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-plus"></i> Nieuwe soort</a></p>

<div id="verslagModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesNieuw) }}
	{{ Form::hidden("action", "nieuw") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Nieuwe soort</h3>
	</div>
	<div class="modal-body">
		{{ Form::control_group(Form::label('naam', 'Naam'), Form::text('naam')) }}
		{{ Form::control_group(Form::label('latijnsenaam', 'Latijnse naam'), Form::text('latijnsenaam')) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>
@endif

@endsection
