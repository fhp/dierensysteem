<?php

$aantalDagen = 14;

if(!function_exists("vogelsTableHeader")) {
	function vogelsTableHeader($aantalDagen, $offset)
	{
		$dagen = array("zo", "ma", "di", "wo", "do", "vr", "za");
		
		$html = "<table>";
		$html .= "<tr><td>&nbsp;</td>";
		for($i = 0; $i < $aantalDagen; $i++) {
			$date = time() + ($i + $offset) * 3600 * 24;
			$html .= "<td class=\"date\">" . $dagen[date("w", $date)] . " " . date("d-m", $date) . "</td>";
		}
		$html .= "</tr>";
		return $html;
	}
}

$vogels = Vogel::where_wegen(1)->order_by("naam", "asc")->get();

$height = min(max(16 / min(count($vogels), 25), 0.6), 1);


$html = "<html><body>";
$html .= <<<STYLE
<style>
/*table {
	page-break-after: always;
}*/
table, tr, td {
	border: 1pt solid black;
	border-collapse: collapse;
}
td {
	white-space: nowrap;
	vertical-align: middle;
}
tr.vogel {
	line-height: $height cm;
}
body, table {
	width: 100%;
}
td.date {
	text-align: center;
	font-weight: bold;
}
td.gewicht {
	vertical-align: top;
	padding: 0px;
}
.braakbal {
	width: 0.3cm;
	height: 0.3cm;
	border-left: 1pt solid black;
	border-bottom: 1pt solid black;
	float:right;
}
</style>
STYLE;

$html .= vogelsTableHeader($aantalDagen, 0);
$i = 0;
foreach($vogels as $vogel) {
	if($i % 25 == 0 && $i != 0) {
		$html .= "</table>";
		$html .= vogelsTableHeader($aantalDagen, 0);
	}
	$i++;
	$html .= "<tr class=\"vogel\"><td>" . $vogel->naam . "</td>";
	$html .= str_repeat("<td class=\"gewicht\"><div class=\"braakbal\"></div></td>", $aantalDagen);
	$html .= "</tr>";
}
$html .= "</table>";

if(count($vogels) < 7) {
	$html .= vogelsTableHeader($aantalDagen, 14);
	$i = 0;
	foreach ($vogels as $vogel) {
		if($i % 25 == 0 && $i != 0) {
			$html .= "</table>";
			$html .= vogelsTableHeader($aantalDagen, 14);
		}
		$i++;
		$html .= "<tr class=\"vogel\"><td>" . $vogel->naam . "</td>";
		$html .= str_repeat("<td class=\"gewicht\"><div class=\"braakbal\"></div></td>", $aantalDagen);
		$html .= "</tr>";
	}
	$html .= "</table>";
}

$html .= "</body></html>";

echo $html;


?>