var timer;
var offset = 0;
$( function() {

    $("#user-find").select2({
        placeholder: 'Find and invite users',
        ajax: {
            url: 'ajax/users/find/' + teamId,
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

    $('.kick').click(function() {
        $('#member_id').val($(this).data('id'));
        $('#kick-form input[type="password"]').focus();
    });

    $('#kick-form').submit(function(e) {
        e.preventDefault();
        var id = $(this).find('#member_id').val();

        $.post($(this).attr('action'), $(this).serialize(), function(response) {
            $('#kick').modal('toggle');
            $(this).find('input[type="password"]').val('');
            console.log(response);
            if (Object.prototype.hasOwnProperty.call(response, 'success')) {
                $('a.kick[data-id="'+id+'"]').closest('.user').remove();
            }
        }, 'json');
    });

    $('a.ajax-button').click(function (e) {
        e.preventDefault();
        $el = $(this);

        $.get($(this).attr('href'), function() {
            if ($el.hasClass('admin-delete')) {
                deleteAdmin($el.closest('.user'));
                $el.text('Make admin');
            }
            else if($el.hasClass('admin-add')) {
                addAdmin($el.closest('.user'));
                $el.text('Delete admin');
            }

            $el
                .toggleClass('admin-add')
                .toggleClass('admin-delete')
                .attr('href', $el.data('href'));
        });
    });

    $('#users-find-form').submit(function (e) {
        e.preventDefault();

        $.post($(this).attr('action'), $(this).serialize(), function () {
            location.reload();
        });
    });

});

function getInvited() {

}

function addAdmin($el) {
    var $row;

    if ($('.admins .row:last-child .user').length < 6) { $row = $('.admins .row:last-child'); }
    else { $row = $('<div/>').addClass('row').addClass('blocklink-wrapper').appendTo('.admins'); }

    $el.detach().appendTo($row);
}

function deleteAdmin($el) {
    var $row;

    if ($('.members .row:last-child .user').length < 6) { $row = $('.members .row:last-child'); }
    else { $row = $('<div/>').addClass('row').addClass('blocklink-wrapper').appendTo('.members'); }

    $el.detach().appendTo($row);
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