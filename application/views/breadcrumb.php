<?php

if (URI::current() != '/') {
	$urlParts = explode('/', URI::current());
	$crumbs = array('Home' => URL::to_route('home'));
	$url = "";
	$count = count($urlParts);
	for($i = 0; $i < $count; $i++) {
		if(is_numeric($urlParts[$i])) {
// 			unset($urlParts[$i]);
		}
	}
	$urlParts = array_merge($urlParts);
	for($i = 0; $i < count($urlParts); $i++) {
		$url .= (($url != '') ? "/" : "") . $urlParts[$i];
		if(!is_numeric($urlParts[$i])) {
			$title = Str::title(str_replace(array('_', '-'), ' ', $urlParts[$i]));
			$last = true;
			for($j = $i + 1; $j < count($urlParts); $j++) {
				if(!is_numeric($urlParts[$j])) {
					$last = false;
					break;
				}
			}
			if($last) {
				$crumbs[] = $title;
			} else {
				$crumbs[$title] = $url;
			}
		}
	}
	echo Breadcrumb::create($crumbs);
} else {
	echo Breadcrumb::create(array('Home' => URL::to_route('home')));
}
