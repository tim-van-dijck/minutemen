@extends('layouts.app')

@section('title', $organisation->name)
@section('content')
	<!-- <div class="banner"><img src="{{ $organisation->banner }}" alt="{{ $organisation->name}} banner"></div> -->
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
                @if (Auth::check())
                    @if($organisation->isAdmin(Auth::user()->id))
                        <i class="fa fa-2x fa-unlock-alt menu-icons" title="You can manage this page"></i>
                        <a href="{{ route('organisations.edit', ['id' => $organisation->id]) }}" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> edit</a>
                    @elseif ($organisation->subscribed())
                        <a id="sub" href="{{ route('ajax.unsub', ['organisation_id' => $organisation->id]) }}" class="btn btn-primary pull-right" data-href="{{ route('ajax.sub', ['organisation_id' => $organisation->id]) }}">Unsubscribe</a>
                    @else
                        @if (count($organisation->subscribers()) > 0)
                            <button type="button" data-toggle="modal" data-target="#subscribers" class="btn btn-primary">Subscribers ({{count($organisation->subscribers())}})</button>
                        @endif
                        <a id="sub" href="{{ route('ajax.sub', ['organisation_id' => $organisation->id]) }}" class="btn btn-primary pull-right" data-href="{{ route('ajax.unsub', ['organisation_id' => $organisation->id]) }}">Subscribe</a>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12 profile-wrapper">
                <h2 class="profile-title">
                    @if ($organisation->trusted)
                        <img id="trusted" src="img/trusted.svg" alt="Trusted Organisation" title="Trusted Organisation">
                    @endif
                    {{ $organisation->name }}
                </h2>
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="profile-img profile">
                            <img src="{{ $organisation->thumb or 'img/organisation.png' }}" alt="{{ $organisation->name }}">
                            @if (count($organisation->subscribers()) > 0)
                                <button type="button" data-toggle="modal" data-target="#subscribers" class="btn btn-primary">Subscribers ({{count($organisation->subscribers())}})</button>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h4 class="text-center">About {{ $organisation->name }}</h4>
                        <div class="description">{!! $organisation->description !!}</div>
                        @if (isset($organisation->website) && $organisation->website != '')
                            <div class="row persist-cols">
                                <div class="col-md-1">
                                    <i class="fa fa-globe accent"></i>
                                </div>
                                <div class="col-md-11">
                                    <a class="accent website" href="{{ $organisation->website }}">{{ $organisation->website }}</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @if ($organisation->isAdmin())
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <a href="{{ route('events.create', ['id' => $organisation->id]) }}" class="btn btn-primary full-width"><i class="fa fa-calendar"></i> create event</a>
                        </div>
                    </div>
                @endif
                @if (!$organisation->events()->isEmpty())
                    <div class="row divider">
                        <div class="col-md-12">
                            <h3>Upcoming events</h3>
                            <div class="row events">
                                <div class="col-md-12 events">
                                    @foreach($organisation->events(4) as $index => $event)
                                        <div class="row event">
                                            <div class="col-md-12">
                                                <div class="blocklink">
                                                    <a href="{{ route('events.show', ['id' => $event->id]) }}">
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <p class="month">{{ strtoupper(date('M', strtotime($event->starts_at))) }}</p>
                                                                <p class="day">{{ date('d', strtotime($event->starts_at)) }}</p>
                                                            </div>
                                                            <div class="col-md-6 banner-wrapper">
                                                                <div class="banner">
                                                                    <img src="{{ $event->banner or 'img/event.png' }}" alt="{{ $event->title }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5 data">
                                                                <div class="row">
                                                                    <div class="col-md-10 col-md-offset-2">
                                                                        <h4>{{ $event->title }}</h4>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-2"><i class="fa fa-map-marker accent"></i></div>
                                                                    <div class="col-md-10">
                                                                        <p class="address">
                                                                            {{ $event->street }} {{ $event->number }}<br>
                                                                            {{ $event->zip }} {{ $event->city }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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
                                        <textarea name="post" class="form-control" placeholder="Write a post here. Press Enter or 'Post' to submit" required></textarea>
                                    </form>
                                </div>
                            </div>
                        @endif
                        <div id="feed" data-organisation="{{ $organisation->id }}">
                            @forelse($organisation->posts(10) as $post)
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
                        <div id="feed-ext"></div>
                        <a href="{{ route('ajax.feed.extend', ['id' => $organisation->id]) }}" class="load-feed btn btn-load">Load more</a>
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