$(function() {
    $('form.delete button[type="submit"]').click(function (e) {
        e.preventDefault();

        $form = $(this).closest('form')

        swal(
            {
                title: "Are you sure?",
                text: "Are you sure you want to " + $form.data('confirm')+"?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                closeOnConfirm: true
            }, function(confirmed) {
                if (confirmed) {
                    $form.submit();
                }
            }
        );
    });
});