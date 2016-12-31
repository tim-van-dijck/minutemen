@extends('layouts.app')

@section('title', 'Search results')
@section('content')
	<h1>Results for "{{ $query }}"</h1>
	@if (!isset($results) || $results['organisations']->isEmpty() && $results['events']->isEmpty() && $results['teams']->isEmpty() && $results['users']->isEmpty())
		<div class="row">
			<div class="col-md-12">
				<p class="empty text-center">No results were found</p>
			</div>
		</div>
	@else
		@foreach ($results as $index => $list)
            <?php $number = ($index != 'events') ? 6 : 3 ?>
			<div class="row blocklink-wrapper {{ $index }}">
				<div class="col-md-12">
					<h2>{{ ucfirst($index) }}</h2>
					<div class="row blocklink-wrapper">
						@foreach ($list as $i => $item)
							<div class="col-md-{{ 12/$number }} blocklink {{ substr($index,0,-1) }}">
								<?php
									$args = [];
									if ($index == 'organisations' || $index == 'events') { $args['id'] = $item->id; }
									else { $args['slug'] = $item->slug; }
								?>
								<a href="{{ route($index.'.show', $args) }}">
									@if ($index != 'events')
										<div class="profile-img">
											@if ($index == 'users')
												<img src="{{ $item->img or 'img/profile.png' }}" alt="{{ $item->username }}">
											@elseif ($index == 'teams')
												<img src="{{ $item->img or 'img/emblem.png' }}" alt="{{ $item->username }}">
											@elseif ($index == 'organisations')
												<img src="{{ $item->thumb or 'img/organisation.png' }}" alt="{{ $item->name }}">
											@endif
										</div>
									@else
										<div class="banner"><img src="{{ $item->banner or 'img/event.png' }}" alt="{{ $item->title }}"></div>
									@endif
									@if ($index == 'users')
										<p>{{ $item->username }}</p>
									@elseif ($index == 'teams' || $index == 'organisations')
										<p>{{ $item->name }}</p>
									@elseif ($index == 'events')
										<h4 class="text-center">{{ $item->title }}</h4>
										<p class="period text-center">{{ $item->starts_at }}</p>
									@endif
								</a>
							</div>

							@if ($i != 0 && ($i+1) % $number == 0)
								</div><div class="row blocklink-wrapper">
							@endif
						@endforeach
					</div>
				</div>
			</div>
		@endforeach
	@endif
@stop