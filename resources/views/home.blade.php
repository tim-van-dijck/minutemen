@extends('layouts.app')

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">Dashboard</div>

		<div class="panel-body">
			@forelse($feed as $post)
				<div class="row">
					<div class="col-md-12">
						{{ $post->content }}
					</div>
				</div>
			@empty
				<div class="row">
					<div class="col-md-12">
						No news could be gathered for you at this time.
					</div>
				</div>
			@endforelse
		</div>
	</div>
@stop
