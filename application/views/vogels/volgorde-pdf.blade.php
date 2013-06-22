<html>
<head>
<style>
.lijsten { list-style-type: none; margin: 0; }
.lijsten > li { float: left; width: 300px; }
.lijsten > .ui-state-highlight { height: 1.5em; padding: 0 0 2.5em; width: 288px; margin: 0; margin-right: 10px; }

.lijst { list-style-type: none; margin: 0; padding: 0 0 2.5em; margin-right: 10px; }
.lijst > li { margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; }
.lijst > .ui-state-highlight { height: 50px; }

.ui-state-default { background: none; border: 0px; height: 48px; }
</style>
</head>
<body>
<h1>Vliegvolgorde</h1>

<ul id="lijsten" class="lijsten">
@foreach(Vliegvolgordelijst::order_by("volgorde")->get() as $lijst)
	<li id="lijsten_{{$lijst->id}}">
	<ul id="lijst_{{$lijst->id}}" class="lijst">
	<h2>{{$lijst->naam}}</h2>
	@foreach($lijst->vogels()->order_by("volgorde")->get() as $vogel)
		<li class="ui-state-default" id="vogel_{{$vogel->pivot->id}}">{{ $vogel->thumbnail_image(null, "small") }} {{ $vogel->naam }}
		@if($vogel->pivot->opmerkingen !== null)
			({{ $vogel->pivot->opmerkingen}})
		@endif
		</li>
	@endforeach
	</ul>
	</li>
@endforeach
</ul>

</body>
</html>
