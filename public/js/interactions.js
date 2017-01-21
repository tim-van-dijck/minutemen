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
		console.log($request);
		if ($('.requests .request').length < 1) { $('.requests').remove(); }
	});
}

function toggleLfg() {
	$.get(base_url+'ajax/lfg', function() {
		$('#lfg').prop('checked', false);
	});
}