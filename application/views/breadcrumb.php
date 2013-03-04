<?php

if (URI::current() != '/') {
	$urlParts = explode('/', URI::current());
	$crumbs = array('Home' => URL::to_route('home'));
	$url = "";
	for($i = 0; $i < count($urlParts); $i++) {
		if(is_numeric($urlParts[$i])) {
			continue;
		}
		$url .= (($url != '') ? "/" : "") . $urlParts[$i];
		$title = Str::title(str_replace(array('_', '-'), ' ', $urlParts[$i]));
		if($i == count($urlParts) - 1) {
			$crumbs[] = $title;
		} else {
			$crumbs[$title] = ($url);
		}
	}
	echo Breadcrumb::create($crumbs);
} else {
	echo Breadcrumb::create(array('Home' => URL::to_route('home')));
}
