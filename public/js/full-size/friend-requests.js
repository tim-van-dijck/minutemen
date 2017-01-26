$(function() {
    $('.add-friend').click(function(e) {
        e.preventDefault();
        $.post($(this).attr('href'), {_token: window.Laravel.csrfToken}, function() {
            swal({
                title: "Friend request sent!",
                type: "success",
                showCancelButton: false,
                confirmButtonColor: '#DD6B55',
                closeOnConfirm: true
            }, function () {
                location.reload();
            });
        });
    })
});