var messages = [];
$(function() {
    $('#message-input').keydown(function(e) {
        if (e.keyCode == 13 && $(this).value != "" && !e.shiftKey) {
            $(this).closest('form').submit();
        }
    }).focus();

    $("#user-find").select2({
        placeholder: 'Find and invite friends/team-mates',
        ajax: {
            url: 'ajax/me/find-recipients/'+conversationId,
            dataType: 'json',
            delay: 250,
            minimumInputLength: 2,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, params) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;

                return {
                    results: data,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work,
            templateResult: formatUser, // omitted for brevity, see the source of this page
            templateSelection: formatUserSelection, // omitted for brevity, see the source of this page
            cache: true
        }
    });
    getMessages();

    $('#send-message-form').submit(function(e) {
        e.preventDefault();
        $.post($(this).attr('action'), $(this).serialize(), function () {
            $('#message-input').val('');
            getMessages();
        });
    });
    setInterval(function () { getMessages() }, 3000);

    $('#add-recipients-form').submit(function (e) {
        e.preventDefault();
        $.post($(this).attr('action'), $(this).serialize());
        $('#add-recipient').modal('toggle');
    });


    $(window).bind('beforeunload', function(){
        if (messages.length == 0) { $.post(base_url+'ajax/conversation/'+conversationId+'/destroy-if-empty', {_token: window.Laravel.csrfToken, _method:"DELETE"}); }
    });
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

function formatUser (user) {
    if (user.loading) return user.text;
    var img = (user.img != null) ? user.img : 'img/profile.png';

    var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-img'><img src='" + img + "' /></div>" +
        "<div class='select2-result-meta'>" +
        "<div class='select2-result-title'>" + user.text + "</div>"+
        "</div></div>";

    return markup;
}

function formatUserSelection(user) {
    return "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-img'><img src='" + img + "' /></div>" +
        "<div class='select2-result-meta'>" +
        "<div class='select2-result-title'>" + user.text + "</div>"+
        "</div></div>";
}