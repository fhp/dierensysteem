@layout('master')

@section('content')
<div class="row">
<div class="span6">
	<h1>{{$vogel->naam}}</h1>
	
	<dl class="dl-horizontal">
	<dt>Soort</dt><dd>{{ HTML::link_to_route("soortDetail", $vogel->soort->naam, array($vogel->soort->id, $vogel->soort->naam)) }}</dd>
	<dt>Geslacht</dt><dd>{{ Str::title($vogel->geslacht) }}</dd>
	<dt>Leeftijd</dt><dd>{{ $vogel->leeftijd }}</dd>
	<dt>Categorie</dt><dd>{{ $vogel->categorie->naam }}</dd>
	@if($vogel->eigenaar !== null)
	<dt>Eigenaar</dt><dd>{{ $vogel->eigenaar->naam }}</dd>
	@endif
<?php
$gebruikers = $vogel->vliegpermissies;
$gebruikerCount = count($gebruikers);
$magGevlogenWordenDoorHtml = "";
foreach($gebruikers as $gebruiker) {
	$magGevlogenWordenDoorHtml .= "<a href=\"" . URL::to_route("gebruikerDetail", array($gebruiker->id, $gebruiker->naam)) . "\">" . $gebruiker->thumbnail_image(null, "xsmall") . " " . $gebruiker->naam . "</a><br>";
}
?>
	<dt>Mag gevlogen worden door</dt><dd>{{ HTML::popup($gebruikerCount . ($gebruikerCount == 1 ? " persoon" : " personen"), $magGevlogenWordenDoorHtml, "$vogel->naam mag gevlogen worden door:") }}</dd>
	</dl>
	
	@if($vogel->alert != "")
	{{ Alert::error("<strong>Let op!</strong> $vogel->alert")->open() }}
	@endif

@if($vogel->gewichten()->where("datum", ">", new DateTime("last month"))->count() > 0)
<script type="text/javascript">
grafiekImageSize = function()
{
	grafiek = $("#grafiek")
	baseUrl = "{{URL::to_route("vogelgrafiek", array($vogel->id))}}"
	src = baseUrl + "?width=" + grafiek.width() + "&height=" + grafiek.height()
	
	grafiek.attr("src", src);	
}

$(function() {
	grafiekImageSize();
	$(window).resize(grafiekImageSize);
});
</script>
<img src="{{URL::to_route("vogelgrafiek", array($vogel->id))}}" id="grafiek">
@endif

	<h2>Dagboek</h2>
	<p><a href="#verslagModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Nieuwe verslag</a></p>
	
	<ul class="media-list">
	<?php $vorigeDatum = ""; ?>
	@foreach ($verslagen->results as $verslag)
		<?php if($verslag->datum != $vorigeDatum) { ?>
			<h4 class="media-heading">{{$verslag->datum}}</h4>
		<?php $vorigeDatum = $verslag->datum; } ?>
		<?php $magEditen = Auth::user()->admin || (Auth::user()->id == $verslag->gebruiker->id && (new DateTime($verslag->datum_edit) == new DateTime("today"))); ?>
		<li class="media {{ $magEditen ? "hover-edit" : "" }}">
			<a class="pull-left" href="{{ URL::to_route("gebruikerDetail", array($verslag->gebruiker->id, $verslag->gebruiker->gebruikersnaam)) }}">
				{{ $verslag->gebruiker->thumbnail_image(null, null, null, array("class"=>"media-object")) }}
			</a>
			<div class="media-body">
				<strong>{{$verslag->gebruiker->naam}}</strong>: {{$verslag->tekst}}
			</div>
			@if($magEditen)
			<div class="hover-edit-tools">
				<a href="{{ URL::to_route("vogelVerslagEdit", array($verslag->id)) }}"><i class="icon icon-pencil"></i></a>
			</div>
			@endif
		</li>
	@endforeach
	</ul>
	{{ $verslagen->links() }}

</div>
<div class="span4">
	<div class="hover-div">
		{{$vogel->thumbnail_image("foto", "large") }}
		@if(Auth::user()->admin)
		<div class="hover-text">
			<a href="#fotoModal" role="button" data-toggle="modal"><i class="icon icon-pencil icon-white"></i></a>
		</div>
		@endif
	</div>
	
	<h2>Notities</h2>
	{{ $vogel->informatie }}
	@if(Auth::user()->admin)
	<p><a href="#informatieModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Bewerk informatie</a></p>
	<p><a href="#vliegpermissiesModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Bewerk vliegpermissies</a></p>
	<p><a href="#alertModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Bewerk waarschuwing</a></p>
	<p><a href="#categorieModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Wijzig categorie</a></p>
	@endif
