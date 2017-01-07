$(function() {
    $('#preview-img').hide();

    $('#full-img').change(function() {
        $('#edit-img').hide();
        $('#preview-img').show();
    });
});