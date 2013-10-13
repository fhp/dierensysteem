@layout('master')

@section('content')
<h1>Wegen</h1>
<p><i>{{ $datum->format("d-m-Y") }}</i></p>

<p><a href="{{ URL::to_route("wegenPdf") }}" class="btn"><i class="icon icon-file"></i> Download weeglijst</a></p>

@if(Auth::check())
{{ Form::horizontal_open() }}
@foreach(Vogel::where_wegen(1)->order_by("naam", "asc")->get() as $vogel)
	{{ Form::control_group(Form::label('vogel_' . $vogel->id, $vogel->naam), Form::text('vogel_' . $vogel->id, $vogel->gewicht($datum))) }}
@endforeach
{{ Form::actions(array(Button::primary_submit('Opslaan'))) }}
{{ Form::close() }}

@if(isAdmin())
<?php
$dag = new DateInterval("P1D");

$morgen = new DateTime($datum->format("d-m-Y"));
$morgen->add($dag);
$gisteren = new DateTime($datum->format("d-m-Y"));
$gisteren->sub($dag);

echo "<span class=\"pull-left\">" . HTML::link_to_route("wegen", "<<< Vorige dag", array($gisteren->format("Y"), $gisteren->format("m"), $gisteren->format("d"))) . "</span>";
echo "<span class=\"pull-right\">" . HTML::link_to_route("wegen", "Volgende dag >>>", array($morgen->format("Y"), $morgen->format("m"), $morgen->format("d"))) . "</span>";
?>
@endif
@else
<div class="alert"><strong>Je bent niet ingelogt!</strong> Om de gewichten van de vogels op te slaan moet je eerst inloggen!</div>
@endif

@endsection