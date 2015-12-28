<?php

define("IP_FALCONCREST", "88.159.83.200");

function isAdmin()
{
	return Auth::check() && Auth::user()->admin;
}

function fcGast()
{
	return Auth::guest() && (Request::ip() == IP_FALCONCREST);
}

function formatDate($datum, $format)
{
	$x = new DateTime($datum);
	return $x->format($format);
}

function formatDatum($datum)
{
	return formatDate($datum, "d-m-Y");
}

function formatTijd($datum)
{
	return formatDate($datum, "H:i");
}

function vogelLinks($tekst)
{
	static $search = null;;
	static $replace = null;
	if($search === null || $replace === null) {
		$search = array();
		$replace = array();
		foreach(Vogel::order_by(DB::raw("length(naam)"), "DESC")->get() as $vogel) {
			$search[] = " {$vogel->naam} ";
			$replace[] = ' <a class="vogellink" href="' . URL::to_route("vogelDetail", array($vogel->id, $vogel->naam)) . '">' . $vogel->naam . '</a> ';
		}
	}
	return str_ireplace($search, $replace, $tekst);
}

?>