@layout('master')

@section('content')
<h1>Nieuw vogel</h1>
{{ Form::horizontal_open_for_files() }}

{{ Form::control_group(Form::label('naam', 'Naam'), Form::text('naam')) }}
{{ Form::control_group(Form::label('geslacht', 'Geslacht'), Form::select('geslacht', array("onbekend"=>"Onbekend", "tarsel"=>"Tarsel", "wijf"=>"Wijf"))) }}
{{ Form::control_group(Form::label('soort', 'Soort'), Form::select('soort', $soorten)) }}
{{ Form::control_group(Form::label('foto', 'Foto'), Form::file('foto')) }}

{{ Form::actions(array(Button::primary_submit('Opslaan'))) }}

{{ Form::close() }}
@endsection
