@layout('master')

@section('content')
<h1>Vogels</h1>

{{ MediaObject::open_list() }}
@foreach ($vogels as $vogel)
	{{ MediaObject::create($vogel->soort->naam, $vogel->thumbnail_url())->with_h4(HTML::link_to_route("vogelDetail", $vogel->naam, array($vogel->id, $vogel->naam))) }}
@endforeach
{{ MediaObject::close_list() }}

{{ Button::link(URL::to_route("vogelNieuw"), "Nieuwe vogel toevoegen") }}

@endsection
