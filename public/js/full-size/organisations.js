$(function() {
    $('#q').keydown(function() {
        if ($(this).val().length) {
            $('.blocklink-wrapper').html('<div class="col-md-12 text-center"><i class="fa fa-circle-o-notch fa-spin"></i></div>');
            getOrganisations($(this).val());
        }
    });
});

function getOrganisations(query) {
    $.getJSON('admin/ajax/organisations/find?q='+query, function(data) {
        $('.blocklink-wrapper').empty();

        if (data.length > 0) { $('#trust-selected').show(); }
        else { $('#trust-selected').hide(); }

        $.each(data, function (i,v) {
            if (v.thumb == null) { v.thumb = 'img/organisation.png'; }
            $('main h1').text('Organisations');
            $('.blocklink-wrapper').append('<div class="col-md-2 blocklink"><div class="check">'+
                '<input id="org-'+v.id+'" type="checkbox" name="trusted[]" value="'+v.id+'">'+
                '<label for="org-'+v.id+'"></label></div><a href="organisations/'+v.id+'">'+
                '<div class="profile-img"><img src="'+v.thumb+'" alt="'+v.name+'"></div>'+
                '<p>'+v.name+'</p></a></div>');
        });
    });
}