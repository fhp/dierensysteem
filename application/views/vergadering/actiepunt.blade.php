@layout('master')

@section('content')
<h1>Actiepunt: {{$actiepunt->titel}}</h1>
@if($actiepunt->voltooid)
<div class="alert">Actiepunt is voltooid.</div>
@endif
<p><i>Agendapunt: <a href="{{ URL::to_route("vergaderingAgendapunt", array($actiepunt->agendapunt->id)) }}">{{$actiepunt->agendapunt->titel}}</a></i></p>

{{nl2br($actiepunt->omschrijving)}}


{{ Form::horizontal_open() }}
{{ Form::hidden("action", "actiepunt") }}
	<h3>Opmerkingen</h3>
	<textarea cols="100" rows="10" id="opmerking" name="opmerkingen" style="width: 516px;">{{ $actiepunt->opmerkingen }}</textarea>
	{{ Form::control_group(Form::label('sluiten', 'Actiepunt voltooid'), Form::labelled_checkbox('sluiten', "Ja", '1', $actiepunt->voltooid)) }}
{{ Form::actions(array(Button::primary_submit('Opslaan'))) }}
{{ Form::close() }}

@endsection
