@extends('layouts.app')

@section('title', (isset($conversation->title)) ?$conversation->title : 'Conversation')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form class="delete" data-confirm="{{ (count($conversation->recipients()) > 1) ? 'leave' : 'cancel' }} this conversation" action="{{ (count($conversation->recipients()) > 1) ? route('conversations.leave', ['id' => $conversation->id]) : route('conversations.destroy', ['id' => $conversation->id]) }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-primary btn-small pull-right">
                    {{ (count($conversation->recipients()) > 1) ? 'Leave' : 'Cancel' }} conversation
                </button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
                <h1>
                    @if ($conversation->title !== null)
                        {{ $conversation->title }}
                    @elseif($conversation->alt_title != '')
                        {{ $conversation->alt_title }}
                    @else
                        {{  'No Recipients' }}
                    @endif
                </h1>
                <div class="row blocklink-wrapper recipients">
                    @foreach ($conversation->recipients() as $recipient)
                        <div class="col-md-1 blocklink" title="{{ $recipient->username }}">
                            <div class="profile-img"><img src="{{ $recipient->img or 'img/profile.png' }}" alt="{{ $recipient->username }}"></div>
                        </div>
                    @endforeach
                    <div class="col-md-1 blocklink"><a href="" class="btn btn-primary" data-toggle="modal" data-target="#add-recipient"><i class="fa fa-plus"></i></a></div>
                </div>
                <div id="message-box">
                    <div class="row">
                        <div class="col-md-12 text-center"><i class="fa fa-circle-o-notch fa-spin"></i></div>
                    </div>
                </div>
                <div class="send-message">
                    <form id="send-message-form" action="{{ route('ajax.message.send', ['conversation_id' => $conversation->id]) }}">
                        {{ csrf_field() }}
                        <textarea class="form-control" name="message" id="message-input" placeholder="Press 'Send' or Enter to send your message"></textarea>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('modals.add-recipient')
@stop

@section('js')
    <script type="text/javascript">
        var conversationId = {{ $conversation->id }}
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="js/messages.js"></script>
    <script src="js/delete-confirm.js"></script>
@stop