@layout('master')

@section('content')
<div class="row">
<div class="span6">
	<h1>{{$vogel->naam}}</h1>
	
	<dl class="dl-horizontal">
	<dt>Soort</dt><dd>{{ HTML::link_to_route("soortDetail", $vogel->soort->naam, array($vogel->soort->id, $vogel->soort->naam)) }}</dd>
	<dt>Geslacht</dt><dd>{{ Str::title($vogel->geslacht) }}&nbsp;</dd>
	<dt>Leeftijd</dt><dd>{{ $vogel->leeftijd }}</dd>
	<dt>Categorie</dt><dd>{{ $vogel->categorie->naam }}</dd>
<!-- 	<dt>Standaard eten</dt><dd>{{ HTML::etenVogel($vogel) }}</dd> -->
	@if($vogel->eigenaar !== null)
	<dt>Eigenaar</dt><dd>{{ HTML::link_to_route("gebruikerDetail", $vogel->eigenaar->naam, array($vogel->eigenaar->id, $vogel->eigenaar->naam)) }}</dd>
	@endif
<?php
$gebruikers = $vogel->vliegpermissies;
$gebruikerCount = count($gebruikers);
$magGevlogenWordenDoorHtml = "";
foreach($gebruikers as $gebruiker) {
	$magGevlogenWordenDoorHtml .= "<a href=\"" . URL::to_route("gebruikerDetail", array($gebruiker->id, $gebruiker->naam)) . "\">" . $gebruiker->thumbnail_image(null, "xsmall") . " " . $gebruiker->naam . "</a>";
	if($gebruiker->pivot->opmerkingen !== null) {
		$magGevlogenWordenDoorHtml .= " (" . $gebruiker->pivot->opmerkingen . ")";
	}
	$magGevlogenWordenDoorHtml .= "<br>";
}
?>
	<!--<dt>Mag gevlogen worden door</dt><dd>{{ HTML::popup($gebruikerCount . ($gebruikerCount == 1 ? " persoon" : " personen"), $magGevlogenWordenDoorHtml, "$vogel->naam mag gevlogen worden door:") }}</dd>-->
	</dl>
	
	@if($vogel->alert != "")
	{{ Alert::error("<strong>Let op!</strong> $vogel->alert")->open() }}
	@endif

@if($vogel->gewichten()->where("datum", ">", new DateTime("last month"))->count() > 0)
<script type="text/javascript">
grafiekImageUpdate = function()
{
	grafiekUpdate($("#grafiek"));
}

grafiekFSImageUpdate = function()
{
	grafiek = $("#fullscreenGrafiek");
	grafiek.show();
	grafiek.position({ top: 0, left: 0 });
	$(document).scrollTop(0);
	$('body').css('overflow', 'hidden');
	grafiek.height($(window).height());
	grafiek.width($(window).width());
	grafiekUpdate(grafiek);
	grafiek.click(function() {
		grafiek.hide();
		$('body').css('overflow', 'auto');
	});
}

grafiekUpdate = function(grafiek)
{
	grafiek.width(Math.round(grafiek.width()));
	
	baseUrl = "{{URL::to_route("vogelgrafiek", array($vogel->id))}}"
	src = baseUrl + "?width=" + grafiek.width() + "&height=" + grafiek.height() + "&start=" + $("#grafiekStartDatum").val() + "&einde=" + $("#grafiekEindDatum").val()
	
	grafiek.attr("src", src);
}

$(function() {
	grafiekImageUpdate();
	$(window).resize(grafiekImageUpdate);
	$("#grafiek").click(function() {
		$("#grafiekopties").show();
	});
	$("#grafiekopties").hide();
	$(".grafiekParameter").change(grafiekImageUpdate);
	
	$("#grafiekFullscreenBtn").click(grafiekFSImageUpdate);
	$("#fullscreenGrafiek").hide();
});
</script>
<img src="{{URL::to_route("vogelgrafiek", array($vogel->id))}}" id="grafiek">
<div id="grafiekopties">
{{ Form::horizontal_open() }}
{{ Form::control_group(Form::label('grafiekStartDatum', 'Start datum'), Form::text('grafiekStartDatum', formatDate("last month", "d-m-Y"), array("class"=>"datepicker grafiekParameter"))) }}
{{ Form::control_group(Form::label('grafiekEindDatum', 'Eind datum'), Form::text('grafiekEindDatum', date("d-m-Y"), array("class"=>"datepicker grafiekParameter"))) }}
{{ Form::control_group(Form::label('fullscreen', ''), '<p><a href="#grafiekFullscreenModal-weg" role="button" data-toggle="modal" class="btn" id="grafiekFullscreenBtn"><i class="icon icon-fullscreen"></i> Vergroten</a></p>') }}
{{ Form::close() }}
</div>

