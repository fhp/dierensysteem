<?php

function isAdmin()
{
	return Auth::check() && Auth::user()->admin;
}

function fcGast()
{
	return Auth::guest() && (Request::ip() == "88.159.83.200");
}

?>