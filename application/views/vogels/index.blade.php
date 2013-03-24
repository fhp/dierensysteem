@layout('master')

@section('content')
<h1>Vogels</h1>

{{ MediaObject::open_list() }}
@foreach ($vogels as $vogel)
	<li class="media">
		<a class="pull-left" href="{{URL::to_route("vogelDetail", array($vogel->id, $vogel->naam))}}">
			<img src="{{URL::to_asset($vogel->thumbnail_url())}}" class="media-object">
		</a>
		<div class="media-body">
			<h4 class="media-heading {{ $vogel->alert == "" ? "" : "vogel-alert" }}">
				<a href="{{URL::to_route("vogelDetail", array($vogel->id, $vogel->naam))}}">{{$vogel->naam}}</a>
			</h4>
			{{$vogel->soort->naam}}
		</div>
	</li>
@endforeach
{{ MediaObject::close_list() }}

@if(Auth::user()->admin)
<p><a href="#nieuwevogelModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-plus"></i> Nieuwe vogel</a></p>

<div id="nieuwevogelModal" class="modal hide fade modal-large" tabindex="-1" role="dialog">
	{{ Form::horizontal_open_for_files() }}
	{{ Form::rules($rulesNieuw) }}
	{{ Form::hidden("action", "nieuw") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Nieuwe vogel</h3>
	</div>
	<div class="modal-body">
		{{ Form::control_group(Form::label('naam', 'Naam'), Form::text('naam')) }}
		{{ Form::control_group(Form::label('geslacht', 'Geslacht'), Form::select('geslacht', array("onbekend"=>"Onbekend", "tarsel"=>"Tarsel", "wijf"=>"Wijf"))) }}
		{{ Form::control_group(Form::label('soort', 'Soort'), Form::select('soort', $soorten)) }}
		{{ Form::control_group(Form::label('geboortedatum', 'Geboortedatum'), Form::text('geboortedatum', null, array("class"=>"datepicker"))) }}
		{{ Form::control_group(Form::label('foto', 'Foto'), Form::file('foto')) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>
@endif

@endsection