<img src="{{URL::to_route("vogelgrafiek", array($vogel->id))}}" id="fullscreenGrafiek" style="position:absolute; top: 0; left: 0; z-index: 100; background: white;">

@endif

	<h2>Dagboek</h2>
	@if(Auth::check())
	<p><a href="#verslagModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Nieuwe verslag</a></p>
	@endif
	
	<ul class="media-list vogelverslagen">
	<?php $vorigeDatum = ""; ?>
	@foreach ($verslagen->results as $verslag)
		<?php if($verslag->datum != $vorigeDatum) { ?>
			<h4 class="media-heading">{{$verslag->datum}}</h4>
		<?php $vorigeDatum = $verslag->datum; } ?>
		<?php $magEditen = isAdmin() || (Auth::check() && Auth::user()->id == $verslag->gebruiker->id && (new DateTime($verslag->datum_edit) == new DateTime("today"))); ?>
		<li class="media {{ $magEditen ? "hover-edit" : "" }} {{ $verslag->belangrijk ? "belangrijk" : "" }}">
			<a class="pull-left" href="{{ URL::to_route("gebruikerDetail", array($verslag->gebruiker->id, $verslag->gebruiker->gebruikersnaam)) }}">
				{{ $verslag->gebruiker->thumbnail_image(null, null, null, array("class"=>"media-object")) }}
			</a>
			<div class="media-body">
				<strong>{{$verslag->gebruiker->naam}}</strong>: {{ nl2br(vogelLinks($verslag->tekst)) }}
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
		{{ $vogel->thumbnail_image("foto", "large") }}
		@if(isAdmin())
		<div class="hover-text">
			<a href="#fotoModal" role="button" data-toggle="modal"><i class="icon icon-pencil icon-white"></i></a>
		</div>
		@endif
	</div>
	
	<h2>Eten</h2>
	<p>Standaard portie: {{ HTML::etenVogel($vogel) }}</p>
	@if(Auth::check())
	<p><a href="#etenModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Eten invullen</a></p>
	@endif
	<table>
	<?php
	$dagNaam = array("Zo", "Ma", "Di", "Wo", "Do", "Vr", "Za");
	for($i = 0; $i < 6; $i++) {
		$dagDatum = new DateTime("today" /*$datum->format("d-m-Y")*/);
		$dagDatum->sub(new DateInterval("P{$i}D"));
		$dag = $dagNaam[$dagDatum->format("w")] . " " . $dagDatum->format('d-m');
		
		echo "<tr><td>" . $dag . "&nbsp;</td><td>" . HTML::eten($vogel, $dagDatum) . "</td></tr>\n";
	}
	?>
	</table>
		
	@if($vogel->informatie != "")
	<h2>Notities</h2>
	{{ $vogel->informatie }}
	@endif
	@if(isAdmin())
	<h2>Beheer</h2>
	<p><a href="#informatieModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Bewerk informatie</a></p>
	<!--<p><a href="#vliegpermissiesModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Bewerk vliegpermissies</a></p>-->
	<p><a href="#alertModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Bewerk waarschuwing</a></p>
	<p><a href="#categorieModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-pencil"></i> Wijzig categorie</a></p>
	@endif
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
		{{ Form::control_group(Form::label('naam', 'Naam'), Form::text('naam', $vogel->naam)) }}
		<?php 
		if($vogel->geboortedatum === null) {
			$gebroortedatum = null;
		} else {
			$dt = new DateTime($vogel->geboortedatum);
			$gebroortedatum =  $dt->format("d-m-Y");
		}
		$eigenaren = array();
		$eigenaren[0] = "n.v.t.";
		foreach(Gebruiker::where_nonactief(0)->order_by("naam", "asc")->lists("naam", "id") as $key=>$value) {
			$eigenaren[$key] = $value;
		}
		?>
		{{ Form::control_group(Form::label('geslacht', 'Geslacht'), Form::text('geslacht', $vogel->geslacht)) }}
		{{ Form::control_group(Form::label('geboortedatum', 'Geboortedatum'), Form::text('geboortedatum', $gebroortedatum, array("class"=>"datepicker"))) }}
		{{ Form::control_group(Form::label('wegen', 'Wegen'), Form::labelled_checkbox('wegen', "Ja", '1', $vogel->wegen)) }}
		{{ Form::control_group(Form::label('kuikens', 'Aantal kuikens:'), Form::text('kuikens', $vogel->kuikens)) }}
		{{ Form::control_group(Form::label('hamsters', 'Aantal hamsters:'), Form::text('hamsters', $vogel->hamsters)) }}
		{{ Form::control_group(Form::label('duif', 'Duif:'), Form::checkbox('duif', '1', $vogel->duif)) }}
		{{ Form::control_group(Form::label('eten_opmerking', 'Opmerkingen over eten:'), Form::text('eten_opmerking', $vogel->eten_opmerking)) }}
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

