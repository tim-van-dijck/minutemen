$(function() {
    $('#tutorial').modal('show');

    $('#hide-tutorial').submit(function(e) {
        e.preventDefault();

        $.post($(this).attr('action'), $(this).serialize());
        $('#tutorial').modal('hide');
    });
});