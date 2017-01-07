@extends('layouts.app')

@section('title', 'Dashboard')
@section('content')
	<h1>Newsfeed</h1>
	<div id="feed">
		@forelse($feed as $post)
			<div class="row blocklink-wrapper">
				<div class="col-md-12 post">
					<div class="header">{{ $post->organisation->name }}</div>
					<div class="content">
						{!! $post->content !!}
					</div>
					<div class="footer">{{ $post->updated_at }}</div>
				</div>
			</div>
		@empty
			<div class="row blocklink-wrapper">
				<div class="col-md-12">
					<p class="text-center">No posts yet.</p>
				</div>
			</div>
		@endforelse
	</div>
	@if (count($feed) > 0 && $canExpand)
		<div id="feed-ext"></div>
		<div class="row">
			<div class="col-md-12"><a href="" class="btn load-feed btn-load" data-href="{{ route('ajax.feed.extend') }}">Load more</a></div>
		</div>
	@endif
@stop
