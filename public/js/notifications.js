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
}