@layout('master')

@section('content')
<h1>Bewerken notule</h1>
<p>Bij agendapunt <a href="{{ URL::to_route("vergaderingAgendapunt", array($notule->agendapunt->id)) }}">{{$notule->agendapunt->titel}}:</a></p>

{{nl2br($notule->agendapunt->omschrijving)}}


{{ Form::horizontal_open() }}
{{ Form::hidden("action", "notule") }}
	<textarea cols="100" rows="10" id="omschrijving" name="omschrijving" style="width: 516px;">{{ $notule->omschrijving }}</textarea>
{{ Form::actions(array(Button::primary_submit('Opslaan'))) }}
{{ Form::close() }}

@endsection
