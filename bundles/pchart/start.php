<?php

function pFont($fontname)
{
	return Bundle::path('pchart') . "fonts/" . $fontname;
}

Autoloader::directories(array(
	Bundle::path('pchart').'classes'
));

?>