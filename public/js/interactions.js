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
	var text = 'Leave team';

	$.get(href, function(data) {
		var dHref = $a.data('href');

		$a.attr('href', dHref)
			.data('href', href);

		if ($a.text() == text) { text = 'Join team'; }
		$a.text(text);
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