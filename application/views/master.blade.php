<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Falconcrest Roofvogel Administratie Systeem</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
{{ HTML::style('laravel/css/style.css') }}
{{ HTML::style('css/style.css') }}
{{ Asset::container('bootstrapper')->styles() }}
{{ Asset::container('bootstrapper')->scripts() }}
{{ Asset::container('ckeditor')->scripts() }}
</head>
<body>
	
	<div style="height: 60px;">
		<h1 style="color: #800000;"><a href="index.php"><img src="{{ asset("img/Falconcrest_Logo.jpg") }}"></a> Falconcrest Roofvogel Administratie Systeem</h1>
	</div>
	@include('breadcrumb')
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span2">
				@if(Auth::check())
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
