@layout('master')

@section('content')

{{ Form::horizontal_open() }}
{{ Form::rules($rulesMededelingBewerk) }}
{{ Form::hidden("action", "bewerk") }}
<h3>Bewerk mededeling</h3>
{{ Form::control_group(Form::label('gebruiker', 'Gebruiker'), Form::select('gebruiker', Gebruiker::where("nonactief", "=", 0)->order_by("naam", "asc")->lists("naam", "id"), $mededeling->gebruiker_id)) }}
{{ Form::control_group(Form::label('datum', 'Datum'), Form::text('datum', $mededeling->datum_edit, array("class"=>"datepicker"))) }}
{{ Form::control_group(Form::label('tekst', 'Tekst'), Form::textarea('tekst', $mededeling->tekst)) }}
{{ Form::actions(array(Button::primary_submit('Opslaan'))) }}
{{ Form::close() }}


{{ Form::horizontal_open() }}
{{ Form::hidden("action", "verwijder") }}
<h3>Verwijder mededeling</h3>
{{ Form::actions(array(Button::primary_submit('Verwijderen'))) }}
{{ Form::close() }}

@endsection
