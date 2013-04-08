<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Falconcrest Roofvogel Administratie Systeem</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
{{ HTML::style('laravel/css/style.css') }}
{{ HTML::style('css/style.css') }}
{{ HTML::style('css/jquery-ui-1.10.1.min.css') }}
{{ Asset::container('bootstrapper')->styles() }}

{{ Asset::container('bootstrapper')->scripts() }}
{{ Asset::container('ckeditor')->scripts() }}
{{ HTML::script('js/jquery-ui-1.10.1.min.js') }}
{{ HTML::script('bundles/jquery-validator/validator.js') }}
{{ HTML::script('js/datepicker.js') }}
{{ HTML::script('js/validator.js') }}
{{ HTML::script('js/scripts.js') }}
</head>
<body>
	
	<div id="header">
		<a href="{{ URL::to_route("home") }}"><h1 class="pull-left"><img src="{{ asset("img/Falconcrest_Logo.jpg") }}"> Falconcrest Roofvogel Administratie Systeem</h1></a>
		@if(Auth::check())
			<span class="pull-right hidden-phone"><a href="{{ URL::to_route("gebruikerDetail", array(Auth::user()->id, Auth::user()->gebruikersnaam)) }}">{{ Auth::user()->thumbnail_image() }}</a></span>
		@endif
	</div>
	<div class="hidden-phone">
	@include('breadcrumb')
	</div>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span2 {{ Request::route()->is('home') ? '' : 'hidden-phone' }}">
				@if(Auth::check() || fcGast())
					@include('menu')
				@endif
			</div>
			<div class="span10">
				@yield('content')
			</div>
		</div>
	</div>
</body>
</html>
