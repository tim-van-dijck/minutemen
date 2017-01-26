@extends('layouts.app')

@section('title', 'Search results')
@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-12">
				<form class="search" action="search" method="GET">
					<button type="submit" class="pull-right btn btn-primary"><i class="fa fa-search"></i></button>
					<input class="pull-right" type="text" name="q" placeholder="Search Minutemen">
				</form>
				<h1>Results for "{{ $query }}"</h1>
				@if (!isset($results) || $results['organisations']->isEmpty() && $results['events']->isEmpty() && $results['teams']->isEmpty() && $results['users']->isEmpty())
					<div class="row">
						<div class="col-md-12">
							<p class="empty text-center">No results were found</p>
						</div>
					</div>
				@else
					<div id="search-results">
						@foreach ($results as $index => $list)
                            <?php $number = ($index != 'events') ? 6 : 3 ?>
							@if (count($list) > 0)
								<div class="row">
									<div class="col-md-12">
										<h2>{{ ucfirst($index) }}</h2>
										<div class="{{ $index }} {{ ($index != 'events') ? 'row  blocklink-wrapper persist-cols' : '' }}">
											@foreach ($list as $i => $item)
												@if ($index == 'events')
													<div class="row event">
														<div class="col-md-12">
															<div class="blocklink">
																<a href="{{ route('events.show', ['id' => $item->id]) }}">
																	<div class="row">
																		<div class="col-md-1">
																			<p class="month">{{ strtoupper(date('M', strtotime($item->starts_at))) }}</p>
																			<p class="day">{{ date('d', strtotime($item->starts_at)) }}</p>
																		</div>
																		<div class="col-md-6 banner-wrapper">
																			<div class="banner">
																				<img src="{{ $item->banner or 'img/event.png' }}" alt="{{ $item->title }}">
																			</div>
																		</div>
																		<div class="col-md-5 data">
																			<div class="row">
																				<div class="col-md-10 col-md-offset-2">
																					<h4>{{ $item->title }}</h4>
																				</div>
																			</div>
																			<div class="row">
																				<div class="col-md-2"><i class="fa fa-map-marker accent"></i></div>
																				<div class="col-md-10">
																					<p class="address">
																						{{ $item->street }} {{ $item->number }}<br>
																						{{ $item->zip }} {{ $item->city }}
																					</p>
																				</div>
																			</div>
																		</div>
																	</div>
																</a>
															</div>
														</div>
													</div>
												@else
													<div class="col-md-{{ 12/$number }} blocklink {{ substr($index,0,-1) }}">
														<?php
														$args = [];
														if ($index == 'organisations') { $args['id'] = $item->id; }
														else { $args['slug'] = $item->slug; }
														?>
														<a href="{{ route($index.'.show', $args) }}">
															<div class="profile-img">
																@if ($index == 'users')
																	<img src="{{ $item->img or 'img/profile.png' }}" alt="{{ $item->username }}">
																@elseif ($index == 'teams')
																	<img src="{{ $item->img or 'img/emblem.png' }}" alt="{{ $item->username }}">
																@elseif ($index == 'organisations')
																	<img src="{{ $item->thumb or 'img/organisation.png' }}" alt="{{ $item->name }}">
																@endif
															</div>
															@if ($index == 'users')
																<p class="name">{{ $item->username }}</p>
															@elseif ($index == 'teams' || $index == 'organisations')
																<p class="name">{{ $item->name }}</p>
															@endif
														</a>
													</div>

													@if ($i != 0 && ($i+1) % $number == 0)
														</div><div class="row blocklink-wrapper">
													@endif
												@endif
											@endforeach
										</div>
									</div>
								</div>
							@endif
						@endforeach
					</div>
				@endif
			</div>
		</div>
	</div>
@stop