</div>
</div>


@if(Auth::user()->admin)
<div id="informatieModal" class="modal hide fade modal-large" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesInformatie) }}
	{{ Form::hidden("action", "informatie") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Bewerk algemene informatie</h3>
	</div>
	<div class="modal-body">
		{{ Form::control_group(Form::label('naam', 'Naam'), Form::text('naam', $vogel->naam)) }}
		<?php 
		if($vogel->geboortedatum === null) {
			$gebroortedatum = null;
		} else {
			$dt = new DateTime($vogel->geboortedatum);
			$gebroortedatum =  $dt->format("d-m-Y");
		}
		$eigenaren = array();
		$eigenaren[0] = "Falconcrest";
		foreach(Gebruiker::order_by("naam", "asc")->lists("naam", "id") as $key=>$value) {
			$eigenaren[$key] = $value;
		}
		?>
		{{ Form::control_group(Form::label('geslacht', 'Geslacht'), Form::select('geslacht', array("onbekend"=>"Onbekend", "tarsel"=>"Tarsel", "wijf"=>"Wijf"), $vogel->geslacht)) }}
		{{ Form::control_group(Form::label('geboortedatum', 'Geboortedatum'), Form::text('geboortedatum', $gebroortedatum, array("class"=>"datepicker"))) }}
		{{ Form::control_group(Form::label('eigenaar', 'Eigenaar:'), Form::select('eigenaar', $eigenaren, $vogel->eigenaar_id === null ? 0 : $vogel->eigenaar_id)) }}
		{{ CKEditor::make('informatie', $vogel->informatie) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>
@endif

<div id="verslagModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesVerslag) }}
	{{ Form::hidden("action", "verslag") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Nieuwe verslag</h3>
	</div>
	<div class="modal-body">
		{{ Form::control_group(Form::label('tekst', 'Informatie:'), Form::textarea('tekst')) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>

@if(Auth::user()->admin)
<div id="alertModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesAlert) }}
	{{ Form::hidden("action", "alert") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Bewerk waarschuwing</h3>
	</div>
	<div class="modal-body">
		{{ Form::control_group(Form::label('alert', 'Waarschuwing:'), Form::text('alert', $vogel->alert)) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>
@endif

@if(Auth::user()->admin)
<div id="fotoModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::vertical_open_for_files() }}
	{{ Form::rules($rulesFoto) }}
	{{ Form::hidden("action", "foto") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Nieuwe foto uploaden</h3>
	</div>
	<div class="modal-body">
		{{ Form::file('foto') }}<br>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>
@endif

@if(Auth::user()->admin)
<div id="categorieModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesCategorie) }}
	{{ Form::hidden("action", "categorie") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Wijzig categorie</h3>
	</div>
	<div class="modal-body">
		{{ Form::control_group(Form::label('categorie', 'Categorie:'), Form::select('categorie', Categorie::order_by("order", "asc")->lists("naam", "id"), $vogel->categorie->id)) }}
		<?php 
		if($vogel->overleidensdatum === null) {
			$overleidensdatum = null;
		} else {
			$dt = new DateTime($vogel->overleidensdatum);
			$overleidensdatum =  $dt->format("d-m-Y");
		}
		?>
		{{ Form::control_group(Form::label('overleidensdatum', 'Overleidensdatum in geval van overleiden'), Form::text('overleidensdatum', $overleidensdatum, array("class"=>"datepicker"))) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>
@endif

@if(Auth::user()->admin)
<div id="vliegpermissiesModal" class="modal hide fade modal-large" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::hidden("action", "vliegpermissies") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Bewerk vliegpermissies</h3>
	</div>
	<div class="modal-body">
		<p>{{ $vogel->naam }} mag gevlogen worden door:</p>
		@foreach(Gebruiker::all() as $gebruiker)
			{{ Form::labelled_checkbox('gebruiker-' . $gebruiker->id, $gebruiker->naam, '1', count($gebruiker->vliegpermissies()->where_vogel_id($vogel->id)->get()) == 1) }}
		@endforeach
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>
@endif

@endsection
