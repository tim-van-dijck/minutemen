@extends('layouts.app')

@section('title', $event->title)
@section('content')
	<div class="banner"><img src="{{ $event->banner or 'img/event.png' }}" alt="{{ $event->title }}"></div>
	<h1>{{ $event->title }}</h1>
	<div class="row">
		<div class="col-md-12 leaderboard">
            @foreach ($leaderboard as $index => $team)
                @if ($index == 1 || $index == 2)
                    @if ($index == 1)
                        <div class="row">
                    @endif
                    <div class="col-md-6">
                @endif
                <div id="{{ $team->slug }}" class="{{ ($index < 3) ? 'place-'.($index+1) : '' }} row leaderboard-team {{ ($team->isMember()) ? 'my-team' : '' }}">
                    @if($index < 3)
                        <div class="col-md-{{ ($index == 1 || $index == 2) ? '2' : '1' }}"><i class="fa fa-trophy fa-2x"></i></div>
                    @else
                        <div class="col-md-1"><span>{{ $index+1 }}</span></div>
                    @endif
                    <div class="col-md-1{{ ($index == 1 || $index == 2) ? '0' : '1' }}">
                        <div class="row">
                            <div class="col-md-{{ ($index == 1 || $index == 2) ? '4' : '2' }}">
                                <div class="profile-img">
                                    <img src="{{ $team->emblem or 'img/emblem.png' }}">
                                </div>
                            </div>
                            <div class="col-md-{{ ($index == 1 || $index == 2) ? '8' : '10' }} f-h">
                                @if ($index == 1 || $index == 2)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <span class="title" title="{{ $team->name }}">{{ $team->name }}</span>
                                        </div>
                                    </div>
                                    <div class="row score">
                                            <div class="col-md-12">
                                                <span class=""><i class="fa fa-trophy"></i> {{ $team->wins }}</span>
                                                <span class=""><i class="fa fa-pause fa-rotate-90"></i> {{ $team->draws }}</span>
                                                <span class=""><i class="fa fa-crosshairs"></i> {{ $team->losses }}</span>
                                            </div>
                                    </div>
                                @else
                                    <div class="row f-h">
                                        <div class="col-md-8">
                                            <span class="title" title="{{ $team->name }}">{{ $team->name }}</span>
                                        </div>
                                        <div class="col-md-4 score f-h">
                                            <div class="a-w">
                                                <span class=""><i class="fa fa-trophy"></i> {{ $team->wins }}</span>
                                                <span class=""><i class="fa fa-pause fa-rotate-90"></i> {{ $team->draws }}</span>
                                                <span class=""><i class="fa fa-crosshairs"></i> {{ $team->losses }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if ($index == 1 || $index == 2)
                    </div>
                    @if ($index == 2)
                        </div>
                    @endif
                @endif
            @endforeach
		</div>
	</div>
@stop

@section('js')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
	<script src="js/forms.js"></script>
@stop