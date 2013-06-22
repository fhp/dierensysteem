@layout('master')

@section('content')

@if(isAdmin())
<script>
function saveOrder()
{
	$.ajax("", {
		type: "POST",
		data: {
			action: "sorteer",
@foreach(Vliegvolgordelijst::all() as $lijst)
			lijst_{{$lijst->id}}: $("#lijst_{{$lijst->id}}").sortable('serialize'),
@endforeach
			lijsten: $("#lijsten").sortable('serialize'),
		}
	});
}

function deleteElements()
{
	$.ajax("", {
		type: "POST",
		data: {
			action: "delete",
			elements: $("#delete").sortable('serialize'),
		}
	});
	$("#delete li").remove();
}

$(function() {
	$( "#lijsten" ).sortable({
		placeholder: "ui-state-highlight",
		update: saveOrder,
		dropOnEmpty: true,
		items: "> li",
		connectWith: ".delete",
	}).disableSelection();
	$( ".lijst" ).sortable({
		placeholder: "ui-state-highlight",
		update: saveOrder,
		dropOnEmpty: true,
		items: "li",
		start: function() { $("#delete").show('slow'); },
		stop: function() { $("#delete").hide('slow'); },
		connectWith: ".lijst, .delete",
	}).disableSelection();
	
	$( ".delete" ).sortable({
		placeholder: "ui-state-highlight",
		update: deleteElements,
		dropOnEmpty: true,
		items: "li",
	}).disableSelection();
})
</script>
@endif

 <style>
.lijsten { list-style-type: none; margin: 0; }
.lijsten > li { float: left; width: 300px; }
.lijsten > .ui-state-highlight { height: 1.5em; padding: 0 0 2.5em; width: 288px; margin: 0; margin-right: 10px; }

.lijst { list-style-type: none; margin: 0; padding: 0 0 2.5em; margin-right: 10px; }
.lijst > li { margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; }
.lijst > .ui-state-highlight { height: 30px; }

.delete { list-style-type: none; margin: 0; padding: 0 0 2.5em; margin-right: 10px; display: none; }
.delete > li { margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; }
.delete > .ui-state-highlight { height: 30px; background: #F2DEDE; border-color: #EED3D7; }

.ui-state-default { background: none; border: 0px; height: 32px; }
</style>

<h1>Vliegvolgorde</h1>

<ul id="lijsten" class="lijsten">
@foreach(Vliegvolgordelijst::order_by("volgorde")->get() as $lijst)
	<li id="lijsten_{{$lijst->id}}">
	<ul id="lijst_{{$lijst->id}}" class="lijst">
	<h2>{{$lijst->naam}}</h2>
	@foreach($lijst->vogels()->order_by("volgorde")->get() as $vogel)
		<li class="ui-state-default" id="vogel_{{$vogel->pivot->id}}">{{ $vogel->thumbnail_image(null, "xsmall") }} {{ $vogel->naam }}
		@if($vogel->pivot->opmerkingen !== null)
			({{ $vogel->pivot->opmerkingen}})
		@endif
		</li>
	@endforeach
	</ul>
	</li>
@endforeach
</ul>

<span class="clearfix"></span>

@if(isAdmin())
<ul id="delete" class="delete">
<h2>Delete</h2>
</ul>
@endif
<p><a href="{{ URL::to_route("vliegvolgordepdf") }}" class="btn"><i class="icon icon-list"></i> Lijst printen</a></p>
@if(isAdmin())
<p><a href="#nieuwevogelModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-plus"></i> Vogel toevoegen</a></p>
<p><a href="#nieuwelijstModal" role="button" data-toggle="modal" class="btn"><i class="icon icon-plus"></i> Lijst toevoegen</a></p>

<div id="nieuwevogelModal" class="modal hide fade modal-large" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::hidden("action", "nieuweVogel") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Vogel toevoegen</h3>
	</div>
	<div class="modal-body">
		{{ Form::control_group(Form::label('vogel', 'Vogel'), Form::select('vogel', Vogel::order_by("naam")->lists("naam", "id"))) }}
		{{ Form::control_group(Form::label('lijst', 'Lijst'), Form::select('lijst', Vliegvolgordelijst::order_by("naam")->lists("naam", "id"))) }}
		{{ Form::control_group(Form::label('opmerkingen', 'Opmerkingen'), Form::text('opmerkingen')) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>

<div id="nieuwelijstModal" class="modal hide fade modal-large" tabindex="-1" role="dialog">
	{{ Form::horizontal_open() }}
	{{ Form::hidden("action", "nieuweLijst") }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Nieuwe lijst</h3>
	</div>
	<div class="modal-body">
		{{ Form::control_group(Form::label('naam', 'Naam'), Form::text('naam')) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>

@endif


@endsection
