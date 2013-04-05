@layout('master')

@section('content')

{{ Form::horizontal_open() }}
{{ Form::rules($rulesVerslagBewerk) }}
{{ Form::hidden("action", "bewerk") }}
<h3>Bewerk verslag</h3>
{{ Form::control_group(Form::label('gebruiker', 'Gebruiker'), Form::select('gebruiker', Gebruiker::where("nonactief", "=", 0)->order_by("naam", "asc")->lists("naam", "id"), $verslag->gebruiker_id)) }}
{{ Form::control_group(Form::label('datum', 'Datum'), Form::text('datum', $verslag->datum_edit, array("class"=>"datepicker"))) }}
{{ Form::control_group(Form::label('tekst', 'Tekst'), Form::textarea('tekst', $verslag->tekst)) }}
{{ Form::actions(array(Button::primary_submit('Opslaan'))) }}
{{ Form::close() }}


{{ Form::horizontal_open() }}
{{ Form::hidden("action", "verwijder") }}
<h3>Verwijder verslag</h3>
{{ Form::actions(array(Button::primary_submit('Verwijderen'))) }}
{{ Form::close() }}

@endsection
