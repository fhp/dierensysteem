@layout('master')

@section('content')
<h1>Wegen</h1>

{{ Form::horizontal_open() }}
@foreach(Vogel::where_wegen(1)->order_by("naam", "asc")->get() as $vogel)
	{{ Form::control_group(Form::label('vogel_' . $vogel->id, $vogel->naam), Form::text('vogel_' . $vogel->id, $vogel->gewicht())) }}
@endforeach
{{ Form::actions(array(Button::primary_submit('Opslaan'))) }}
{{ Form::close() }}

@endsection