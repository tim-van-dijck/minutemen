@extends('layouts.app')

@section('content')
	@if (Auth::check())
		<div class="pull-right"><a class="btn btn-primary" href="{{ route('organisations.create') }}"><i class="fa fa-plus"></i> new organisation</a></div>
	@endif

	<h1>Organisation</h1>
	<div class="row blocklink-wrapper">
		@forelse($organisations as $i => $organisation)
			<div class="col-md-2 blocklink">
				<a href="{{ route('organisations.show', ['id' => $organisation->id]) }}">
					<div class="profile-img"><img src="{{ $organisation->thumb or 'img/organisation.png' }}" alt="{{ $organisation->name }}"></div>
					<p>{{$organisation->name}}</p>
				</a>
			</div>
			@if ($i != 0 && $i % 6 == 0)
				</div><div class="row">
			@endif
		@empty
			<p>There are no organisations yet.</p>
		@endforelse
	</div>
@stop
