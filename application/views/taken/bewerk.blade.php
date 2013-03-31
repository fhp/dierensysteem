@layout('master')

@section('content')
<h1>{{ $taak->naam }}</h1>

{{ Form::horizontal_open() }}
{{ Form::rules($rulesBewerkTaak) }}
{{ Form::hidden("action", "bewerk") }}
<h3>Bewerk taak</h3>
{{ Form::control_group(Form::label('naam', 'Naam'), Form::text('naam', $taak->naam)) }}
{{ Form::control_group(Form::label('beschrijving', 'Informatie:'), Form::textarea('beschrijving', $taak->beschrijving)) }}
{{ Form::control_group(Form::label('frequentie', 'Frequentie'), Form::select('frequentie', array("1"=>"Dagtaak", 7=>"Weektaak"), $taak->frequentie)) }}
{{ Form::actions(array(Button::primary_submit('Opslaan'))) }}
{{ Form::close() }}


{{ Form::horizontal_open() }}
{{ Form::hidden("action", "verwijder") }}
<h3>Verwijder taak</h3>
{{ Form::actions(array(Button::primary_submit('Verwijderen'))) }}
{{ Form::close() }}

@endsection
