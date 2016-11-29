$(function() {
	getNotifications();
	setInterval(function () { getNotifications(); }, 5000);
});

function getNotifications() {
	$.get('ajax/notifications/count', function (count) {
		if (parseInt(count) > 0) {
			$('.notification-bubble')
				.text(count)
				.show();
		} else {
			$('.notification-bubble').hide();
		}
	});
	if ($('#feed').length > 0) {
		getFeed();
	}
}

function getFeed() {
	var id = '';
	if ($("#feed").data('organisation') > 0) {
		id = '/' + $("#feed").data('organisation');
	}
	$.getJSON('ajax/feed'+id, function (posts) {
		$("#feed").empty();

		if (posts.length > 0) {
			$.each(posts, function(i,v) {

				$('<div/>')
					.addClass('col-md-12')
					.addClass('post')
					.html(v.content)
					.appendTo('#feed');
			});
		}
		else { $("#feed").append('<div class="col-md-12"><p class="text-center">No posts yet.</p></div>'); }
	});
}