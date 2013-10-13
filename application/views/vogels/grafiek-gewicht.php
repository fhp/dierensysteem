<?php

header("Content-Type: image/png");

$width = Input::get("width", 750);
$height = Input::get("height", 300);

$gemiddelde = Input::get("gemiddelde", 0);

/* Create and populate the pData object */
$MyData = new pData();
$gewichten = array();
$datums = array();
$start = new DateTime(Input::get("start", "last month"));
$einde = new DateTime(Input::get("einde", "today"));
$day = new DateInterval("P1D");
$first = true;
$runningAverage = array();
$runningAverageTemp = array();
foreach($vogel->gewichten()->where("datum", ">", $start)->where("datum", "<", $einde)->order_by("datum")->get() as $gewicht) {
	$datum = new DateTime($gewicht->datum);
	$start->add($day);
	while($datum >= $start) {
		if(!$first) {
			$gewichten[] = VOID;
			if($gemiddelde > 0) {
				$runningAverage[] = $avg;
			}
			$datums[] = $start->getTimestamp();
		}
		$start->add($day);
	}
	$first = false;
	$gewichten[] = $gewicht->gewicht;
	$datums[] = $start->getTimestamp();
	
	if($gemiddelde > 0) {
		if(count($runningAverageTemp) > $gemiddelde) {
			array_shift($runningAverageTemp);
		}
		$runningAverageTemp[] = $gewicht->gewicht;
		$sum = 0;
		foreach($runningAverageTemp as $x) {
			$sum += $x;
		}
		$avg = $sum / count($runningAverageTemp);
		$runningAverage[] = $avg;
	}
}

if($gemiddelde > 0) {
	$MyData->addPoints($runningAverage, "Gemiddelde");
} else {
	$MyData->addPoints($gewichten, "Gewicht");
}

$datumCount = count($datums);
if($datumCount > floor($width/25)) {
	$i = 0;
	$nieuweDatums = array();
	foreach($datums as $datum) {
		if($i % floor($datumCount / floor($width/25)) == 0) {
			$nieuweDatums[] = $datum;
		} else {
			$nieuweDatums[] = VOID;
		}
		$i++;
	}
	$datums = $nieuweDatums;
}
$MyData->addPoints($datums, "Datum");

$MyData->setPalette("Gewicht", array("R" => 0,"G" => 136, "B" => 204, "Alpha" => 100));
$MyData->setPalette("Gemiddelde", array("R" => 255,"G" => 0, "B" => 0, "Alpha" => 100));

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
$myPicture->setGraphArea(30, 0, $width - 10, $height - 40);

/* Draw the scale */
$scaleSettings = array("XMargin"=>10, "YMargin"=>10, "Floating"=>false, "CycleBackground"=>true, "LabelingMethod"=>LABELING_DIFFERENT, "LabelRotation"=>90);
$myPicture->drawScale($scaleSettings);

/* Draw the line chart */
$myPicture->drawSplineChart(array("BreakVoid"=>FALSE, "BreakR"=>234, "BreakG"=>55, "BreakB"=>26));
$options = array();
if($datumCount < floor($width/15) || $height > 750) {
	$options["DisplayValues"] = TRUE;
	$myPicture->drawPlotChart($options);
}

/* Render the picture (choose the best way) */

$myPicture->Stroke();
die();

?>