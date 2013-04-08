@layout('master')

@section('content')
<div class="row">
	<div class="span6">
		<h1>{{$soort->naam}}</h1>
		
		<dl class="dl-horizontal">
		<dt>Engelse naam</dt><dd>{{ $soort->engelsenaam }}&nbsp;</dd>
		<dt>Latijnse naam</dt><dd>{{ $soort->latijnsenaam }}&nbsp;</dd>
		</dl>
		
		<h2>Notities</h2>
		{{ $soort->informatie }}
		@if(isAdmin())
		<p><a href="#informatieModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Bewerk informatie</a></p>
		@endif
	</div>
	
	<div class="span4">
		{{ MediaObject::open_list() }}
		@foreach ($soort->vogels as $vogel)
			{{ MediaObject::create($vogel->soort->naam, $vogel->thumbnail_url())->with_h4(HTML::link_to_route("vogelDetail", $vogel->naam, array($vogel->id, $vogel->naam))) }}
		@endforeach
		{{ MediaObject::close_list() }}
	</div>
</div>

@if(isAdmin())
<div id="informatieModal" class="modal hide fade modal-large" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesInformatie) }}
	{{ Form::hidden("action", "informatie") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Bewerk algemene informatie</h3>
	</div>
	<div class="modal-body">
		{{ Form::control_group(Form::label('naam', 'Naam'), Form::text('naam', $soort->naam)) }}
		{{ Form::control_group(Form::label('engelsenaam', 'Engelse naam'), Form::text('engelsenaam', $soort->engelsenaam)) }}
		{{ Form::control_group(Form::label('latijnsenaam', 'Latijnse naam'), Form::text('latijnsenaam', $soort->latijnsenaam)) }}
		{{ CKEditor::make('informatie', $soort->informatie) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>
@endif

@endsection
