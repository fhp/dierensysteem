@layout('master')

@section('content')
<h1>Agenda
<?php
$maanden = array("", "januari", "februari", "maart", "april", "mei", "juni", "juli", "augustus", "november", "december");
echo $maanden[(int)$maand] . " " . $jaar;
?>
</h1>

<a href="{{ URL::to_route("agendaWeek") }}" class="pull-right">Week weergave</a>

@render("widgets.calendar", array("month"=>$maand, "year"=>$jaar, "cell"=>"agenda.calendar-cell", "cellData"=>$dagenData))

<div id="evenementModal" class="modal hide fade" tabindex="-1" role="dialog">
	{{ Form::horizontal_open(URL::to_route("agendaEvenement")) }}
	{{ Form::rules($rulesEvenement) }}
	{{ Form::hidden("action", "nieuwEvenement") }}
	<input type="hidden" name="datum" id="datum">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Nieuwe evenement</h3>
	</div>
	<div class="modal-body">
		{{ Form::control_group(Form::label('naam', 'Naam'), Form::text('naam')) }}
		{{ Form::control_group(Form::label('beschrijving', 'Informatie:'), Form::textarea('beschrijving')) }}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Sluiten</button>
		<button class="btn btn-primary">Opslaan</button>
	</div>
	{{ Form::close() }}
</div>

@endsection
