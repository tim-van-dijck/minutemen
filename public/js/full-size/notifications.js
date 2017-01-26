var offset = 1;
var id, canExpand;
var notifications = messageNotifications = freqs = 0;

$(function() {
	bubbleHandler();
	setInterval(function () {
		bubbleHandler();
	}, 5000);

	if ($('#feed').length > 0) { setInterval(function () { getFeed(); }, 5000); }

	if ($('#feed').length > 0) {
        id = '';
        if ($("#feed").data('organisation') > 0) {
            id = '/' + $("#feed").data('organisation');
        }
        $('.load-feed.btn-load').click(function(e) {
            e.preventDefault();
            $(this).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
            feedExpand($(this));
        });
    }

    $('.post a.toggleSeen').click(function (e) {
    	e.preventDefault();
    	notificationSeen($(this).closest('.post'));
    });

    $('.post .content a').click(function (e) { notificationSeen($(this).closest('.post')); });
    canExpandFeed();
});

function getFreqs() {
	return $.get('ajax/friend-requests/count', function (count) {
		freqs = parseInt(count)
		if (freqs > 0) {
			$('.friend-bubble')
				.text(count)
				.show();
		} else {
			$('.friend-bubble').hide();
		}
	});
}

function getMessageNotifications() {
    return $.get('ajax/conversations/count', function (count) {
        messageNotifications = parseInt(count)
        if (messageNotifications > 0) {
            $('.message-bubble')
                .text(count)
                .show();
        } else {
            $('.message-bubble').hide();
        }
    });
}

function getNotifications() {
    return $.get('ajax/notifications/count', function (count) {
        notifications = parseInt(count);
        if (notifications > 0) {
            $('.notification-bubble')
                .text(count)
                .show();
        } else {
            $('.notification-bubble').hide();
        }
    });
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
	$.getJSON('ajax/feed/extend'+id, {offset: offset}, function(posts) {
        if (posts.length > 0) {
            $.each(posts, function(i,v) {
                var timestamp = v.updated_at;
                if (timestamp == null) { timestamp = v.created_at; }

                $post = '<div class="col-md-12 post">'+
                    '<div class="header">'+v.organisation.name+'</div><div class="content">'+
                    v.content+'</div><div class="footer">'+ v.created_at+'</div>'+
                '</div>';

                $('#feed-ext').append($post);
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

function notificationSeen($el) {
	$.get('ajax/notifications/'+$el.data('notification-id')+'/seen', function() {
		$el.find('i.fa-circle').removeClass('fa-circle').addClass('fa-circle-o');
	});
}

function bubbleHandler() {
    getFreqs().then(function() {
		getNotifications().then(function() {
			getMessageNotifications().then(function() {
                if (notifications + messageNotifications + freqs > 0) {
                    $('.excl-bubble').show();
                } else {
                    $('.excl-bubble').hide();
                }
            });
		});
	});
}