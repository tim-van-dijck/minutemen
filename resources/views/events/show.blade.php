@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h2>{{ $event->namename }}</h2>
			<div class="profile-img"><img src="{{ $event->thumb or 'img/profile.png' }}" alt="{{ $event->username }}"></div>
			<div class="stats row">
				<div class="col-md-4">
					<p>{{ $event->starts_at }} - $event->ends_at</p>
					<p>at {{ $event->address }}</p>
				</div>
			</div>
		</div>
	</div>
</div>
@stop