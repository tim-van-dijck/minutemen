@extends('layouts.app')

@section('title', 'Messages')
@section('content')
    <a href="{{ route('messages.create') }}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> new message</a>
    <h1>Messages</h1>
    <div id="conversation-list">
        @forelse ($conversations as $conversation)
            <div class="row blocklink-wrapper">
                <div class="col-md-12 post" data-notification-id="{{ $conversation->id }}">
                    <div class="content">
                        <div class="row">

                        </div>
                    </div>
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
@stop