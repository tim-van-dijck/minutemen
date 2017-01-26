$('#join').click(function (e) {
	e.preventDefault();
	ajaxJoin($(this));
});

$('#sub').click(function (e) {
	e.preventDefault();
	ajaxSub($(this));
});

$('.accept-deny a').click(function (e) {
	e.preventDefault();
	acceptDeny($(this));
});

$('a#accept').click(function(e) {
	e.preventDefault();
	$.get($(this).attr('href'), function() {
		location.reload();
	});
});

$('#lfg').change(function(e) {
    if ($('#lfg').prop('checked') && addressSet == false) {
        swal(
            {
                title: "No address found",
                text: 'We have no location to find a lobby for. Care to set one?',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, please",
                cancelButtonText: "No (ill advised)",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(confirmed) {
                if (confirmed) {
                	window.location = base_url+'settings';
                }
                else {
                	$.get('ajax/lfg', function () {
						if ($('#lfg').prop('checked')) { findLobby(); }
                });
                }
            }
        );
    }
    else {
    	$.get('ajax/lfg', function () {
    		if ($('#lfg').prop('checked')) { findLobby(); }
		});
    }
});

function ajaxJoin($a) {
	var href = $a.attr('href');

	$.get(href, function(data) {
		var dHref = $a.data('href');

		$a.attr('href', dHref)
			.data('href', href);

		$('form.delete, a#join').toggleClass('hidden');
	});
}

function ajaxSub($a) {
	var href = $a.attr('href');
	var text = 'Unsubscribe';

	$.get(href, function(data) {
		var dHref = $a.data('href');

		$a.attr('href', dHref)
			.data('href', href);

		if ($a.text() == text) { text = 'Subscribe'; }
		$a.text(text);
	});
}

function acceptDeny($el) {
	$request = $el.closest('.request');
	$.getJSON($el.attr('href'), function (data) {
		$request.remove();
		if ($('.requests .request').length < 1) { $('.requests').remove(); }
	});
}

function toggleLfg() {
	$.get(base_url+'ajax/lfg', function(data) {
        if (data.lfg == '1') {
            swal('Are you sure?');
            findLobby();
        }
        $('#lfg').prop('checked', false);
    });
}