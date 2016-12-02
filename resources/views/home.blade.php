@extends('layouts.app')

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">Dashboard</div>

		<div class="panel-body">
			<div id="feed">
				@forelse($feed as $post)
					<div class="col-md-12 post">
						<div class="header">{{ $post->organisation->name or 'This Organisation' }}</div>
						<div class="content">
							{!! $post->content !!}
						</div>
						<div class="footer">{{ $post->updated_at }}</div>
					</div>
				@empty
					<div class="col-md-12">
						<p class="text-center">No posts yet.</p>
					</div>
				@endforelse
			</div>
		</div>
	</div>
@stop
