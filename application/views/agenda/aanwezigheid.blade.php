@layout('master')

@section('content')

<h1>Aanwezigheid van {{ $aanwezigheid->gebruiker->naam }}</h1>

{{ Form::horizontal_open() }}
{{ Form::control_group(Form::label('actief', 'Aanmelding actief'), Form::checkbox('actief', '1', $aanwezigheid->actief)) }}
{{ Form::control_group(Form::label('opmerkingen', 'Opmerkingen'), Form::text('opmerkingen', $aanwezigheid->opmerkingen)) }}
{{ Form::actions(array(Button::primary_submit('Opslaan'))) }}
{{ Form::close() }}

@endsection
