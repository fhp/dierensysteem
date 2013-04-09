<?php
/* draw table */
$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

/* table headings */
$headings = array('Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag', 'Zondag');
$calendar.= '<thead><tr><th>'.implode('</th><th>',$headings).'</th></tr></thead>';

/* days and weeks vars now ... */
$running_day = date('w',mktime(0,0,0,$month,1,$year));
if($running_day == 0) {
	$running_day = 7;
}
$running_day--;
$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
$days_in_this_week = 1;
$day_counter = 0;
$dates_array = array();

/* row for week one */
$calendar.= '<tbody><tr>';

/* print "blank" days until the first of the current week */
for($x = 0; $x < $running_day; $x++) {
	$calendar.= '<td class="calendar-day-np"> </td>';
	$days_in_this_week++;
}

/* keep going with days.... */
for($list_day = 1; $list_day <= $days_in_month; $list_day++) {
	$calendar.= '<td class="calendar-day">';
	
	/* events */
	$calendar .= render($cell, array("day"=>$list_day, "data"=>isset($cellData[$list_day]) ? $cellData[$list_day] : null));
	
	$calendar.= '</td>';
	if($running_day == 6) {
		$calendar.= '</tr>';
		if(($day_counter+1) != $days_in_month) {
			$calendar.= '<tr>';
			$running_day = -1;
			$days_in_this_week = 0;
		}
	}
	$days_in_this_week++;
	$running_day++;
	$day_counter++;
}
/* finish the rest of the days in the week */
if($days_in_this_week < 8) {
	for($x = 1; $x <= (8 - $days_in_this_week); $x++) {
		$calendar.= '<td class="calendar-day-np"> </td>';
	}
}

/* final row */
$calendar.= '</tr></tbody>';

/* end the table */
$calendar.= '</table>';

$nextMonth = array($year, $month + 1);
$prevMonth = array($year, $month - 1);
if($month == 12) {
	$nextMonth = array($year + 1, 1);
} else if($month == 1) {
	$prevMonth = array($year - 1, 12);
}

$calendar .= "<span class=\"pull-left\">" . HTML::link_to_route("agendaMaand", "<<< Vorige maand", $prevMonth) . "</span>";
$calendar .= "<span class=\"pull-right\">" . HTML::link_to_route("agendaMaand", "Volgende maand >>>", $nextMonth) . "</span>";


echo $calendar;
?>