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
?>