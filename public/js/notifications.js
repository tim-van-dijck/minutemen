var offset = 1;
var id;

$(function() {
	getNotifications();
	// setInterval(function () { getNotifications(); }, 5000);

	$('#lfg').change(function(e) { $.get('ajax/lfg'); });
	// if ($('#feed').length > 0) { setInterval(function () { getFeed(); }, 5000); }

	if ($('#feed').length > 0) {
        id = '';
        if ($("#feed").data('organisation') > 0) {
            id = '/' + $("#feed").data('organisation');
        }
        $('.load-feed.btn-load').click(function(e) {
            e.preventDefault();
            $(this).html('<i class="fa fa-spinner fa-spin"></i>');
            feedExpand($(this));
        });
    }
    canExpandFeed();
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
		// getFeed();
	}
}

function getFeed() {
		$.getJSON('ajax/feed'+id, function (posts) {
		$("#feed").empty();

		if (posts.length > 0) {
			$.each(posts, function(i,v) {
				var timestamp = v.updated_at;
				if (timestamp == null) { timestamp = v.created_at; }

				$('<div/>')
					.addClass('col-md-12')
					.addClass('post')
					.html(	'<div class="header">'+v.organisation.name+'</div>'+
							'<div class="content">'+v.content+'</div>'+
                			'<div class="footer">'+timestamp+'</div>')
					.appendTo('#feed');
			});
		}
		else { $("#feed").append('<div class="col-md-12"><p class="text-center">No posts yet.</p></div>'); }
	});
}

function feedExpand($el) {
	$.getJSON($el.data('href'), {offset: offset}, function(posts) {
        if (posts.length > 0) {
            $.each(posts, function(i,v) {
                var timestamp = v.updated_at;
                if (timestamp == null) { timestamp = v.created_at; }

                $('<div/>')
                    .addClass('col-md-12')
                    .addClass('post')
                    .html(	'<div class="header">'+v.organisation.name+'</div>'+
                        	'<div class="content">'+v.content+'</div>'+
                        	'<div class="footer">'+timestamp+'</div>')
                    .appendTo('#feed-ext');
            });
        }
        $el.html('Load more');
        offset++;
        canExpandFeed();
	});
}

function canExpandFeed() {
	$.get('ajax/feed/can-expand'+id, {offset: offset}, function(canExpand) {
		canExpand = parseInt(canExpand);
		if (!canExpand) { $('.load-feed').remove(); }
	});
}