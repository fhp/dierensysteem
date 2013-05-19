@layout('master')

@section('content')

<h1>Voerlijst</h1>
<dl class="dl-horizontal">
@foreach(Vogel::order_by("naam")->get() as $vogel)
	<?php
	$eten = HTML::etenVogel($vogel);
	if($eten == "-") {
		continue;
	}
	?>
	<dt>{{ HTML::link_to_route("vogelDetail", $vogel->naam, array($vogel->id, $vogel->naam)) }}</dt><dd>{{ $eten }}</dd>
@endforeach
</dl>

@endsection
