@extends('layouts.app')

@section('title', $conversation->title)
@section('content')
    <h1>{{ $conversation->title }}</h1>
        <div class="row blocklink-wrapper">
            @foreach ($conversation->recipients() as $recipient)
                <div class="col-md-1 blocklink" title="{{ $recipient->username }}">
                    <div class="profile-img"><img src="{{ $recipient->img or 'img/profile.png' }}" alt="{{ $recipient->username }}"></div>
                </div>
            @endforeach
                <div class="col-md-1 blocklink"><a href="" class="btn btn-primary"><i class="fa fa-plus"></i></a></div>
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
@stop

@section('js')
    <script type="text/javascript">
        var conversationId = {{ $conversation->id }}

        $(function() {
            getMessages();
            $('#send-message-form').submit(function(e) {
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serialize(), function () {
                    $('#message-input').val('');
                    getMessages();
                });
            });

            setInterval(function () { getMessages() }, 3000);
        });

        function getMessages() {
            $.getJSON('ajax/conversation/'+conversationId+'/get', function(data) {
                var lastId = $('#message-box .row:last-child').data('id');
                $('#message-box').empty();

                $.each(data, function(i,v) {
                    $message = '<div class="row" data-id="'+v.id+'"><div class="col-md-3">'+
                        '<p>'+v.sender.username+'</p></div>'+
                        '<div class="col-md-9"><p class="text-right">'+v.content+'</p></div></div>';
                    $('#message-box').append($message);
                });

                if (lastId != $('#message-box .row:last-child').data('id')) {
                    $curMsg = $('#message-box .row:last-child');
                    $('#message-box')
                            .scrollTop($curMsg.offset().top - $('#message-box').innerHeight() + $('#message-box').scrollTop() + $curMsg.innerHeight() );
                }
            });
        }
    </script>
@stop