@if(Auth::check())
<div id="etenModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesEten) }}
	{{ Form::hidden("action", "eten") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Eten opgeven</h3>
	</div>
	<div class="modal-body">
		@if(isAdmin())
		{{ Form::control_group(Form::label('datum', 'Datum'), Form::text('datum', date("d-m-Y"), array("class"=>"datepicker"))) }}
		@endif
		<?php
		$ingevuld = $vogel->etenIngevuld();
		if($ingevuld) {
			$eten = $vogel->eten();
		}
		?>
		{{ Form::control_group(Form::label('kuikens', 'Aantal kuikens:'), Form::text('kuikens', $ingevuld ? $eten->kuikens : $vogel->kuikens)) }}
		{{ Form::control_group(Form::label('hamsters', 'Aantal hamsters:'), Form::text('hamsters', $ingevuld ? $eten->hamsters : $vogel->hamsters)) }}
		{{ Form::control_group(Form::label('duif', 'Duif:'), Form::checkbox('duif', '1', $ingevuld ? $eten->duif : $vogel->duif)) }}
		{{ Form::control_group(Form::label('opmerking', 'Opmerkingen:'), Form::text('opmerking', $ingevuld ? $eten->opmerking : "")) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>
@endif

@if(Auth::check())
<div id="verslagModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesVerslag) }}
	{{ Form::hidden("action", "verslag") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Nieuwe verslag</h3>
	</div>
	<div class="modal-body">
		@if(isAdmin())
		{{ Form::control_group(Form::label('gebruiker', 'Gebruiker'), Form::select('gebruiker', Gebruiker::where("nonactief", "=", 0)->order_by("naam", "asc")->lists("naam", "id"), Auth::user()->id)) }}
		{{ Form::control_group(Form::label('verslagdatum', 'Datum'), Form::text('verslagdatum', date("d-m-Y"), array("class"=>"datepicker"))) }}
		@endif
		{{ Form::control_group(Form::label('tekst', 'Informatie:'), Form::textarea('tekst')) }}
		{{ Form::control_group(Form::label('belangrijk', 'Belangrijk:'), Form::checkbox('belangrijk', '1')) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>
@endif

@if(isAdmin())
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

@if(isAdmin())
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

@if(isAdmin())
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

@if(isAdmin())
<div id="vliegpermissiesModal" class="modal hide fade modal-large" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::hidden("action", "vliegpermissies") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Bewerk vliegpermissies</h3>
	</div>
	<div class="modal-body">
		<p>{{ $vogel->naam }} mag gevlogen worden door:</p>
		<table>
		<tr><th>Naam</th><th>Opmerkingen</th></tr>
		@foreach(Gebruiker::where_nonactief(0)->order_by("naam", "asc")->get() as $gebruiker)
			<tr>
			<td>{{ Form::labelled_checkbox('gebruiker-' . $gebruiker->id, $gebruiker->naam, '1', $vogel->vliegpermissie($gebruiker->id)) }}</td>
			<td>{{ Form::text('opmerkingen-' . $gebruiker->id, $vogel->vliegpermissieOpmerkingen($gebruiker->id)) }}</td>
			</tr>
		@endforeach
		</table>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>
@endif

@endsection
