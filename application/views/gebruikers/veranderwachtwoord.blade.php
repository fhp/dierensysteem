@layout('master')

@section('content')
<div>
	{{ Form::horizontal_open() }}
	{{ Form::rules($rulesWachtwoord) }}
		<h3>Verander wachtwoord</h3>
		<p>Voordat je gebruik kan maken van dit systeem, moet je een eigen wachtwoord instellen.</p>
		{{ Form::control_group(Form::label('wachtwoord', 'Wachtwoord'), Form::password('wachtwoord')) }}
		{{ Form::control_group(Form::label('wachtwoord_confirmation', 'Bevestig wachtwoord'), Form::password('wachtwoord_confirmation')) }}
	{{ Form::actions(array(Button::primary_submit('Opslaan'))) }}
	{{ Form::close() }}
</div>
@endsection
