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
            cache: true
        },
        minimumInputLength: 2,
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work,
        templateResult: formatUser, // omitted for brevity, see the source of this page
        templateSelection: formatUserSelection // omitted for brevity, see the source of this page
    });
    getMessages();

    $('#send-message-form').submit(function(e) {
        e.preventDefault();
        if ($('#message-input').val() != '' && $('#message-input').val())
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

    $('h1').click(function () {
        $(this).toggleClass('hidden');
        $('#set-title').toggleClass('hidden');
    });


    $(window).bind('beforeunload', function(){
        if (messages.length == 0) { $.post(base_url+'ajax/conversation/'+conversationId+'/destroy-if-empty', {_token: window.Laravel.csrfToken, _method:"DELETE"}); }
    });

    $('#set-title').submit(function (e) {
        e.preventDefault();
        setTitle();
    });

    $('body').keydown(function (e) {
        if (e.keyCode == 27 && $('h1').hasClass('hidden')) {
            $('#set-title, h1').toggleClass('hidden');
        }
    });
});

function getMessages() {
    $.getJSON('ajax/conversation/'+conversationId+'/get', function(data) {
        var lastId = $('#message-box .row:last-child').data('id');
        var $messageBox = $('#message-box');
        $messageBox.empty();

        $.each(data, function(i,v) {
            var own = (v.own) ? ' own' : '';
            console.log(own);
            $message = '<div class="row'+ own +'" data-id="'+v.id+'"><div class="col-md-3">'+
                '<p>'+v.sender.username+'</p></div>'+
                '<div class="col-md-9"><p class="text-right">'+v.content+'</p></div></div>';
            $messageBox.append($message);
        });

        if (lastId != $messageBox.find('.row:last-child').data('id')) {
            $curMsg = $messageBox.find('.row:last-child');
            $messageBox
                .scrollTop($curMsg.offset().top - $messageBox.innerHeight() + $messageBox.scrollTop() + $curMsg.innerHeight() );
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

function formatUser (user) {
    if (user.loading) return user.text;
    var img = (user.img != null) ? user.img : 'img/profile.png';

    var markup = '<div class="selectbox-result row blocklink-wrapper persist-cols">'+
        '<div class="col-md-12 blocklink user"><div class="row"><div class="col-md-4"><div class="profile-img">'+
        '<img src="'+img+'" alt="'+user.text+'"></div></div>'+
        '<div class="col-md-8"><p class="name">'+user.text+'</p></div></div></div></div>';

    return markup;
}

function formatUserSelection(user) {
    return user.text;
}

function setTitle() {
    $.post($('#set-title').attr('action'), $('#set-title').serialize(), function() {
        $('h1').text($('#set-title input[name="title"]').val()).toggleClass('hidden');
        $('#set-title').toggleClass('hidden');
    });
}