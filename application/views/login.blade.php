@layout('master')

@section('content')
<h1>Login</h1>
<?php
$error = (isset($error) && $error);

echo Form::horizontal_open();
if($error) {
	echo Form::block_help('De opgegeven gegevens zijn incorrect.', 'error');
}
echo Form::control_group(Form::label('username', 'Gebruikersnaam'), Form::text('username', Input::get("username")), $error ? "error" : null);
echo Form::control_group(Form::label('password', 'Wachtwoord'), Form::password('password'), $error ? "error" : null);
echo Form::control_group(Form::label('checkme', ''), Form::labelled_checkbox('checkme', 'Onthoud mij'));
echo Form::actions(array(Button::primary_submit('Login')));
echo Form::close();
?>
@endsection
