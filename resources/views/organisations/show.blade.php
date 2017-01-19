@extends('layouts.app')

@section('title', $organisation->name)
@section('content')
	<!-- <div class="banner"><img src="{{ $organisation->banner }}" alt="{{ $organisation->name}} banner"></div> -->
	@if (Auth::check())
		@if($organisation->isAdmin(Auth::user()->id))
			<a href="{{ route('organisations.edit', ['id' => $organisation->id]) }}" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> edit</a>
			<a href="{{ route('events.create', ['id' => $organisation->id]) }}" class="btn btn-primary"><i class="fa fa-calendar"></i> create event</a>
		@elseif ($organisation->subscribed())
            @if (count($organisation->subscribers()) > 0)
                <button type="button" data-toggle="modal" data-target="#subscribers" class="btn btn-primary">Subscribers ({{count($organisation->subscribers())}})</button>
            @endif
            <a id="sub" href="{{ route('ajax.unsub', ['organisation_id' => $organisation->id]) }}" class="btn btn-primary pull-right" data-href="{{ route('ajax.sub', ['organisation_id' => $organisation->id]) }}">Unsubscribe</a>
		@else
            @if (count($organisation->subscribers()) > 0)
                <button type="button" data-toggle="modal" data-target="#subscribers" class="btn btn-primary">Subscribers ({{count($organisation->subscribers())}})</button>
            @endif
            <a id="sub" href="{{ route('ajax.sub', ['organisation_id' => $organisation->id]) }}" class="btn btn-primary pull-right" data-href="{{ route('ajax.unsub', ['organisation_id' => $organisation->id]) }}">Subscribe</a>
		@endif
	@endif
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12 profile-wrapper">
                <h2 class="profile-title">
                    @if ($organisation->trusted)
                        <img id="trusted" src="img/trusted.svg" alt="Trusted Organisation" title="Trusted Organisation">
                    @endif
                    {{ $organisation->name }}
                </h2>
                <h5 class="text-center">{{ count($organisation->subscribers()) }} subscribers</h5>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="profile-img profile">
                            <img src="{{ $organisation->thumb or 'img/organisation.png' }}" alt="{{ $organisation->name }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="text-center">About {{ $organisation->name }}</h4>
                        <div class="description">{!! $organisation->description !!}</div>
                    </div>
                </div>
                @if (!$organisation->events()->isEmpty())
                    <div class="row divider">
                        <div class="col-md-12">
                            <h3>Events</h3>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        @foreach($organisation->events(4) as $index => $event)
                                            <div class="col-md-6 {{ ($index % 2 == 0 && $index == count($organisation->events(4))-1) ? 'col-md-offset-3' : '' }}">
                                                <div class="blocklink">
                                                    <a href="{{ route('events.show', ['id' => $event->id]) }}">
                                                        <div class="banner"><img src="{{ $event->banner or 'img/event.png' }}" alt="{{ $event->title }}"></div>
                                                        <h5 class="text-center">{{ $event->title }}</h5>
                                                        <p class="period text-center">{{ date('F dS \a\t H:i', strtotime($event->starts_at)) }}</p>
                                                    </a>
                                                </div>
                                            </div>
                                            @if ($index == 1)
                                                </div><div class="row blocklink-wrapper">
                                            @endif
                                        @endforeach
                                    </div>
                                    <a href="" class="btn btn-load">more events</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row {{ ($organisation->events()->isEmpty()) ? 'divider' : '' }}">
                    <div class="col-md-12">
                        <h3>News</h3>
                        @if(Auth::check() && $organisation->isAdmin(Auth::user()->id))
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="post-form" action="{{ route('ajax.organisations.post', ['id' => $organisation->id]) }}" method="POST">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-primary">Post</button>
                                        <textarea name="post" class="form-control" required></textarea>
                                    </form>
                                </div>
                            </div>
                        @endif
                        <div id="feed" data-organisation="{{ $organisation->id }}">
                            @forelse($organisation->posts() as $post)
                                <div class="col-md-12 post">
                                    <div class="header">{{ $organisation->name or 'This Organisation' }}</div>
                                    <div class="content">
                                        {!! $post->content !!}
                                    </div>
                                    <div class="footer">{{ $post->updated_at or $post->created_at }}</div>
                                </div>
                            @empty
                                <div class="col-md-12">
                                    <p class="text-center">No posts yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @include('modals.subscribers')
            </div>
        </div>
    </div>
@stop
@section('js')
	<script src="js/forms.js"></script>
@stop