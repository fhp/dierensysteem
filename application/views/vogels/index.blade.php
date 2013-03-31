@layout('master')

@section('content')
<h1>Vogels</h1>

@foreach(Categorie::order_by("order", "asc")->get() as $categorie)
	<?php if(count($categorie->vogels) == 0) continue; ?>
	@if($categorie->in_overzicht)
		<h2>{{ $categorie->naam }}</h2>
		<ul class="media-list media-table">
	@else
		<p id="header-categorie-{{$categorie->id}}"><a onclick="$('#table-categorie-{{$categorie->id}}').css('display', 'block'); $('#header-categorie-{{$categorie->id}}').html('<h2>{{ $categorie->naam }}</h2>')">{{ $categorie->naam }}</a></p>
		<ul class="media-list media-table" id="table-categorie-{{$categorie->id}}" style="display: none;">
	@endif
	@foreach ($categorie->vogels()->order_by("naam", "asc")->get() as $vogel)
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
	</ul>
@endforeach

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
		<?php
		$soorten = array();
		foreach(Soort::all() as $soort) {
			$soorten[$soort->id] = $soort->naam;
		}
		?>
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
