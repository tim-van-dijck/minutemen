$( function() {

    $("#team-find").select2({
        placeholder: 'Your teams that qualify',
        ajax: {
            url: 'ajax/team/find/'+eventId,
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
        allowClear: true,
        minimumInputLength: 2,
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work,
        templateResult: formatTeam, // omitted for brevity, see the source of this page
        templateSelection: formatTeamSelection // omitted for brevity, see the source of this page
    });
});

function formatTeam (team) {
    if (team.loading) return team.text;
    var img = (team.emblem != null) ? team.emblem : 'img/emblem.png';

    var markup = '<div class="selectbox-result row blocklink-wrapper persist-cols">'+
        '<div class="col-md-12 blocklink user"><div class="row"><div class="col-md-4"><div class="profile-img">'+
        '<img src="'+img+'" alt="'+team.text+'"></div></div>'+
        '<div class="col-md-8"><p class="name">'+team.text+'</p></div></div></div></div>';

    return markup;
}

function formatTeamSelection(team) {
    return team.text;
}