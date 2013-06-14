@layout('master')

@section('content')
<?php
$datum = new DateTime($aanwezigheid->datum);
?>
<div>
	{{ Form::horizontal_open() }}
		<h3>Bewerk de uren van {{ $aanwezigheid->gebruiker->naam }} op {{ $datum->format("d-m-Y") }}</h3>
		{{ Form::control_group(Form::label('start', 'Starttijd'), Form::text('start', $aanwezigheid->start !== null ? formatTijd($aanwezigheid->start) : "")) }}
		{{ Form::control_group(Form::label('einde', 'Eindtijd'), Form::text('einde', $aanwezigheid->einde !== null ? formatTijd($aanwezigheid->einde) : "")) }}
	{{ Form::actions(array(Button::primary_submit('Opslaan'))) }}
	{{ Form::close() }}
</div>
@endsection
