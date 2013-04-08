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


?>