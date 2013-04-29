@layout('master')

@section('content')

<script>
function saveOrder()
{
	$.ajax("", {
		type: "POST",
		data: {
@foreach(Vliegvolgorde::all() as $lijst)
			lijst_{{$lijst->id}}: $("#lijst_{{$lijst->id}}").sortable('serialize'),
@endforeach
		}
	});
}

$(function() {
	$( ".lijst" ).sortable({
		connectWith: ".connectedSortable",
		placeholder: "ui-state-highlight",
		update: saveOrder,
		dropOnEmpty: true,
		items: "li"
	}).disableSelection();
})
</script>

 <style>
.lijst { list-style-type: none; margin: 0; padding: 0 0 2.5em; float: left; margin-right: 10px; }
.lijst li { margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; width: 120px; }
.lijst { float: left; width: 300px; }
.ui-state-highlight { height: 1.5em; line-height: 1.2em; }

.ui-state-default {
	background: none;
	border: 0px;
}
</style>

<h1>Vliegvolgorde</h1>

@foreach(Vliegvolgorde::order_by("order")->get() as $lijst)
	<div class="lijst">
	<ul id="lijst_{{$lijst->id}}" class="lijst connectedSortable">
	<h2>{{$lijst->naam}}</h2>
	@foreach($lijst->vogels()->order_by("lijst_volgorde")->get() as $vogel)
		<li class="ui-state-default" id="vogel_{{$vogel->id}}">{{ $vogel->thumbnail_image(null, "xsmall") }} {{ $vogel->naam }}</li>
	@endforeach
	</ul>
	</div>
@endforeach

<div class="lijst">
<ul id="lijst_overig" class="lijst connectedSortable">
<h2>Overige vogels</h2>
@foreach(Vogel::where_null("lijst_id")->where_categorie_id(1)->order_by("naam")->get() as $vogel)
	<li class="ui-state-default" id="vogel_{{$vogel->id}}">{{ $vogel->thumbnail_image(null, "xsmall") }} {{ $vogel->naam }}</li>
@endforeach
</ul>
</div>


@endsection
