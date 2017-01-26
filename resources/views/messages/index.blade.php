@extends('layouts.app')

@section('title', 'Conversations')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
                <i class="fa fa-2x fa-comments menu-icons"></i>
                <a href="{{ route('conversations.create') }}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> new message</a>
                <h1>Conversations</h1>
                <div id="conversation-list">
                    @forelse ($conversations as $conversation)
                        <div class="row blocklink-wrapper">
                            <div class="col-md-12 post" data-notification-id="{{ $conversation->id }}">
                                <a href="{{ route('conversations.show', ['id' => $conversation->id]) }}">
                                    <div class="content">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3>
                                                    @if ($conversation->title !== null)
                                                        {{ $conversation->title }}
                                                    @elseif($conversation->alt_title != '')
                                                        {{ $conversation->alt_title }}
                                                    @else
                                                        {{ '<No Recipients>' }}
                                                    @endif
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-11">{{ $conversation->latestMessage()->content or 'No messages yet' }}</div>
                                            <div class="col-md-1">
                                                <a href="#" class="toggleSeen">
                                                    <i class="fa fa-circle{-- ($conversation->seen) ? '-o' : '' --}"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-center">You have no conversations at this time.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@stop