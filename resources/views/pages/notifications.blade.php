@extends('layouts.app')

@section('title', 'Notifications')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
                <i class="fa fa-2x fa-bell menu-icons"></i>
                <h2>Notifications</h2>
                <div id="notifications">
                    @forelse ($notifications as $notification)
                        <div class="row blocklink-wrapper">
                            <div class="col-md-12 post" data-notification-id="{{ $notification->id }}">
                                <div class="content">
                                    <div class="row">
                                        <div class="col-md-8">{!! $notification->content !!}</div>
                                        @if ($notification->entity_name == 'lobby-invite')
                                            <div class="col-md-3">
                                                <div class="invite pull-right">
                                                    <div class="col-md-6"><a href="{{ route('lobby.accept-invite', ['lobby_id' => $notification->entity_id, 'notification_id' => $notification->id]) }}">accept</a></div>
                                                    <div class="col-md-6"><a href="{{ route('lobby.deny-invite', ['lobby_id' => $notification->entity_id, 'notification_id' => $notification->id]) }}">deny</a></div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-3"><div class="footer"><div class="accept-deny"></div></div></div>
                                        @endif
                                        <div class="col-md-1"><a href="#" class="toggleSeen"><i class="fa fa-circle{{ ($notification->seen) ? '-o' : '' }}"></i></a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center">Nothing to report, captain!</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@stop