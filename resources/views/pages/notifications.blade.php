@extends('layouts.app')

@section('title', 'Home')
@section('content')
    <h2>Notifications</h2>
    <div id="feed">
        @forelse ($notifications as $notification)
        <div class="row blocklink-wrapper">
            <div class="col-md-12 post" data-notification-id="{{ $notification->id }}">
                <div class="content">
                    <div class="row">
                        <div class="col-md-8">{!! $notification->content !!}</div>
                        <div class="col-md-3"><div class="footer">{{ $notification->updated_at or $notification->created_at }}</div></div>
                        <div class="col-md-1"><a href="#" class="toggleSeen"><i class="fa fa-circle{{ ($notification->seen) ? '-o' : '' }}"></i></a></div>
                    </div>

                </div>
            </div>
        </div>
        @empty
            <p class="text-center">Nothing to report, captain!</p>
        @endforelse
    </div>
@stop