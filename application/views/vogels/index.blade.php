@layout('master')

@section('content')
<h1>{{ $categorie->naam }}</h1>

<ul class="nav nav-tabs">
@foreach(Categorie::order_by("order", "asc")->get() as $andereCategorie)
	<?php
	if(count($andereCategorie->vogels) == 0) {
		continue;
	}
	
	if($andereCategorie->id == $categorie->id) {
		$class = "class=\"active\"";
	} else if($andereCategorie->ongelezenVerslagen()) {
		$class = "class=\"marked\"";
	} else {
		$class = "";
	}
	?>
	<li {{ $class }}>
	<a href="{{ URL::to_route("vogels", array($andereCategorie->id)) }}"> {{ $andereCategorie->naam }}</a>
	</li>
@endforeach
</ul>

<?php
$vogels = $categorie->vogels()->order_by("naam", "asc")->get();
$picked = false;
$nonpicked = false;
?>


<ul class="media-list media-table">
@foreach ($vogels as $vogel)
	@if($vogel->vliegpermissie(Auth::user()->id))
		<?php $picked = true; ?>
		<li class="media">
			<a class="pull-left" href="{{URL::to_route("vogelDetail", array($vogel->id, $vogel->naam))}}">
				<img src="{{URL::to_asset($vogel->thumbnail_url())}}" class="media-object">
			</a>
			<div class="media-body">
				<h4 class="media-heading {{ $vogel->alert == "" ? "" : "vogel-alert" }}">
					<a href="{{URL::to_route("vogelDetail", array($vogel->id, $vogel->naam))}}">{{$vogel->naam}}</a>
				</h4>
				@if($vogel->geschreven())
					<i class="icon icon-ok" title="Er is vandaag een verslag gescheven voor deze vogel."></i>
				@endif
				@if(Auth::check() && !$vogel->isGelezen())
					<i class="icon icon-flag" title="Er is nieuwe informatie voor deze vogel."></i>
				@endif
				{{$vogel->soort->naam}}
			</div>
		</li>
	@else
		<?php $nonpicked = true; ?>
	@endif
@endforeach
@if($picked && $nonpicked)
	</ul>
	<p class="overigevogels">Overige vogels</p>
	<ul class="media-list media-table">
@endif
@foreach ($vogels as $vogel)
	@if(!$vogel->vliegpermissie(Auth::user()->id))
		<li class="media">
			<a class="pull-left" href="{{URL::to_route("vogelDetail", array($vogel->id, $vogel->naam))}}">
				<img src="{{URL::to_asset($vogel->thumbnail_url())}}" class="media-object">
			</a>
			<div class="media-body">
				<h4 class="media-heading {{ $vogel->alert == "" ? "" : "vogel-alert" }}">
					<a href="{{URL::to_route("vogelDetail", array($vogel->id, $vogel->naam))}}">{{$vogel->naam}}</a>
				</h4>
				@if($vogel->geschreven())
					<i class="icon icon-ok" title="Er is vandaag een verslag gescheven voor deze vogel."></i>
				@endif
				@if(Auth::check() && !$vogel->isGelezen())
					<i class="icon icon-flag" title="Er is nieuwe informatie voor deze vogel."></i>
				@endif
				{{$vogel->soort->naam}}
			</div>
		</li>
	@endif
@endforeach
</ul>


@if(isAdmin())
<p><a href="#nieuwevogelModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-plus"></i> Nieuw dier</a></p>

<div id="nieuwevogelModal" class="modal hide fade modal-large" tabindex="-1" role="dialog">
	{{ Form::horizontal_open_for_files() }}
	{{ Form::rules($rulesNieuw) }}
	{{ Form::hidden("action", "nieuw") }}
	{{ Form::hidden("categorie", $categorie->id) }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Nieuw dier</h3>
	</div>
	<?php
	$eigenaren = array();
	$eigenaren[0] = "n.v.t.";
	foreach(Gebruiker::where_nonactief(0)->order_by("naam", "asc")->lists("naam", "id") as $key=>$value) {
		$eigenaren[$key] = $value;
	}
	?>
	<div class="modal-body">
		{{ Form::control_group(Form::label('naam', 'Naam'), Form::text('naam')) }}
		{{ Form::control_group(Form::label('geslacht', 'Geslacht'), Form::text('geslacht')) }}
		{{ Form::control_group(Form::label('soort', 'Soort'), Form::select('soort', Soort::order_by("naam", "asc")->lists("naam", "id"))) }}
		{{ Form::control_group(Form::label('geboortedatum', 'Geboortedatum'), Form::text('geboortedatum', null, array("class"=>"datepicker"))) }}
		{{ Form::control_group(Form::label('wegen', 'Wegen'), Form::labelled_checkbox('wegen', "Ja", '1', $categorie->id == 1)) }}
		{{ Form::control_group(Form::label('eigenaar', 'Eigenaar:'), Form::select('eigenaar', $eigenaren, $vogel->eigenaar_id === null ? 0 : $vogel->eigenaar_id)) }}
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
