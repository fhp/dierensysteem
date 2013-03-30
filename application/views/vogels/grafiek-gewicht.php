<?php

$width = Input::get("width", 750);
$height = Input::get("height", 300);

/* Create and populate the pData object */
$MyData = new pData();
$gewichten = array();
$datums = array();
$start = new DateTime("last month");
$day = new DateInterval("P1D");
foreach($vogel->gewichten()->where("datum", ">", $start)->order_by("datum")->get() as $gewicht) {
	$datum = new DateTime($gewicht->datum);
	$start->add($day);
	while($datum >= $start) {
		$gewichten[] = VOID;
		$datums[] = $start->getTimestamp();
		$start->add($day);
	}
	$gewichten[] = $gewicht->gewicht;
	$datums[] = $start->getTimestamp();
}
$MyData->addPoints($gewichten, "Gewicht");
$MyData->addPoints($datums, "Datum");

$MyData->setPalette("Gewicht", array("R" => 0,"G" => 136, "B" => 204, "Alpha" => 100));

$MyData->setAbscissa("Datum");

$MyData->setXAxisName("Datum");
$MyData->setXAxisDisplay(AXIS_FORMAT_DATE,"d-m");

$MyData->setAxisName("Gewicht", 0);
$MyData->setAxisUnit("gram", 0);
$MyData->setAxisDisplay("gram", 0);


/* Create the pChart object */
$myPicture = new pImage($width, $height, $MyData);

/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>pfont("pf_arma_five.ttf"),"FontSize"=>6,"R"=>0,"G"=>0,"B"=>0));

/* Define the chart area */
$myPicture->setGraphArea(30, 0, $width - 10, $height - 20);

/* Draw the scale */
$scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>false,"CycleBackground"=>true, "LabelingMethod"=>LABELING_DIFFERENT);
$myPicture->drawScale($scaleSettings);

/* Draw the line chart */
$myPicture->drawSplineChart(array("BreakVoid"=>FALSE, "BreakR"=>234, "BreakG"=>55, "BreakB"=>26));
$myPicture->drawPlotChart(array("DisplayValues"=>TRUE));

/* Render the picture (choose the best way) */
$myPicture->Stroke();

?>